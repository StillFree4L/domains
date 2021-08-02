<?php

if (IS_ADMIN OR $model->isVoted() == "1") {

    $variants = $model->pSurveyVariants();

    ?>
    <div class="survey_body">
        <div class="survey_body_bg bg"></div>
        <div class="survey_body_content cc">
    <?php

    $votes_overall = 0;
    if (!empty($variants))
    {

        $votes_overall = $model->votesOverall;
        foreach ($variants as $k=>$v)
        {

            if (intval($votes_overall) > 0) {
                $percent = floor(intval($v->votesCount)/intval($votes_overall)*100);
            } else {
                $percent = 0;
            }

            ?>
                <div class="variant_main">

                    <div class="variant_header variant_header_margin"><?=$v->name?></div>
                    <div class="variant_bar">
                        <div class="variant_bar_percent" style="width:<?=$percent?>%;"></div>
                        <div class="variant_bar_number"><?=$v->votesCount?></div>
                    </div>
                </div>

            <?php
        }

    }
    ?>
        </div>
    </div>
    <?php

} else {
    
    $variants = $model->pSurveyVariants();

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(    
    'htmlOptions'=>array(),
    ));

    ?>
    <div class="survey_body">
        <div class="survey_body_bg bg"></div>
        <div class="survey_body_content cc">
    <?php

    if (!empty($variants))
    {

        foreach ($variants as $k=>$v)
        {

            ?>
                <div class="variant_main">

                    <div class="variant_header">
                        <input class="variant_radio" id="PSurvey_<?=$model->id?>_variant" type='radio' name='PSurveyVote[<?=$model->id?>]' value='<?=$v->id?>' />
                        <label class="variant_label" for='PSurvey_<?=$model->id?>_variant'><?=$v->name?></label>
                        <div style="clear:both;"></div>
                    </div>
            
                </div>

            <?php
        }

    }

    $htmlOptions = array();
    $type = "submit";
    if ($model->isVoted() == "3") {
        $type = "link";
        $htmlOptions = array('data-html'=>true,
            'data-title'=>t("Регистрация"),
            'data-content'=>"Только авторизовавшиеся пользователи могут голосовать. Вы можете <a href='/admin'>войти</a> в систему под уже существующим аккаунтам, либо <a href='/admin/authentication/registration'>создать</a> новый.",
            'rel'=>'popover');
    }

    $this->widget("bootstrap.widgets.TbButton", array(
        "size"=>"small",
        "label"=>t("Проголосовать"),
        "buttonType"=>$type,
        "htmlOptions"=>$htmlOptions
    ));

    ?>
        </div>
    </div>
    <?php


    $this->endWidget();

}

?>