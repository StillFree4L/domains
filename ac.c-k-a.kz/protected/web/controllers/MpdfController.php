<?php

namespace app\controllers;

use app\components\Controller;
use yii\base\Exception;
use yii\filters\AccessControl;

class MpdfController extends Controller
{

    public function actionIndex()
    {

        if (!\Yii::$app->request->post('data')) {
            throw new Exception("Specify data by _POST");
        }

        ini_set('memory_limit', '512M');

        $data = json_decode(\Yii::$app->request->post('data'));

        $p = \Yii::getAlias('@webroot');
        require_once($p."/protected/vendors/mpdf/mpdf.php");

        $o = "A4";
        if ($data->options->orientation == "L") {
            $o = 'A4-'.$data->options->orientation;
        }

        $mpdf = new \mPDF('utf-8', $o, '8', '', 10, 10, 7, 7, 10, 10);
        $mpdf->charset_in = 'utf-8';

        if ($data->styles) {

            foreach ($data->styles as $s) {

                if (strpos($s, $_SERVER['SERVER_NAME']) !== false) {
                    $fname = $_SERVER['DOCUMENT_ROOT'].str_replace("http://".$_SERVER['SERVER_NAME'], "",$s);
                    $fname = substr($fname, 0, (strpos($fname, "?")!==false ? strpos($fname, "?") : strlen($fname)));
                }

                if (!empty($fname)) {
                    $stylesheet = file_get_contents($fname);
                    $mpdf->WriteHTML(iconv("UTF-8","UTF-8//IGNORE",$stylesheet), 1);
                }

            }
        }

        $data->html = str_replace(FILES_HOST, FILES_ROOT, $data->html);

        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML(iconv("UTF-8","UTF-8//IGNORE",$data->html), 2); /*формируем pdf*/
        $mpdf->Output('mpdf.pdf', 'I');
        exit();

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }

}
?>