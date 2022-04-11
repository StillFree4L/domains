<?php
$calc_dops = ['strikethrough_price','sale_percent','totalPrice','stoimost','zatrat','wb_commission','cost_delivery','cost_amout','ransom','defect'];
$calc_divs = ['pribil','marga','sale_total','cost_defect','cost_log','cost_wb_commission'];
 ?>
<input class='btn btn-default' id='btn_pd_plus_val' title='Добавить свободный товар' value='+' style='margin-left:10px;width: 40px;padding: 0px;height: 30px;'
  onclick="addProduct();">
<input class='btn btn-default btn_focus_val' id='btn_pd_del_val' title='Удалить свободный товар'  value='' style='margin-left:5px;width: 40px;padding: 0px;height: 30px;'
  onclick="delProduct()">
<input class='btn btn-default btn_focus_val' id='btn_pd_clear_val' title='Очистить строку'  value='' style='margin-left:5px;width: 40px;padding: 0px;height: 30px;'
    onclick="clearProduct()">
<input class='btn btn-default btn_focus_val' id='btn_pd_redact_val' title='Массовое редактирование'  value='' style='margin-left:5px;width: 40px;padding: 0px;height: 30px;'
    onclick="$('#set_fields_div').show();$('#btn_pd_redact_val').addClass('btn-warning');">

<br clear=all><div id="set_fields_div" style="float: left; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; font-weight: normal; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; ">
  <img onclick="$('#set_fields_div').hide();$('#btn_pd_redact_val').removeClass('btn-warning');" style="width: 20px; cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png">
  Установка значения полей для всех товаров
<input type=button  onclick="number_update_all();$('#set_fields_div').hide();$('#btn_pd_redact_val').removeClass('btn-warning');" style="margin-left: 10px;" class='btn btn-success' value='Сохранить'>
<table class="items table table-striped" style="width: 700px;margin-top: 20px;"><tr><th>Поле</th><th>Значение</th></tr>
<?php foreach($calc_dops as $key=>$value): ?>
<tr>
  <td style="width: 250px;"><?=$tbl_keys[$value]?></td>
  <td style="width: 100px;">
    <input type="text" sf="<?=$value?>" class="form-control inputValueAll" onkeyup="this.value = this.value.replace(/[^^0-9\.]/g,'');">
  </td>
</tr>
<?php endforeach; ?>
</table>  </div> <br clear=all>

<script type = "text/javascript">

