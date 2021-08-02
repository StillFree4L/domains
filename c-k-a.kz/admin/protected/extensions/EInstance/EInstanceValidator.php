<?php

class EInstanceValidator
{

    

    public static function validateInstance($model)
    {
        // validate fields
        if (isset($model->caption) AND empty($model->caption)) $model->caption = null;       

        if (empty($model->id))
        {
            $model = self::_addInstance($model);
        } else
        {
            $model = self::_editInstance($model);
        }
                
        return $model;
    }
    private static function _addInstance($model)
    {

        if ($model->validate() AND $model->save()) {
            
            if ($model->id) {

                Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно добавлена').". <a class='btn btn-small' href='/admin/".Yii::app()->language.'/'.Yii::app ()->controller->id."/add'>".t("Добавить еще")."</a>");
                Yii::app()->request->redirect( '/admin/'.Yii::app()->language.'/'.Yii::app ()->controller->id.'/add/iid/'.$model->id );
            }

        } else {
            
        }
        return $model;
    }
    private static function _editInstance($model)
    {

        if ($model->validate() AND $model->save())
        {
            Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно обновлена').". <a class='btn btn-small' href='/admin/".Yii::app()->language.'/'.Yii::app ()->controller->id."'>".t("Вернуться к списку")."</a>");
        }
        return $model;

    }    

}

?>
