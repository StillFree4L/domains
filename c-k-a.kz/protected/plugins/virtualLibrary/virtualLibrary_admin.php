<?php

class virtualLibrary_admin extends CWidget
{
    public function run()
    {
        
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/virtualLibrary.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/virtualLibrary.js', CClientScript::POS_HEAD);
        
        if (isset($_FILES['import_xml']))
        {
            $this->import($_FILES['import_xml']['tmp_name']);
        }
        
        if (isset($_GET['act'])) {
            call_user_func(array($this,$_GET['act']));
           
        } else {
        
            if (isset($_POST['submitType']))
            {

                if (isset($_POST['Books']))
                {


                    foreach ($_POST['Books'] as $id)
                    {

                        switch ($_POST['submitType'])
                        {

                            case "delete":
                                $instance = PVirtualLibrary::model()->deleteByPk($id);
                                break;
                        }

                    }

                    Yii::app()->request->redirect( Yii::app()->request->url );
                }

            }

            $model=new PVirtualLibrary('search');
            $model->resetScope();
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['PVirtualLibrary']))
                    $model->attributes=$_GET['PVirtualLibrary'];

            $this->render("admin/index", array(
                'model'=>$model,
            ));
        }
        
    }
    
    public function add()
    {
        
        $model = new PVirtualLibrary();
        
        $new = true;
        if (isset($_GET['book']))
        {
            $new = false;
            $model = PVirtualLibrary::model()->findByPk($_GET['book']);
        }
        
        if (isset($_POST['PVirtualLibrary']))
        {
            $model->attributes = $_POST['PVirtualLibrary'];
            
            if ($model->validate() AND $model->save()) {
                if ($new) {
                    Yii::app()->user->setFlash('fieldSubmitted', t('Книга успешно добавлена'));
                    Yii::app()->request->redirect("/admin/".Yii::app()->language."/".Yii::app()->controller->id."/virtualLibrary");
                } else {
                    Yii::app()->user->setFlash('fieldSubmitted', t('Книга успешно обновлена'));
                }                

            } else {

                Yii::app()->user->setFlash('fieldError', t('Ошибка добавления книги'));                
            }
        }
        
        
        $l = PVirtualLibrary::model()->findAllBySql("SELECT DISTINCT library_name FROM p_virtual_library ORDER BY library_name");
        $libraries = array();
        foreach ($l as $lib)
        {
            $libraries[] = $lib->library_name;
        }
                
        $this->render("admin/add",array(
            "model"=>$model,
            "libraries"=>$libraries
        ));
    }
    
    public function import($xml_file_name)
    {
        
        set_time_limit(0);
        
        $xml = simpleXML_load_file($xml_file_name,"SimpleXMLElement",LIBXML_NOCDATA); 
        if($xml ===  FALSE) 
        { 
           Yii::app()->user->setFlash('fieldError', t('Файл должен быть в формате XML'));
        } 
        
        
        if (isset($xml->xmlkniga))
        {
            
            $saved = 0;
            $all = 0;
            foreach ($xml->xmlkniga as $k=>$v)
            {
                $book = new PVirtualLibrary();
                $book->refreshMetaData();                
                $book->book_name = $v->namebook0name;
                $book->book_year = $v->invbook0god;
                $book->book_price = intval($v->invbook0price);
                $book->book_lang = $v->lang0name;
                
                if (!empty($v->invbook0isbn))
                $book->book_isbn = $v->invbook0isbn;
                
                $book->book_country = $v->invbook0strana;
                $book->book_code = $v->invbook0kodnamebook;
                $book->book_preview = trim($v->invbook0sod," ");
                $book->pub_view = $v->vidizd0name;
                $book->pub_name = $v->izdatel0name;
                $book->pub_city = $v->izdat0name;
                $book->pub_dep = $v->otdel0name;
                $book->pub_code = $v->invbook0kodizdat;
                $book->library_name = $v->bible0name;
                $book->author_code = $v->invbook0kodauthor;
                
                $authors = "";
                if (!empty($v->author0name))
                {
                    $authors.= $v->author0name.", ";
                }
                if (!empty($v->author_a0name))
                {
                    $authors.= $v->author_a0name.", ";
                }
                if (!empty($v->author_b0name))
                {
                    $authors.= $v->author_b0name.", ";
                }
                if (!empty($v->author_c0name))
                {
                    $authors.= $v->author_c0name.", ";
                }
                if (!empty($v->author_d0name))
                {
                    $authors.= $v->author_d0name.", ";
                }
                 
                $authors = rtrim($authors,", ");
                $book->author_name = $authors;
                
                if ($book->validate() AND $book->save())
                {
                    $saved++;
                } else {
                    print_r($book->getErrors());
                }
                $all++;
                
                
                
            }
            Yii::app()->user->setFlash('fieldSuccess', "Сохранено $saved из $all");
        } else if (isset($xml->bd1))
        {
            foreach ($xml->bd1 as $k=>$v)
            {
                $book = new PVirtualLibrary();
                $book->refreshMetaData();                
                $book->book_name = $v->заглавие_x0020_книги;
                $book->book_year = $v->год_x0020_издания;                
                $book->book_lang = $v->язык_x0020_текста;
                $book->book_code = $v->инвентарный_x0020_номер;
                
                if (!empty($v->ISBN) AND $v->ISBN!="0")
                $book->book_isbn = $v->ISBN;
                
                $book->book_preview = trim($v->аннотация," ");
                $book->pub_view = $v->тематическая_x0020_рубрика;
                $book->pub_name = $v->место_x0020_издания_x0020_и_x0020_издательство;                
                $book->pub_dep = $v->серия;
                $book->library_name = 'Факультет Языка и перевода';
                                
                $authors = "";
                if (!empty($v->авторы_x0020_книги))
                {
                    $authors.= $v->авторы_x0020_книги.", ";
                }
                
                 
                $authors = rtrim($authors,", ");
                $book->author_name = $authors;
                
                if ($book->validate() AND $book->save())
                {
                    $saved++;
                } else {
                    print_r($book->getErrors());
                }
                $all++;
                
                
            }
            Yii::app()->user->setFlash('fieldSuccess', "Сохранено $saved из $all");
        } else if (isset($xml->asd))
        {
            $name = "";
            foreach ($xml->asd as $k=>$v)
            {
                if (strcmp($v->F1, $name) !== 0) {
                $name = $v->F1;
                $book = new PVirtualLibrary();
                $book->refreshMetaData();                
                $book->book_name = $v->F1;      
                $book->library_name = 'Факультет Педагогики и социальной работы';
                
                if ($book->validate() AND $book->save())
                {
                    $saved++;
                } else {
                    print_r($book->getErrors());
                }
                $all++;
                
                }
                
                
            }
            Yii::app()->user->setFlash('fieldSuccess', "Сохранено $saved из $all");
        } else {
            Yii::app()->user->setFlash('fieldError', t('Неправильная структура файла'));
        }
        
    }
    
}

?>
