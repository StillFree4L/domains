<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Тест"), false);
?>

<div class="container test-controller">

    <div class="action-content">

        <script type="text/template" id="time_left_template">
            <div class="time-left text-center bg-primary" style="padding:5px; margin-bottom:10px;">
                <%
                    var time = data.model.get("timeLeft");
                %>
                <div class="inline-block">
                    <p><?=Yii::t("main","Время")?>:</p>
                </div>
                <div class="inline-block">
                    <p><strong> <%=Math.floor(time/60) < 10 ? "0" + Math.floor(time/60) : Math.floor(time/60)%> : <%=(time % 60) < 10 ? "0" + (time % 60) : (time % 60)%></strong></p>
                </div>
            </div>
        </script>

        <script type="text/template" id="question_template">
            <div class="question-item" style="margin-bottom:30px;" id="question_<%=data.model.get("n")%>">
                <div class="page-header" style="margin-top:0; margin-bottom:5px;">
                    <h4 style="margin-top:0; margin-bottom:5px;"><strong><%=data.model.get("n")%>.</strong> <%=data.model.get("question")%></h4>
                </div>

                <%
                    _(data.model.get("testAnswers")).each(function(a) {
                        %>
                        <a style="display: block; text-align: left;" mark="<%=a.mark%>" class="answer btn btn-<%=a.selected == 1 ? "primary" : "link" %>"><strong style="font-size:1.2em;"><%=a.mark%>.</strong> <%=a.answer%></a>
                        <%
                    });
                %>

            </div>
        </script>

        <script type="text/template" id="question_navigation_template">
            <div class="inline-block" style="margin:0px 2.5px 5px 2.5px;">
                <%
                    var selected = _(data.model.get("testAnswers")).findWhere({
                            selected : 1
                        });
                %>
                <a style="width:33px;" class="btn  btn-sm btn-<%=selected ? "primary" : "warning" %>"><%=data.model.get("n")%></a>
            </div>
        </script>

        <div class="row">
            <div class="col-xs-2 text-center">
                <div class="navigation">

                    <div class="time-left">

                    </div>

                    <div class="items">

                    </div>

                    <div class="finish-test" style="margin-top:15px;">
                        <a confirm="<?=Yii::t("main","Вы уверены?")?>" href="<?=\glob\helpers\Common::createUrl("/tests/finish", ["id"=>$test->id])?>" class="btn btn-danger btn-lg"><?=Yii::t("main","Завершить")?></a>
                    </div>

                </div>
            </div>

            <div class="col-xs-10 questions">

            </div>

        </div>

    </div>

</div>