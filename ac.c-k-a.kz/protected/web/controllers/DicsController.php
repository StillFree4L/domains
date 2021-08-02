<?php

namespace app\controllers;

use glob\components\ActiveRecord;
use app\components\Controller;
use glob\helpers\Common;
use glob\models\Dics;
use glob\models\DicValues;
use glob\models\FilterForm;
use glob\models\Users;
use yii\filters\AccessControl;

class DicsController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \glob\helpers\Common::createUrl("/main/index"));
        return $p;
    }

    public function actionIndex()
    {

        $filter = new FilterForm();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $dics = Dics::find()
            ->with(["values"])
            ->orderBy("ts DESC");

        if ($filter->s) {
            $dics->andWhere(["LIKE", "info", "{$filter->s}"]);
        }

        $dics = $dics->all();

        return $this->render("index", [
            "filter"=>$filter,
            "dics"=>$dics
        ]);

    }

    public function actionAdd()
    {
        $dic = new Dics();
        if (\Yii::$app->request->get('id')) {
            $dic = Dics::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if (\Yii::$app->request->post('Dics')) {

            $dic->attributes = \Yii::$app->request->post('Dics');
            $dic->setInfo("description", \Yii::$app->request->post('Dics')['description']);

            if ($dic->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Справочник успешно добавлен"));
                return $this->renderJSON([
                    "redirect"=>\glob\helpers\Common::createUrl("/dics/index")
                ]);
            } else {
                return $this->renderJSON($dic->getErrors(), true);
            }

        }

        \Yii::$app->data->dic = ActiveRecord::arrayAttributes($dic, [],  ['id', 'name', 'description'], true);
        \Yii::$app->data->rules = $dic->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $dic->attributeLabels();

        return $this->render("form", [
            "dic" => $dic
        ]);
    }

    public function actionAddv()
    {
        $dicv = new DicValues();
        if (\Yii::$app->request->get('id')) {
            $dicv = DicValues::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if (\Yii::$app->request->post('DicValues')) {

            $dicv->attributes = \Yii::$app->request->post('DicValues');
            if ($dicv->isNewRecord) {
                $dicv->dic_id = \Yii::$app->request->get('did');
            }

            if ($dicv->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Значение успешно добавлено"));
                return $this->renderJSON([
                    "redirect"=>\glob\helpers\Common::createUrl("/dics/index")
                ]);
            } else {
                return $this->renderJSON($dicv->getErrors(), true);
            }

        }

        \Yii::$app->data->dicv = ActiveRecord::arrayAttributes($dicv, [],  ['id', 'name'], true);
        \Yii::$app->data->rules = $dicv->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $dicv->attributeLabels();

        return $this->render("formv", [
            "dicv" => $dicv
        ]);
    }

    public function actionDelete()
    {

        if (\Yii::$app->request->get('id')) {
            $dic = Dics::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($dic AND $dic->delete()) {

            \Yii::$app->session->setFlash("success",\Yii::t("main","Словарь успешно удален"));

        }

        \Yii::$app->response->redirect(Common::createUrl("/dics/index"));

    }

    public function actionDeletev()
    {

        if (\Yii::$app->request->get('id')) {
            $dicv = DicValues::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($dicv AND $dicv->delete()) {

            \Yii::$app->session->setFlash("success",\Yii::t("main","Значение успешно удалено"));

        }

        \Yii::$app->response->redirect(Common::createUrl("/dics/index"));

    }

    public function actionDautocomplete()
    {
        return $this->renderJSON(Dics::autoComplete(\Yii::$app->request->get('attribute'), \Yii::$app->request->get('query')));
    }

    public function actionVautocomplete()
    {

        $data = DicValues::find()->filterWhere(["like", "dic_values.name", \Yii::$app->request->get('query')])
            ->joinWith([
                "dic"
            ])
            ->andWhere("dics.name = :name", [
                ":name"=>\Yii::$app->request->get('dic')
            ])
            ->distinct(true)->all();

        $result = [
            "query"=>\Yii::$app->request->get('query'),
            "suggestions"=>[]
        ];
        if (!empty($data)) {
            foreach($data as $d) {
                $result['suggestions'][] = $d->name;
            }
        }
        return $this->renderJSON($result);
    }

    public function actionAutocomplete()
    {
        return $this->renderJSON(DicValues::autoComplete(\Yii::$app->request->get('attribute'), \Yii::$app->request->get('query')));
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Users::ROLE_ADMIN],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?', '@']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }


}

?>