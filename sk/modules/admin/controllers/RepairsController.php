<?php

namespace app\modules\admin\controllers;

use app\models\Complete;
use app\models\Master;
use app\models\Material;
use Yii;
use app\models\Repairs;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\RepairsSearch;

class RepairsController extends AppMasterController
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
                    'delete' => ['POST'],
                    'deleteImage' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * Lists all Repairs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RepairsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $result = Repairs::find()->orderBy('id DESC')->limit(1)->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'result'=>$result,
        ]);
    }

    /**
     * Displays a single Repairs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $complete = Complete::find()->andWhere(['repairs_id'=>$id]);
        $dataProvider = new ActiveDataProvider([
            'query' =>$complete,
        ]);
        $complete=$complete->all();

        $material = Material::find()->andWhere(['repairs_id'=>$id]);
        $dataProviderMaterial = new ActiveDataProvider([
            'query' =>$material,
        ]);
        $material=$material->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider,
            'dataProviderMaterial'=>$dataProviderMaterial,
            'complete'=>$complete,
            'material'=>$material,
        ]);
    }

    public function actionCompleteCreate()
    {
        if(Yii::$app->request->get('type')=='Материалы')
        {$model = new Material();}
        else{$model = new Complete();}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => Yii::$app->request->get('id')]);
        }
        $repairs = Repairs::find()->andWhere(['id'=>Yii::$app->request->get('id')])->all();

        return $this->render('complete/create', [
            'model' => $model,
            'repairs'=>$repairs,
            'type'=>Yii::$app->request->get('type'),
        ]);
    }
    public function actionCompleteUpdate($id)
    {
        if(Yii::$app->request->get('type')=='Материалы')
        {$model = new Material();}
        else{$model = Complete::findOne($id);}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => Yii::$app->request->get('repairs_id')]);
        }
        $repairs = Repairs::find()->andWhere(['id'=>Yii::$app->request->get('repairs_id')])->all();

        return $this->render('complete/create', [
            'model' => $model,
            'repairs'=>$repairs,
            'type'=>Yii::$app->request->get('type'),
        ]);
    }
    public function actionCompleteDelete($id)
    {
        if(Yii::$app->request->get('type')=='Материалы') {
            if($model = Material::findOne($id)){$model->delete();}
        }
        else{
            if($model = Complete::findOne($id)){$model->delete();}
        }
        return $this->redirect(['view', 'id' => Yii::$app->request->get('repairs_id')]);
    }

    public function actionCreate()
    {
        $model = new Repairs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->gallery = UploadedFile::getInstances($model,'gallery');
            $model->uploadGallery();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->gallery = UploadedFile::getInstances($model,'gallery');
            $model->uploadGallery();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$model->getImage()==null){$model->removeImages();}
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionDeleteimg($id,$imgId)
    {
        $model = $this->findModel($id);
        $image = $model->getImages();
        foreach ($image as $img) {
            if ($img->id == $imgId)
            {$model->removeImage($img);break;}
        }
        return $this->redirect(["view", "id" => $id]);
    }
    public function actionReportReady($id) {
        $content = $this->renderPartial('pdf-ready', [
            'model' => $this->findModel($id),
        ]);
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '*{font-size:14px}',
        ]);
        return $pdf->render();
    }
    public function actionReportService($id) {
        $content = $this->renderPartial('pdf-service', [
            'model' => $this->findModel($id),
        ]);
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        ]);
        return $pdf->render();
    }
    public function actionReportApp($id) {
        $content = $this->renderPartial('pdf-app', [
            'model' => $this->findModel($id),
        ]);
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
        ]);
        return $pdf->render();
    }


    /**
     * Finds the Repairs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Repairs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Repairs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого нет!');
    }
}
