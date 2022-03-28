<?php
$calc_divs = ['pribil','marga','sale_total','cost_defect','cost_log','cost_wb_commission'];
 ?>
<input class='btn btn-default' id='btn_pd_val' title='Добавить свободный товар' value='&plus;' style='margin-left:10px;width: 40px;padding: 0px;'
  onclick="addProduct();">
<input class='btn btn-default' id='btn_pd_del_val' title='Удалить свободный товар'  value='&#128465;' style='margin-left:5px;width: 40px;padding: 0px;'
  onclick="delProduct()">
<input class='btn btn-default' id='btn_pd_del_val' title='Очистить строку'  value='&#129529;' style='margin-left:5px;width: 40px;padding: 0px;'
    onclick="">

<script async type = "text/javascript">


function addProduct(){
  var user = Ext.create('Data', {
    checkbox_del:Date.now(),
 });
  store.insert(0,user);
}


function delProduct(){
  let check_del = Ext.select(".check_del").elements;

  if(check_del && check_del[0]){
    let i=0;
    while(i<check_del.length){
      if(check_del[i].checked){
        store.removeAt(store.data.indices[check_del[i].getAttribute('idd')]);
        $.post("/wb/update/update.php?products=del", {checkbox_del:check_del[i].value}, function (res){});
      }
      i++;
    }
  }
}

function number_update_add(id,val,name,idd){
  $.post("/wb/update/update.php?products=add", {val:val, name:name, checkbox_del:id}, function (res){
    store.data.map[idd].data[name] = val;
    update_formul(idd,name,null,null,id);
  });
}
</script>

<?php

        if ($tbl_rows){
          $last_key = -1;

        foreach ($tbl_rows as $g) {
            if (isset($_GET['f1']) && isset($_GET['bc'])) {
                if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

            } else if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            } else {
                if (isset($keys_bc[$g->barcode])) continue;
            }

            $reps[] = $g;

            $last_key = count($reps) - 1;
            $keys_bc[$g->barcode] = $last_key;
            $keys_bc2[$g->barcode] = $last_key;
        }
        $tbl_rows = $reps;
      }

 ?>
