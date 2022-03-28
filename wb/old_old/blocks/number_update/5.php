<script async type = "text/javascript">

function number_update(id,val,name,real) {
    $.post("/wb/update/update.php?type=5", {val:val, name:name, realizationreport_id:real}, function (res){
        var re = /\B(?=(\d{3})+(?!\d))/g;

        let realizationreport_id = document.querySelectorAll("td.x-grid-cell-realizationreport_id");

        i=0;
        while(i<realizationreport_id.length){
            if (realizationreport_id[i].innerText == real) {
                id1 = "Data-"+(i+1);
            }
            i++;
        }

        store.data.map[id].data[name] = val;
        val = Number(val);
        if (name == "storage_cost"){
            var storage_cost = val;
        }else{
            var storage_cost = store.data.map[id].data.storage_cost;
            if (storage_cost != null){
                storage_cost = Number(storage_cost);
            }else{
                storage_cost = 0;
            }
        }
        if (name == "acceptance_fee"){
            var acceptance_fee = val;
        }else{
            var acceptance_fee = store.data.map[id].data.acceptance_fee;
            if (acceptance_fee != null){
                acceptance_fee = Number(acceptance_fee);
            }else{
                acceptance_fee = 0;
            }
        }if (name == "other_deductions"){
            var other_deductions = val;
        }else{
            var other_deductions = store.data.map[id].data.other_deductions;
            if (other_deductions && other_deductions != null){
                other_deductions = Number(other_deductions);
            }else{
                other_deductions = 0;
            }
        }
        var ppvz_for_pay = store.data.map[id].data.ppvz_for_pay;
        if (ppvz_for_pay && ppvz_for_pay != null){
                ppvz_for_pay = Number(ppvz_for_pay);
            }else{
                ppvz_for_pay = 0;
            }
        var delivery_rub = store.data.map[id].data.delivery_rub;
        if (delivery_rub && delivery_rub != null){
                delivery_rub = Number(delivery_rub);
            }else{
                delivery_rub = 0;
            }
        var total_payable = ppvz_for_pay - (delivery_rub + acceptance_fee + other_deductions + storage_cost);

        id=id1;

        Ext.select("td.x-grid-cell-total_payable").item(id.replace("Data-","")-1).update('<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;"><a href="?page=wb&amp;type=<?=$_GET["type"]?>&amp;rid='+(Ext.select("td.x-grid-cell-realizationreport_id").item(id.replace("Data-","")-1).dom.innerText)+'">'+(total_payable.toFixed(2).replace(re," "))+'</a></div>');
        store.data.map[id].data.total_payable = total_payable;
    });
}
</script>
