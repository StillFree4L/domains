<?php

namespace app\modules\admin\controllers;

use app\models\RepairsAuditSearch;
use app\modules\admin\controllers\AppAdminController;
use Yii;
use app\models\RepairsAudit;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class RepairsAuditController extends AppAdminController
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
                ],
            ],
        ];
    }

    /**
     * Lists all RepairsAudit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RepairsAuditSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RepairsAudit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the RepairsAudit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RepairsAudit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RepairsAudit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого нет!');
    }
}
