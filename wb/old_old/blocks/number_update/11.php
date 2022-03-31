<script async type = "text/javascript">
function number_update(id,val,name,article,barcode) {
    $.post("/wb/update/update.php?type=11", {val:val, name:name, supplierArticle:article, barcode:barcode}, function (res){

      let all = document.querySelectorAll('[idd='+id+']');
      let re = /\B(?=(\d{3})+(?!\d))/g;
      let pay = '<?=$pay?>';
      let percent = <?=$perc?>;
      let columns = {};
      let i = 0;

      while(i<all.length){
        columns[all[i].id] = all[i];
        i++;
      }

      store.data.map[id].data[name] = Number(val).toFixed(0);
/*----------------------------------------------------------------------------------------------------------------------------*/
      //зачеркнутая цена
      if((name==='totalPrice') && columns['sale_percent'].value!=="" && !isNaN(columns['sale_percent'].value) && !isNaN(columns['totalPrice'].value)){

        columns['strikethrough_price'].value = Number(Number(columns['totalPrice'].value) + ((Number(columns['totalPrice'].value)*Number(columns['sale_percent'].value))/(100-Number(columns['sale_percent'].value)))).toFixed(0);
        store.data.map[id].data.strikethrough_price = columns['strikethrough_price'].value;
        $.post("/wb/update/update.php?type=11", {val:columns['strikethrough_price'].value, name:'strikethrough_price', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //скидка %
      if(('strikethrough_price' == name || 'totalPrice' == name) && (columns['strikethrough_price'].value!=="" && columns['totalPrice'].value!=="")
      && columns['sale_percent'].value==="" && !isNaN(columns['strikethrough_price'].value) && !isNaN(columns['totalPrice'].value)){

        columns['sale_percent'].value = Number(((columns['strikethrough_price'].value - columns['totalPrice'].value)*100)/columns['strikethrough_price'].value).toFixed(0);
        store.data.map[id].data.sale_percent = columns['sale_percent'].value;
        $.post("/wb/update/update.php?type=11", {val:columns['sale_percent'].value, name:'sale_percent', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //скидка руб
      if(('strikethrough_price' == name || 'sale_percent' == name) && !isNaN(columns['strikethrough_price'].value) && !isNaN(columns['sale_percent'].value)){

        columns['sale_total'].innerText = ((Number(columns['strikethrough_price'].value) * Number(columns['sale_percent'].value))/100).toFixed(0);
        store.data.map[id].data.sale_total = columns['sale_total'].innerText;
        $.post("/wb/update/update.php?type=11", {val:columns['sale_total'].innerText, name:'sale_total', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //розничная цена
      if(('strikethrough_price' == name || 'sale_percent' == name) && columns['sale_percent'].value!=="" && !isNaN(columns['sale_percent'].value) && !isNaN(columns['strikethrough_price'].value)){

        columns['totalPrice'].value = Number(Number(columns['strikethrough_price'].value) - ((Number(columns['strikethrough_price'].value) * Number(columns['sale_percent'].value))/100)).toFixed(0);
        store.data.map[id].data.totalPrice = columns['totalPrice'].value;
        $.post("/wb/update/update.php?type=11", {val:columns['totalPrice'].value, name:'totalPrice', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //стоимость логистики
      if(!isNaN(columns['cost_delivery'].value) && !isNaN(columns['cost_amout'].value) && !isNaN(columns['ransom'].value)){
        columns['cost_log'].innerText = (Number(columns['cost_delivery'].value) + Number((1 - (Number(columns['ransom'].value)/100)) * Number(columns['cost_amout'].value))).toFixed(0);
        store.data.map[id].data.cost_log = columns['cost_log'].innerText;
        $.post("/wb/update/update.php?type=11", {val:columns['cost_log'].innerText, name:'cost_log', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //затраты на брак
      if(!isNaN(columns['stoimost'].value) && !isNaN(columns['zatrat'].value) && !isNaN(columns['cost_delivery'].value) && !isNaN(columns['cost_amout'].value) && !isNaN(columns['defect'].value)){
        columns['cost_defect'].innerText = (Number(Number(columns['stoimost'].value) + Number(columns['zatrat'].value) + Number(columns['cost_delivery'].value) + Number(columns['cost_amout'].value)) * Number(columns['defect'].value)/100).toFixed(0);
        store.data.map[id].data.cost_defect = columns['cost_defect'].innerText;
        $.post("/wb/update/update.php?type=11", {val:columns['cost_defect'].innerText, name:'cost_defect', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //Комиссия WB, руб.
      if(!isNaN(columns['totalPrice'].value) && !isNaN(columns['wb_commission'].value)){
        columns['cost_wb_commission'].innerText = (Number(columns['totalPrice'].value) * Number(columns['wb_commission'].value)/100).toFixed(0);
        store.data.map[id].data.cost_wb_commission = columns['cost_wb_commission'].innerText;
        $.post("/wb/update/update.php?type=11", {val:columns['cost_wb_commission'].innerText, name:'cost_wb_commission', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
      //налоги
      if(((pay !== 'on' && !isNaN(columns['totalPrice'].value))
        || (pay === 'on' && !isNaN(columns['totalPrice'].value) && !isNaN(columns['stoimost'].value) && !isNaN(columns['zatrat'].value) && !isNaN(columns['cost_wb_commission'].innerText)
          && !isNaN(columns['cost_log'].innerText) && !isNaN(columns['cost_defect'].innerText)))
        && name !== 'nalog7'
        ){
        if(pay === 'on'){
          columns['nalog7'].value = (Number(Number(columns['totalPrice'].value) - (Number(columns['stoimost'].value) + Number(columns['zatrat'].value)
          + Number(columns['cost_wb_commission'].innerText) + Number(columns['cost_log'].innerText) + Number(columns['cost_defect'].innerText))) * (percent/100)).toFixed(0);
        }else{
          columns['nalog7'].value = (Number(columns['totalPrice'].value) * (percent/100)).toFixed(0);
        }
        store.data.map[id].data.nalog7 = columns['nalog7'].value;
        $.post("/wb/update/update.php?type=11", {val:columns['nalog7'].value, name:'nalog7', supplierArticle:article, barcode:barcode}, function (res){});
      }
/*----------------------------------------------------------------------------------------------------------------------------*/
//чистая прибыль
if(!isNaN(columns['totalPrice'].value) && !isNaN(columns['stoimost'].value) && !isNaN(columns['zatrat'].value) && !isNaN(columns['cost_wb_commission'].innerText)
  && !isNaN(columns['cost_log'].innerText) && !isNaN(columns['cost_defect'].innerText) && !isNaN(columns['nalog7'].value)){

  let pribil = Number((Number(columns['totalPrice'].value) * (Number(columns['ransom'].value)/100)) - (Number(columns['stoimost'].value) + Number(columns['zatrat'].value)
  + Number(columns['cost_wb_commission'].innerText) + Number(columns['cost_log'].innerText) + Number(columns['cost_defect'].innerText) + Number(columns['nalog7'].value))).toFixed(0);

  if(!isNaN(pribil)){
    columns['pribil'].innerText = pribil;
  store.data.map[id].data.pribil = columns['pribil'].innerText;
  $.post("/wb/update/update.php?type=11", {val:columns['pribil'].innerText, name:'pribil', supplierArticle:article, barcode:barcode}, function (res){});
  }
}
/*----------------------------------------------------------------------------------------------------------------------------*/
//маржинальность
if(!isNaN(columns['pribil'].innerText) && !isNaN(columns['stoimost'].value) && !isNaN(columns['zatrat'].value) && !isNaN(columns['cost_wb_commission'].innerText)
  && !isNaN(columns['cost_log'].innerText) && !isNaN(columns['cost_defect'].innerText) && !isNaN(columns['nalog7'].value)){

    let marga = Number(Number(Number(columns['pribil'].innerText)/(Number(columns['stoimost'].value) + Number(columns['zatrat'].value)
    + Number(columns['cost_wb_commission'].innerText) + Number(columns['cost_log'].innerText) + Number(columns['cost_defect'].innerText) + Number(columns['nalog7'].value))) * 100).toFixed(0);

  if(!isNaN(marga)){
    columns['marga'].innerText = marga
  store.data.map[id].data.marga = marga;
  $.post("/wb/update/update.php?type=11", {val:marga, name:'marga', supplierArticle:article, barcode:barcode}, function (res){});
  }
}
/*----------------------------------------------------------------------------------------------------------------------------*/
    //  console.log(all)
    });
}
</script>