function number_update_all(){

  let map = store.data.map,i=0,like={};

  for (var ma in map){
    if(!isNaN(map[ma].data.checkbox_del)){
      like[i]={goods: map[ma].data.checkbox_del};
    }else{
      like[i]={supplierArticle: map[ma].data.supplierArticle, barcode: map[ma].data.barcode};
    }

    i++;
  }

  let re = /\B(?=(\d{3})+(?!\d))/g;
  let columns = {};
  let pay = '<?=$pay?>';
  let percent = <?=$perc?>;
  let all = $('.inputValueAll'),sf={};

i=0;

while (i<all.length) {
  if(all[i].value !== "" && all[i].value !== null){
    sf[all[i].getAttribute('sf')] = all[i].value;
  }
  i++;
}

/*
      //зачеркнутая цена
      if(!sf['strikethrough_price'] && sf['totalPrice'] && sf['sale_percent']){
        sf['strikethrough_price'] = Number(Number(sf['totalPrice']) + ((Number(sf['totalPrice'])*Number(sf['sale_percent']))/(100-Number(sf['sale_percent']))));
      }

      //скидка %
      if(sf['strikethrough_price'] && sf['totalPrice'] && !sf['sale_percent']){
        sf['sale_percent'] = Number(((sf['strikethrough_price'] - sf['totalPrice'])*100)/sf['strikethrough_price']);
      }

      //розничная цена
      if(sf['strikethrough_price'] && !sf['totalPrice'] && sf['sale_percent']){
        sf['totalPrice'] = Number(Number(sf['strikethrough_price']) - ((Number(sf['strikethrough_price']) * Number(sf['sale_percent']))/100));
      }

      //скидка руб
      if(!sf['sale_total'] && sf['strikethrough_price'] && sf['sale_percent']){
        sf['sale_total'] = ((Number(sf['strikethrough_price']) * Number(sf['sale_percent']))/100);
      }

      //стоимость логистики
      if(!sf['cost_log'] && sf['cost_delivery'] && sf['cost_amout'] && sf['ransom']){
        sf['cost_log'] = (Number(sf['cost_delivery']) + Number((1 - (Number(sf['ransom'])/100)) * Number(sf['cost_amout'])));
      }

      //затраты на брак
      if(!sf['cost_defect'] && sf['stoimost'] && sf['zatrat'] && sf['cost_delivery'] && sf['cost_amout'] && sf['defect']){
        sf['cost_defect'] = (Number(Number(sf['stoimost']) + Number(sf['zatrat']) + Number(sf['cost_delivery']) + Number(sf['cost_amout'])) * Number(sf['defect'])/100);
      }

      //Комиссия WB, руб.
      if(!sf['cost_wb_commission'] && sf['totalPrice'] && sf['wb_commission']){
        sf['cost_wb_commission'] = (Number(sf['totalPrice']) * Number(sf['wb_commission'])/100);
      }

      //налоги
      if(!sf['nalog7'] && ((pay !== 'on' && sf['totalPrice']) || (pay === 'on' && sf['totalPrice'] && sf['stoimost'] && sf['zatrat'] && sf['cost_wb_commission'] && sf['cost_log'] && sf['cost_defect']))){
          if(pay === 'on'){
            sf['nalog7'] = (Number(Number(sf['totalPrice']) - (Number(sf['stoimost']) + Number(sf['zatrat'])
                                + Number(sf['cost_wb_commission'])
                                + Number(sf['cost_log'])
                                + Number(sf['cost_defect']))) * (percent/100));
          }else{
            sf['nalog7'] = (Number(sf['totalPrice']) * (percent/100));
          }
      }

      //чистая прибыль
      if(!sf['pribil'] && sf['totalPrice']){
        if(Number(sf['ransom']) === 0 || Number(sf['ransom']) === "" || Number(sf['ransom']) === null){
          sf['pribil'] = (Number(Number(sf['totalPrice']) * (Number(sf['ransom'])/100))
                        - Number(sf['stoimost'])
                        - Number(sf['zatrat'])
                        - Number(Number(sf['totalPrice']) * (Number(sf['ransom'])/100) * (Number(sf['wb_commission'])/100))
                        - Number(sf['cost_log'])
                        - Number(sf['cost_defect'])
                        - Number(Number(sf['totalPrice']) * (Number(sf['ransom'])/100) * (Number(sf['nalog7'])/100)));
        }else{
            sf['pribil']= Number((Number(sf['totalPrice']))
                        - Number(sf['stoimost'])
                        - Number(sf['zatrat'])
                        - Number(sf['cost_wb_commission'])
                        - Number(sf['cost_log'])
                        - Number(sf['cost_defect'])
                        - Number(sf['nalog7']));
        }
      }

      //маржинальность
      if(!sf['marga'] && sf['pribil']){
          sf['marga'] = Number(Number(Number(sf['pribil'])/(Number(sf['stoimost'])
          + Number(sf['zatrat'])
          + Number(sf['cost_wb_commission'])
          + Number(sf['cost_log'])
          + Number(sf['cost_defect'])
          + Number(sf['nalog7']))) * 100);
      }
*/

  $.post("/wb/update.php?all=11", {like:like,sf:sf}, function (res){
    setTimeout(function () {document.location.reload();}, 1000);
  });

}

function clearProduct(){
  let all = '';
  let check_del = Ext.select(".check_del").elements;


  if(check_del && check_del[0]){
    let i=0;
    let j=0;
    while(i<check_del.length){
      if(check_del[i].checked){
      if(!isNaN(check_del[i].value)){
        j=1;
        $.post("/wb/update.php?clear=12", {goods:check_del[i].value}, function (res){});
      }else{
        j=6;
        $.post("/wb/update.php?del=11", {supplierArticle:store.data.map[check_del[i].getAttribute('idd')].data.supplierArticle, barcode:store.data.map[check_del[i].getAttribute('idd')].data.barcode}, function (res){});
      }

        all = document.querySelectorAll('[idd='+(check_del[i].getAttribute('idd'))+']');
        while(j < all.length){
          if(all[j].value){
              all[j].value = "";
            }else{
              all[j].innerHTML = "";
            }
            store.data.map[check_del[i].getAttribute('idd')].data[all[j].id] = "";
          j++;
        }
      }
      i++;
    }
  }
}

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
      if(check_del[i].checked && !isNaN(check_del[i].value)){
        store.removeAt(store.data.indices[check_del[i].getAttribute('idd')]);
        $.post("/wb/update.php?del=12", {goods:check_del[i].value}, function (res){});
      }
      i++;
    }
  }
}

function number_update_add(id,val,name,idd){
  if(store.data.map[idd].data[name] != val){
    $.post("/wb/update.php?type=12", {value:val, name:name, goods:id}, function (res){
    store.data.map[idd].data[name] = val;
    update_formul_number(idd,name,null,null,id);
  });
}
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
