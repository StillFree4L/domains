<?php

namespace app\widgets\EDisplayDate;

use app\components\Widget;

class EDisplayDate extends Widget
{

    protected $backbone = true;

    public $time = 0;
    public $formatType =1; //type : 1 - 26 мая, 12.30;  2 26 мая в 12.30
    public function run()
    {
        $formatType=$this->formatType;
        if ($this->type != Widget::TYPE_TEMPLATE) {
            $label = $this->timeformat($this->time, $formatType);
        } else {
            $label = "<%=EDisplayDate(".$this->time.",".$formatType.")%>";
            $this->time = "<%=".$this->time."%>";
        }
        if($formatType==1)
            return $this->render("posted_date", [
                    "label"=>$label,
            ]);
        else
            return $this->render("on_date", [
                    "label"=>$label,
            ]);
    }	
    
    
    public function timeformat($time, $formatType)
    {

        $labelTime = date( 'd.m.Y', $time );
        $arrM = $this->getArrM();

      	if($formatType==2)
        {
           return date( 'd', $time ).' '.$arrM[date( 'm', $time )].' '.date( 'Y', $time ).' '.\Yii::t("main","w_in").date( 'H:i', $time );
        }	
        else
        {
            if ( $labelTime == date( 'd.m.Y' ) ) {
                if (time() - $time < 60*60) {
                    $t = ceil((time() - $time)/60);
                    return $t." ".glob\helpers\Common::multiplier($t, [
                            \Yii::t("main","w_minutes_1"),
                            \Yii::t("main","w_minutes_2"),
                            \Yii::t("main","w_minutes_3"),
                    ])." ".\Yii::t("main","w_before");
                }
                return \Yii::t("main","w_today_in").' '.date( 'H:i', $time );
            }
            elseif ( $labelTime == ( date( 'd' ) - 1 ).'.'.date( 'm.Y' ) ) {
                return \Yii::t("main","w_yesterday_in").' '.date( 'H:i', $time );
            }
            elseif ( date( 'Y', $time ) == date( 'Y' ) ) {
                return date( 'd', $time ).' '.$arrM[date( 'm', $time )].', '.date( 'H:i', $time );
            }
            else {
                return date( 'd', $time ).' '.$arrM[date( 'm', $time )].' '.date( 'Y', $time ).', '.date( 'H:i', $time );
            }
        }
    }

    public function getArrM()
    {
        return [
            '01'=>\Yii::t("main","w_january_in"),
            '02'=>\Yii::t("main","w_february_in"),
            '03'=>\Yii::t("main","w_march_in"),
            '04'=>\Yii::t("main","w_april_in"),
            '05'=>\Yii::t("main","w_may_in"),
            '06'=>\Yii::t("main","w_june_in"),
            '07'=>\Yii::t("main","w_july_in"),
            '08'=>\Yii::t("main","w_august_in"),
            '09'=>\Yii::t("main","w_september_in"),
            '10'=>\Yii::t("main","w_october_in"),
            '11'=>\Yii::t("main","w_november_in"),
            '12'=>\Yii::t("main","w_december_in")
        ];
    }
    
}
?>
