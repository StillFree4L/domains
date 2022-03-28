<script async type = "text/javascript">

  setInterval(() => {
        let img = document.querySelectorAll('td.x-grid-cell-realizationreport_id img');
        let div_img = document.querySelectorAll('td.x-grid-cell-realizationreport_id');
        let i=0;
        let j=0;

        while (i < div_img.length){
            if (img[i] && div_img[i].innerText != '' && div_img[i].innerText != 'Итого:') {
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

        let all_costss = 0;
        let all_marga = 0;
        let all_pribils =0;
        let nal = 0;
        i=0;
        let pay = '<?=$pay?>';//Ext.select("#pay-btnInnerEl").elements[0].innerText;
        let percent = <?=$perc?>;//document.getElementById('percent-inputEl').value;
      //  console.log( percent);

        if (percent != '' && percent != null && percent != 0)
        {

            if(percent != <?=$perc?>){$.get("/wb/update/update.php", {percent:percent}, function (res){});}

            if (img && nalog && retail_amount && percent && all_cost){
                    while (i < div_img.length) {
                        if (div_img[i].innerText != ''){

                          if(pay === 'on' && inp_storage_cost[i]){
                            nal = Number((Number(retail_amount[i].innerText.replace(" ", "").replace(" ", "")) - (
                              Number(inp_storage_cost[i].value.replace(" ", "").replace(" ", ""))
                                  + Number(inp_acceptance_fee[i].value.replace(" ", "").replace(" ", ""))
                                  + Number(inp_other_deductions[i].value.replace(" ", "").replace(" ", ""))
                                  + Number(inp_delivery_rub[i].innerText.replace(" ", "").replace(" ", ""))
                                  + Number(inp_ppvz_vw[i].innerText.replace(" ", "").replace(" ", ""))
                                  + Number(inp_ppvz_vw_nds[i].innerText.replace(" ", "").replace(" ", ""))
                                  + Number(ss_one[i].innerText.replace(" ", "").replace(" ", ""))
                            )) * percent);//.toFixed(2).replace(re, " ");
                            nalog[i].innerText = nal.toFixed(2).replace(re, " ");
                            store.data.map[inp_storage_cost[i].getAttribute('idd')].data.nalog7 = nal;
                          }

                      if(inp_storage_cost[i]){
                        all_co = Number(inp_storage_cost[i].value.replace(" ", "").replace(" ", ""))
                            + Number(inp_acceptance_fee[i].value.replace(" ", "").replace(" ", ""))
                            + Number(inp_other_deductions[i].value.replace(" ", "").replace(" ", ""))
                            + Number(inp_delivery_rub[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_ppvz_vw[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(inp_ppvz_vw_nds[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(ss_one[i].innerText.replace(" ", "").replace(" ", ""))
                            + Number(nalog[i].innerText.replace(" ", "").replace(" ", ""));

                        all_cost[i].innerText = all_co.toFixed(2).replace(re, " ");
                        //console.log(inp_storage_cost[i].getAttribute('idd'));

                        marg = (Number(pribil[i].innerText.replace(" ", "").replace(" ", ""))/Number(all_co))*100;
                        marga[i].innerText = marg.toFixed(2).replace(re, " ");
                        prib = (Number(retail_amount[i].innerText.replace(" ", "").replace(" ", "")) - all_co);
                        pribil[i].innerText = prib.toFixed(2).replace(re, " ");

                        store.data.map[inp_storage_cost[i].getAttribute('idd')].data.all_cost = all_co.toFixed(2);//.toFixed(2).replace(re, " ");
                        store.data.map[inp_storage_cost[i].getAttribute('idd')].data.marga = marg.toFixed(2);//.toFixed(2).replace(re, " ");
                        store.data.map[inp_storage_cost[i].getAttribute('idd')].data.pribil = prib.toFixed(2);//.toFixed(2).replace(re, " ");

                        all_costss += all_co;
                        all_marga += marg;
                        all_pribils += prib;
                      }
                        }
                        i++;
                    }
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
                sum_storage_cost += Number(inp_storage_cost[p].value.replace(" ", "").replace(" ", ""));
                sum_acceptance_fee += Number(inp_acceptance_fee[p].value.replace(" ", "").replace(" ", ""));
                sum_other_deductions += Number(inp_other_deductions[p].value.replace(" ", "").replace(" ", ""));
            }
            //console.log(sum_storage_cost);
            Ext.select("td.x-grid-cell-storage_cost").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_storage_cost.toFixed(2).replace(re, " ") + '</div>';
            Ext.select("td.x-grid-cell-acceptance_fee").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_acceptance_fee.toFixed(2).replace(re, " ") + '</div>';
            Ext.select("td.x-grid-cell-other_deductions").elements[j].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_other_deductions.toFixed(2).replace(re, " ") + '</div>';
        }

        let gridLength = grid.getStore().data.items.length;
        let cccc = Ext.select("table.x-grid-item").last();

      //  console.log(cccc.dom.id);

        if(cccc && cccc.dom.id !== "tableview-1011-record-"+(gridLength+1)){
          let wer = Ext.select("table#tableview-1011-record-"+gridLength).elements[0];
          let werr = '<table id="tableview-1011-record-'+(gridLength+1)+'" role="presentation" data-boundview="tableview-1011" data-recordid="'+(gridLength-1)+'" data-recordindex="'+(gridLength-1)+'" class="x-grid-item" cellpadding="0" cellspacing="0" style=";width:0">';
          werr += wer.innerHTML;
          werr += '</table>';
          let body = Ext.select("div.x-grid-item-container").elements[0];
          Ext.core.DomHelper.append(body, werr);
          wer.hidden = true;

          let recordHrefs = Ext.select("table#tableview-1011-record-"+gridLength+" tr td").elements;
          i=1;
          while(i<recordHrefs.length){
            recordHrefs[i].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">'+recordHrefs[i].innerText+'</div>';
            i++;
          }
      }else{
        Ext.select("table#tableview-1011-record-"+(gridLength+1)).elements[0].innerHTML = Ext.select("table#tableview-1011-record-"+gridLength).elements[0].innerHTML;
      }

        <?php endif; ?>
      }, 1500);
</script>
