<?php

class HomeController extends BaseController
{
        
        public function f_exists($url)
        {
            $file_headers = @get_headers($url);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                return false;
            }
            else {
                return true;
            }
        }
    
        
        
        public function reasembleVersion($version)
        {
            $vr = "<?php return array(";
            foreach ($version as $k=>$v)
            {
                
                $r = "\"$v\"";
                
                if (is_array($v))
                {
                    $r = "array(";
                    foreach ($v as $vv)
                    {
                        $r .= "\"$vv\",";
                    }
                    $r = rtrim($r,",");
                    $r .= ")";
                }
                
                $vr .= "\"$k\"=>$r, \n";
            }
           
            $vr .= "); ?>";
            file_put_contents(Yii::getPathOfAlias("application.config")."/version.php", $vr);            
        }
    
        public function checkForUpdates($version)
        {
            
            $updates = array();            
            $current_version = explode(".",$version['version']);           
            
            for ($i = 3; $i>=0; $i--) {
                
                $current_version[$i]++;
                $cversion = implode(".",$current_version);                
                
                while ($this->f_exists("http://".$version['login'].":".$version['password']."@".$version['host']."/updates/$cversion/$cversion.sql") 
                        OR $this->f_exists("http://".$version['login'].":".$version['password']."@".$version['host']."/updates/$cversion/$cversion.zip")) 
                {
                   $current_version[$i]++;
                   $updates[] = $cversion;
                   $cversion = implode(".",$current_version);
                }
                $current_version[$i] = 0;
            }
            
            $version['updates'] = $updates;
            $version['last_checked'] = time();
            $this->reasembleVersion($version);
            
            return $version;
            
        }
        
        public function updateSystem()
        {
            
            set_time_limit(0);
            ignore_user_abort(true);
            
            $version = include(Yii::getPathOfAlias("application.config")."/version.php"); 
            $version = $this->checkForUpdates($version);                    
            
            if (!empty($version['updates']))
            {
                foreach ($version['updates'] as $k=>$update)
                {
                    
                     
                    
                    $sql_file = @fopen("http://".$version['login'].":".$version['password']."@".$version['host']."/updates/$update/$update.sql","r");
                    $zip_file = @fopen("http://".$version['login'].":".$version['password']."@".$version['host']."/updates/$update/$update.zip","r");
                    
                    if ($sql_file)
                    {     
                        $error = "";
                        $sqlUpdate = $this->splitSQL($sql_file);
                        
                        
                        if ($sqlUpdate !== true)
                        {
                            if (is_array($sqlUpdate))
                            {
                                                                
                                $error = "Обновление $update. ".$sqlUpdate['message'];
                                
                            } else {
                                $error = "Обновление $update. Ошибка файла";
                            }
                        } 
                        
                        if (!empty($error)) {
                            Yii::import('application.extensions.phpmailer.JPhpMailer');
                            $mail = new JPhpMailer;
                            $mail->IsSMTP();
                            $mail->CharSet = 'utf-8';  
                            $mail->Host = Yii::app()->baseOptions->site_host;                        
                            $mail->SetFrom('admin@'.Yii::app()->baseOptions->site_host, Yii::app()->baseOptions->system_name);
                            $mail->Subject = 'Ошибка обновления';                   
                            $mail->MsgHTML($error);
                            $mail->AddAddress("aloudnoise@mail.ru");
                            //$mail->Send();
                           
                            return false;
                        }
                        
                    }
                    $error = "";
             
                    if ($zip_file)
                    {
                        
                        copy("http://".$version['login'].":".$version['password']."@".$version['host']."/updates/$update/$update.zip",Yii::app()->params['uploadDir']."/patch.zip");
                        
                        $zip = new ZipArchive;
                        $zip->open(Yii::app()->params['uploadDir']."/patch.zip");
                        
                        if (!$zip->extractTo($_SERVER['DOCUMENT_ROOT']."/"))
                        {
                            $error = "Обновление $update. Ошибка архива";
                        }
                        
                        $zip->close(); 
                        
                        if (!empty($error)) {
                            Yii::import('application.extensions.phpmailer.JPhpMailer');
                            $mail = new JPhpMailer;
                            $mail->IsSMTP();
                            $mail->CharSet = 'utf-8';  
                            $mail->Host = Yii::app()->baseOptions->site_host;                        
                            $mail->SetFrom('admin@'.Yii::app()->baseOptions->site_host, Yii::app()->baseOptions->system_name);
                            $mail->Subject = 'Ошибка обновления';                   
                            $mail->MsgHTML($error);
                            $mail->AddAddress("aloudnoise@mail.ru");
                            //$mail->Send();
                           
                            return false;
                        }
                        
                    }
                    
                    $version['version'] = $update;
                    unset($version['updates'][$k]);
                    
                }
            }
            
            $this->reasembleVersion($version);
            
            return true;
            
        }
        
        function splitSQL($file, $delimiter = ';')
        {
            $transaction = Yii::app()->db->beginTransaction();
            
            if (is_resource($file) === true)
            {
                
                $query = array();

                while (feof($file) === false)
                {
                    $query[] = fgets($file);

                    if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1)
                    {
                        $query = trim(implode('', $query));

                        $command=Yii::app()->db->createCommand($query);

                        try {
                            
                            $command->execute();
                            
                        } catch (Exception $e) {
                            return array("result"=>"error",
                                "message"=>$e->getMessage());
                        }

                    }

                    if (is_string($query) === true)
                    {
                        $query = array();
                    }
                }

                $transaction->commit();
                fclose($file);      
                return true;


            }
            return true;
        }
        
        public function actionUpdate()
        {
            if ($this->updateSystem())
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Обновления успешно выполнено'));
            } else {
                Yii::app()->user->setFlash('fieldError', t('Ошибка обновления. Отчет был выслан разработчикам.'));
            }
            Yii::app()->request->redirect("/admin/".Yii::app()->language."/".Yii::app()->controller->id."/index");
        }
    
	public function actionIndex()
	{
            
                $version = include(Yii::getPathOfAlias("application.config")."/version.php"); 
                /* if ($version['last_checked'] < time()-(60*60*24)) {
                    $version = $this->checkForUpdates($version);                    
                } */
        
                
                                
                $month_graph = $this->getGraph(UserCountModel::model()->uniqueUsers()->monthly(date('m'))->findAll(), UserCountModel::model()->uniqueGuests()->monthly(date('m'))->findAll());
                $year_graph = $this->getGraph(UserCountModel::model()->uniqueUsers()->yearly(date('Y'))->findAll(), UserCountModel::model()->uniqueGuests()->yearly(date('Y'))->findAll());
     
                
                $this->render('index', array(
                    "version"=>$version,
                    "month_graph"=>$month_graph,
                    "year_graph"=>$year_graph,
                ));
	}
        
        public function getGraph($users_model, $guests_model)
        {
            $dates = array();
            $values['users'] = array();
            $values['guests'] = array();
            
            // extract dates and values
            foreach ($users_model as $k=>$v)
            {
                $dates[$v->ts] = $v->ts;
                $values['users'][$v->ts] = $v->count;
            }
            foreach ($guests_model as $k=>$v)
            {
                $dates[$v->ts] = $v->ts;
                $values['guests'][$v->ts] = $v->count;
            }
             
            $users = '<graph gid="1">';
            $guests = '<graph gid="2">';
            $series = "<series>";
            $last = "";
            foreach ($dates as $key => $value) {
             $series .= '<value xid="'.$key.'">'.$value.'</value>';
             $last = $value;
            }
            $series .= "</series>";
            $graphs = "<graphs>";
            foreach ($values['users'] as $key=>$value) {
                $users .= '<value xid="'.$key.'">'.$value.'</value>';
            }
            $users .= '</graph>';
            foreach ($values['guests'] as $key=>$value) {
                $guests .= '<value xid="'.$key.'">'.$value.'</value>';
            }
            $guests .= '</graph>';
            $graphs .= $users.$guests."</graphs>";
            $data = "<chart>".$series.$graphs."</chart>";
                 
                      
            return $data;
        }
        
        public function getSettings()
        {
            
            return '<settings>

<font>Arial,Sans-Serif</font>
<text_color>#770000</text_color>
<plot_area>
<margins>
<left>50</left>
<top>30</top>
<right>50</right>
<bottom>100</bottom>
</margins>
</plot_area>
<scroller>
<y>340</y>
<color>#770000</color>
<bg_color>#dddddd</bg_color>
<bg_alpha>20</bg_alpha>
</scroller>

<grid>
<x>
    <enabled>true</enabled>
    <color>#dddddd</color>
    <dashed>true</dashed>
    <alpha>100</alpha>
</x>
<y_left>
    <enabled>false</enabled>
</y_left>
</grid>

<values>
<y_left>
<unit_position>left</unit_position>
<skip_last>true</skip_last>
</y_left>
</values>
<axes>
<x>
<color>#000000</color>
<alpha>25</alpha>
<width>1</width>
</x>
<y_left>
<alpha>10</alpha>
<width>1</width>
</y_left>
</axes>
<indicator>
<selection_color>#000000</selection_color>
<selection_alpha>25</selection_alpha>
<one_y_balloon>false</one_y_balloon>
</indicator>
<legend>
<x>50</x>
<y>370</y>
<text_color_hover>#fff</text_color_hover>
<values>    
<enabled>false</enabled>
<text>{value}</text>
</values>
</legend>
<zoom_out_button>
    
<x>83%</x>
<y>360</y>
<text>Показать все</text>
<alpha>100</alpha>
<text_color>#ffffff</text_color>
<text_color_hover>#ff8800</text_color_hover>
<color>#993333</color>
</zoom_out_button>
<graphs>
<graph gid="1">
<axis>left</axis>
<title>Пользователи</title>
<balloon_text_color>#ffffff</balloon_text_color>
<bullet>round</bullet>
<bullet_size>4</bullet_size>
<balloon_text>
<b>{value}</b>
</balloon_text>
<line_width>2</line_width>
</graph>
<graph gid="2">
<axis>left</axis>
<title>Гости</title>
<balloon_text_color>#ffffff</balloon_text_color>
<bullet>round</bullet>
<bullet_size>4</bullet_size>
<balloon_text>
<b>{value}</b>
</balloon_text>
<line_width>2</line_width>
</graph>
</graphs>
</settings>';
        }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}