<script type = "text/javascript">
setInterval(() => {
        let img = document.querySelectorAll('td.x-grid-cell-realizationreport_id img');
        let div_img = document.querySelectorAll('td.x-grid-cell-realizationreport_id');
        let i=0;
        let j=0;

        while (i < div_img.length){
            if (div_img[i].innerText != '' && div_img[i].innerText != 'Итого:') {
                img[i].hidden = false;
            }else{
                j=i;
            }
            i++;
        }

        <?php if ($_GET['type'] == 9 and !$_GET['rid']): ?>

        var re = /\B(?=(\d{3})+(?!\d))/g;

        let retail_amount = Ext.select(".x-grid-cell-retail_amount a").elements;
        let all_cost = Ext.select(".x-grid-cell-all_cost a").elements;
        let nalog = Ext.select(".x-grid-cell-nalog7 a").elements;
        let inp_delivery_rub =Ext.select(".x-grid-cell-delivery_rub a").elements;
        let inp_ppvz_vw =Ext.select(".x-grid-cell-ppvz_vw a").elements;
        let inp_ppvz_vw_nds =Ext.select(".x-grid-cell-ppvz_vw_nds a").elements;
        let ss_one =Ext.select(".x-grid-cell-ss_one a").elements;
        let pribil =Ext.select(".x-grid-cell-pribil a").elements;
        let marga =Ext.select(".x-grid-cell-marga a").elements;

        let inp_storage_cost = document.querySelectorAll('input.inputValue#storage_cost');
        let inp_acceptance_fee = document.querySelectorAll('input.inputValue#acceptance_fee');
        let inp_other_deductions = document.querySelectorAll('input.inputValue#other_deductions');

        let sum_nalog = 0;
        let all_costss = 0;
        let all_marga = 0;
        let all_pribils =0;
        i=0;
        let pay = Ext.select("#pay-btnInnerEl").elements[0].innerText;
        let percent = document.getElementById('percent-inputEl').value;

        if (percent != '' && percent != null && percent != 0)
        {

            if(percent != <?=$perc?>){$.get("/wb/update/update_nine.php", {percent:percent}, function (res){});}

            if (img && nalog && retail_amount && percent && all_cost){
                    while (i < div_img.length) {
                        if (div_img[i].innerText != ''){
                            if (retail_amount[i] || percent || all_cost[i] || nalog[i]) {
                            if (pay == 'Доходы') {
                                nal = Number(retail_amount[i].innerText.replace(" ", "").replace(" ", "")) * (Number(percent) / 100);

                            }
                            else if (pay == 'Доходы - Расходы') {
                                nal = (Number(retail_amount[i].innerText.replace(" ", "").replace(" ", "")) - Number(all_cost[i].innerText.replace(" ", "").replace(" ", ""))) * (Number(percent) / 100);
                            }
                        }

                        all_co = Number(inp_storage_cost[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_acceptance_fee[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_other_deductions[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_delivery_rub[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_ppvz_vw[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_ppvz_vw_nds[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(ss_one[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(nal);

                        all_cost[i].innerText = all_co.toFixed(2).replace(re, " ");

                        nalog[i].innerText = nal.toFixed(2).replace(re, " ");
                        sum_nalog += Number(nal);
                        marg = (Number(pribil[i].innerText.replace(" ", "").replace(" ", ""))/Number(all_co))*100;
                        marga[i].innerText = marg.toFixed(2).replace(re, " ");
                        prib = (Number(retail_amount[i].innerText.replace(" ", "").replace(" ", "")) - all_co);
                        pribil[i].innerText = prib.toFixed(2).replace(re, " ");

                        all_costss += all_co;
                        all_marga += marg;
                        all_pribils += prib;

                        }
                        i++;
                    }
                }
                if (sum_nalog){
                    Ext.select("td.x-grid-cell-nalog7").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_nalog.toFixed(2).replace(re, " ") + '</div>';
                }
                if (all_costss){
                    Ext.select("td.x-grid-cell-all_cost").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + all_costss.toFixed(2).replace(re, " ") + '</div>';
                }

                if (all_pribils){
                    Ext.select("td.x-grid-cell-pribil").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + all_pribils.toFixed(2).replace(re, " ") + '</div>';
                }
                if (all_marga){
                    Ext.select("td.x-grid-cell-marga").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + all_marga.toFixed(2).replace(re, " ") + '</div>';
                }
            }
        i=0;
        let sum_storage_cost=0;
        let sum_acceptance_fee=0;
        let sum_other_deductions=0;
        if (inp_storage_cost && inp_acceptance_fee && inp_other_deductions){
            while (i < inp_storage_cost.length) {
                if (i != j){
                    inp_storage_cost[i].hidden = false;
                    inp_acceptance_fee[i].hidden = false;
                    inp_other_deductions[i].hidden = false;
                    sum_storage_cost += Number(inp_storage_cost[i].value);
                    sum_acceptance_fee += Number(inp_acceptance_fee[i].value);
                    sum_other_deductions += Number(inp_other_deductions[i].value);
                }
                i++;
            }
        }
        if (sum_storage_cost)
        {
            if (inp_storage_cost.length>j) {
                p=j;
                sum_storage_cost += Number(inp_storage_cost[p].value);
                sum_acceptance_fee += Number(inp_acceptance_fee[p].value);
                sum_other_deductions += Number(inp_other_deductions[p].value);
            }
            Ext.select("td.x-grid-cell-storage_cost").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_storage_cost.toFixed(2).replace(re, " ") + '</div>';
            Ext.select("td.x-grid-cell-acceptance_fee").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_acceptance_fee.toFixed(2).replace(re, " ") + '</div>';
            Ext.select("td.x-grid-cell-other_deductions").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_other_deductions.toFixed(2).replace(re, " ") + '</div>';
        }
        <?php endif; ?>
    }, 1500);
</script>