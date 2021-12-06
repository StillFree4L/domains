<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Sertificat;
use \app\models\Master;
use \app\models\Repairs;
use app\models\Services;
use app\models\Clients;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get','post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/
    public function actionIndex()
    {
        $master=Master::find()->count();
        $repairs=Repairs::find()->count();
        $sertificat=Sertificat::find()->count();
        $services=Services::find()->count();
        $clients=Clients::find()->all();
        return $this->render('index',compact('master','repairs','sertificat','services','clients'));
    }
    public function actionSertificat()
    {
        $models=Sertificat::find()->all();
        return $this->render('sertificat',compact('models'));
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    public function actionCreate()
    {
        $model = new Sertificat();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->gallery = UploadedFile::getInstances($model,'gallery');
            $model->uploadGallery();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = Sertificat::findOne($id);
        if($model->getImage()){$model->removeImages();}
        $this->findModel($id)->delete();

        return $this->redirect(['sertificat']);
    }
    /**
     * Finds the Repairs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sertificat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sertificat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого нет!');
    }
}
