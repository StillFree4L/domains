$(function() {

    UpdateTime = function()
    {
        $("time").each(function() {
            if ($(this).attr("ts") && (time() - $(this).attr("ts") < 61*60)) {
                $(this).html(EDisplayDate($(this).attr('ts'), $(this).attr('ft')));
            }
        })
        setTimeout(UpdateTime, 5000);
    }
    setTimeout(UpdateTime, 5000);

    EDisplayDate = function(seconds, formatType) {

        if (!formatType)
        {
            formatType = 1;
        }

        this.getArrM = function()
        {
            return {
                '01':Yii.t("main","января"),
                '02':Yii.t("main","февраля"),
                '03':Yii.t("main","марта"),
                '04':Yii.t("main","апреля"),
                '05':Yii.t("main","мая"),
                '06':Yii.t("main","июня"),
                '07':Yii.t("main","июля"),
                '08':Yii.t("main","августа"),
                '09':Yii.t("main","сентября"),
                '10':Yii.t("main","октября"),
                '11':Yii.t("main","ноября"),
                '12':Yii.t("main","декабря")
            }

        }

        this.timeformat = function(seconds, formatType)
        {

            labelTime = date( 'd.m.Y', seconds );
            arrM = this.getArrM();

            if(formatType==2)
            {
                return date( 'd', seconds )+' '+arrM[date( 'm', seconds )]+' '+date( 'Y', seconds )+' '+Yii.t("main","в")+' '+date( 'H:i', seconds );
            }
            else
            {
                if ( labelTime == date( 'd.m.Y' ) ) {
                    if (time() - seconds < 60*60) {
                        var t = Math.ceil((time() - seconds)/60);
                        return t + " "+multiplier(t, [
                            Yii.t("main","минуту"),
                            Yii.t("main","минуты"),
                            Yii.t("main","минут")])+" "+Yii.t("main","назад");
                    }
                    return Yii.t("main","сегодня в")+' '+date( 'H:i', seconds );
                }
                else if ( labelTime == ( date( 'd' ) - 1 )+'.'+date( 'm.Y' ) ) {
                    return Yii.t("main","вчера в")+' '+date( 'H:i', seconds );
                }
                else if ( date( 'Y', seconds ) == date( 'Y' ) ) {
                    return date( 'd', seconds )+' '+arrM[date( 'm', seconds )]+', '+date( 'H:i', seconds );
                }
                else {
                    return date( 'd', seconds )+' '+arrM[date( 'm', seconds )]+' '+date( 'Y', seconds )+', '+date( 'H:i', seconds );
                }
            }
        }

        return this.timeformat(seconds, formatType);
    }

})