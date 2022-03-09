function number_update(id,val,name,real) {
        $.post("/wb/update/update_five.php", {val:val, name:name, real:real}, function (res){
            var re = /\B(?=(\d{3})+(?!\d))/g;

            


            store.data.map[id].data[name] = val;
            val = Number(val);

            if (name == "storage_cost"){
                var storage_cost = val;
            }else{
                var storage_cost = store.data.map[id].data.storage_cost;
                if (storage_cost != null){
                    storage_cost = Number(storage_cost.replace(" ",""));
                }else{
                    storage_cost = 0;
                }
            }

            if (name == "acceptance_fee"){
                var acceptance_fee = val;
            }else{
                var acceptance_fee = store.data.map[id].data.acceptance_fee;
                if (acceptance_fee != null){
                    acceptance_fee = Number(acceptance_fee.replace(" ",""));
                }else{
                    acceptance_fee = 0;
                }
            }

            if (name == "other_deductions"){
                var other_deductions = val;
            }else{
                var other_deductions = store.data.map[id].data.other_deductions;
                if (other_deductions != null){
                    other_deductions = Number(other_deductions.replace(" ",""));
                }else{
                    other_deductions = 0;
                }
            }

            var ppvz_for_pay = store.data.map[id].data.ppvz_for_pay;
            if (ppvz_for_pay != null){
                    ppvz_for_pay = Number(ppvz_for_pay.replace(" ",""));
                }else{
                    ppvz_for_pay = 0;
                }
            var delivery_rub = store.data.map[id].data.delivery_rub;
            if (delivery_rub != null){
                    delivery_rub = Number(delivery_rub.replace(" ",""));
                }else{
                    delivery_rub = 0;
                }
            var total_payable = ppvz_for_pay - (delivery_rub + acceptance_fee + other_deductions + storage_cost);



            Ext.select("td.x-grid-cell-total_payable").item(id.replace("Data-","")-1).update("<div unselectable=\"on\" class=\"x-grid-cell-inner \" style=\"text-align:left;\"><a href=\"?page=wb&amp;type='.$_GET["type"].'&amp;rid="+(Ext.select("td.x-grid-cell-realizationreport_id").item(id.replace("Data-","")-1).dom.innerText)+"\">"+(total_payable.toFixed(2).replace(re," "))+"</a></div>");
            store.data.map[id].data.total_payable = total_payable.toFixed(2).replace(re," ");
        });
    }