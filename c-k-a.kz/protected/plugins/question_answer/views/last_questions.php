<div class="last_quesiton">
    <?php if ($this->label) { ?>
    <a class="text_color last_question_header" href="/<?=Yii::app()->language?>/page/question_answer"><?=t('Вопрос-ответ')?></a>
    <?php } ?>

    <div class="last_question_body">
        <?php
        if (!empty($questions)) {
        foreach ($questions as $qquestion)
        {
            ?>

                <div class="comment">

                    <span class="date system_font_color"><?=date("d.m.Y",$qquestion->ts)?></span><span class="user"><?=$qquestion->name?></span>

                    <div class="comment_text">
                        <?=$qquestion->question?>
                    </div>

                    <?php if (!empty($qquestion->answer))
                    {
                        ?>


                    <span class="comment_answer_header"><?=t("Ответ:")?></span>
                    <div class="comment_answer">
                        <?php
                            $words = explode(" ",$qquestion->answer);
                            if (count($words)>20) {
                            $words = array_slice($words,count($words)-20);
                            $answer = implode(" ",$words)." ...";
                            echo $answer;
                            echo "<a href='/".Yii::app()->language."/page/question_answer#question_".$qquestion->id."'>".t("Читать далее")."</a>";
                            } else {
                                echo $qquestion->answer;
                            }
                        ?>
                    </div>

                        <?php
                    }
                    ?>

                </div>

            <?php
        }
        }
    ?>
    </div>

</div>