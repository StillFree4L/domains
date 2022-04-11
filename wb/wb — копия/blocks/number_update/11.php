<script async type = "text/javascript">

function update_post_formul(val,name,article=null,barcode=null,image=null){
  if(image === null){
    $.post("/wb/update.php?type=11", {value:String(val), name:String(name), supplierArticle:String(article), barcode:String(barcode)}, function (res){});
  }else{
    $.post("/wb/update.php?type=12", {value:String(val), name:String(name), goods:String(image)}, function (res){});
  }
}

function update_formul_number(id,name,article=null,barcode=null,image=null){
  let map = store.data.map, tmp=0, i=2, re = /\B(?=(\d{3})+(?!\d))/g;

  //зачеркнутая цена
  if(name==='totalPrice' && map[id].data.sale_percent && map[id].data.totalPrice){
    tmp = Number(Number(map[id].data.totalPrice) + ((Number(map[id].data.totalPrice)*Number(map[id].data.sale_percent))/(100-Number(map[id].data.sale_percent))));
    if(map[id].data.strikethrough_price !== tmp && !isNaN(tmp)){
      map[id].data.strikethrough_price = tmp;
      if(document.querySelector('input[id=strikethrough_price][idd='+id+']')){document.querySelector('input[id=strikethrough_price][idd='+id+']').value = Number(tmp).toFixed(0).replace(re," ");}
      update_post_formul(tmp,'strikethrough_price',article,barcode,image);
    }
  }

  //скидка %
  if(('strikethrough_price' == name || 'totalPrice' == name) && map[id].data.strikethrough_price && map[id].data.totalPrice){
    tmp = Number(((map[id].data.strikethrough_price - map[id].data.totalPrice)*100)/map[id].data.strikethrough_price);
    if(map[id].data.sale_percent !== tmp && !isNaN(tmp)){
      map[id].data.sale_percent = tmp;
      if(document.querySelector('input[id=sale_percent][idd='+id+']')){document.querySelector('input[id=sale_percent][idd='+id+']').value = Number(tmp).toFixed(0).replace(re," ");}
      update_post_formul(tmp,'sale_percent',article,barcode,image);
    }
  }

  //розничная цена
  if(('strikethrough_price' == name || 'sale_percent' == name) && map[id].data.strikethrough_price && map[id].data.sale_percent){
    tmp = Number(Number(map[id].data.strikethrough_price) - ((Number(map[id].data.strikethrough_price) * Number(map[id].data.sale_percent))/100));
    if(map[id].data.totalPrice !== tmp && !isNaN(tmp)){
      map[id].data.totalPrice = tmp;
      if(document.querySelector('input[id=totalPrice][idd='+id+']')){document.querySelector('input[id=totalPrice][idd='+id+']').value = Number(tmp).toFixed(0).replace(re," ");}
      update_post_formul(tmp,'totalPrice',article,barcode,image);
    }
  }
}

function update_formul(id,article=null,barcode=null,image=null){
  let map = store.data.map, tmp=0, i=2, re = /\B(?=(\d{3})+(?!\d))/g, pay = '<?=$pay?>', percent = <?=$perc?>;

  if(!map[id].data.defect){map[id].data.defect=0;}
  if(!map[id].data.stoimost){map[id].data.stoimost=0;}
  if(!map[id].data.zatrat){map[id].data.zatrat=0;}
  if(!map[id].data.cost_delivery){map[id].data.cost_delivery=0;}
  if(!map[id].data.cost_amout){map[id].data.cost_amout=0;}
  if(!map[id].data.cost_defect){map[id].data.cost_defect=0;}
  if(!map[id].data.cost_wb_commission){map[id].data.cost_wb_commission=0;}
  if(!map[id].data.cost_log){map[id].data.cost_log=0;}
  if(!map[id].data.totalPrice){map[id].data.totalPrice=0;}
  if(!map[id].data.ransom){map[id].data.ransom=0;}
  if(!map[id].data.wb_commission){map[id].data.wb_commission=0;}
  if(!map[id].data.nalog7){map[id].data.nalog7=0;}
  if(!map[id].data.pribil){map[id].data.pribil=0;}

  //скидка руб
  if(map[id].data.sale_percent && map[id].data.strikethrough_price){
    tmp = Number((Number(map[id].data.strikethrough_price) * Number(map[id].data.sale_percent))/100);
    if(map[id].data.sale_total !== tmp && !isNaN(tmp)){
      map[id].data.sale_total = tmp;
      if(document.querySelector('a[id=sale_total][idd='+id+']')){document.querySelector('a[id=sale_total][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
      update_post_formul(tmp,'sale_total',article,barcode,image);
    }
  }

  //стоимость логистики
  tmp = Number(Number(map[id].data.cost_delivery) + Number((1 - (Number(map[id].data.ransom)/100)) * Number(map[id].data.cost_amout)));
  if(map[id].data.cost_log !== tmp && !isNaN(tmp)){
    map[id].data.cost_log = tmp;
    if(document.querySelector('a[id=cost_log][idd='+id+']')){document.querySelector('a[id=cost_log][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
    update_post_formul(tmp,'cost_log',article,barcode,image);
  }

  //затраты на брак
  tmp = Number(Number(Number(map[id].data.stoimost) + Number(map[id].data.zatrat) + Number(map[id].data.cost_delivery) + Number(map[id].data.cost_amout)) * Number(map[id].data.defect)/100);
  if(map[id].data.cost_defect !== tmp && !isNaN(tmp)){
    map[id].data.cost_defect = tmp;
    if(document.querySelector('a[id=cost_defect][idd='+id+']')){document.querySelector('a[id=cost_defect][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
    update_post_formul(tmp,'cost_defect',article,barcode,image);
  }

  //Комиссия WB, руб.
if(map[id].data.totalPrice && map[id].data.wb_commission){
  tmp = Number(Number(map[id].data.totalPrice) * Number(map[id].data.wb_commission)/100);
  if(map[id].data.cost_wb_commission !== tmp && !isNaN(tmp)){
    map[id].data.cost_wb_commission = tmp;
    if(document.querySelector('a[id=cost_wb_commission][idd='+id+']')){document.querySelector('a[id=cost_wb_commission][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
    update_post_formul(tmp,'cost_wb_commission',article,barcode,image);
  }
}

//все затраты
tmp = Number(Number(map[id].data.stoimost)
          + Number(map[id].data.zatrat)
          + Number(map[id].data.cost_wb_commission)
          + Number(map[id].data.cost_log)
          + Number(map[id].data.cost_defect)
          + Number(map[id].data.nalog7));

if(map[id].data.all_costs !== tmp && !isNaN(tmp)){
  map[id].data.all_costs = tmp;
  if(document.querySelector('a[id=all_costs][idd='+id+']')){document.querySelector('a[id=all_costs][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
  update_post_formul(tmp,'all_costs',article,barcode,image);
}

  //налоги
if(map[id].data.totalPrice){
  if(pay === 'on'){
  tmp = Number(Number(Number(map[id].data.totalPrice) - (Number(map[id].data.stoimost) + Number(map[id].data.zatrat)
            + Number(map[id].data.cost_wb_commission)
            + Number(map[id].data.cost_log)
            + Number(map[id].data.cost_defect))) * (percent/100));
  }else{
    tmp = Number(Number(map[id].data.totalPrice) * (percent/100));
  }

  if(map[id].data.nalog7 !== tmp && !isNaN(tmp)){
    map[id].data.nalog7 = tmp;
    if(document.querySelector('a[id=nalog7][idd='+id+']')){document.querySelector('a[id=nalog7][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
    update_post_formul(tmp,'nalog7',article,barcode,image);
  }
}

  //чистая прибыль
  if(Number(map[id].data.ransom) === 0 || Number(map[id].data.ransom) === "" || Number(map[id].data.ransom) === null){
    tmp = Number(Number(map[id].data.totalPrice) * (Number(map[id].data.ransom)/100))
            - Number(map[id].data.stoimost)
            - Number(map[id].data.zatrat)
            - Number(Number(map[id].data.totalPrice) * (Number(map[id].data.ransom)/100) * (Number(map[id].data.wb_commission)/100))
            - Number(map[id].data.cost_log)
            - Number(map[id].data.cost_defect)
            - Number(Number(Number(map[id].data.totalPrice) * (Number(map[id].data.ransom)/100) * (Number(map[id].data.nalog7)/100)));
  }else{
    /*  tmp = Number((Number(columns['totalPrice'].value) * (Number(columns['ransom'].value)/100))
                    - Number(columns['stoimost'].value)
                    - Number(columns['zatrat'].value)
                    - Number(columns['cost_wb_commission'].innerText)
                    - Number(columns['cost_log'].innerText)
                    - Number(columns['cost_defect'].innerText)
                    - Number(columns['nalog7'].innerText)).toFixed(0);*/
    tmp = Number(Number(map[id].data.totalPrice)
              - Number(map[id].data.stoimost)
              - Number(map[id].data.zatrat)
              - Number(map[id].data.cost_wb_commission)
              - Number(map[id].data.cost_log)
              - Number(map[id].data.cost_defect)
              - Number(map[id].data.nalog7));
    }
    if(map[id].data.pribil !== tmp && !isNaN(tmp)){
      map[id].data.pribil = tmp;
      if(document.querySelector('a[id=pribil][idd='+id+']')){document.querySelector('a[id=pribil][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
      update_post_formul(tmp,'pribil',article,barcode,image);
    }

  //маржинальность
  tmp = Number(Number(Number(map[id].data.pribil)/(Number(map[id].data.stoimost) + Number(map[id].data.zatrat)
        + Number(map[id].data.cost_wb_commission) + Number(map[id].data.cost_log) + Number(map[id].data.cost_defect) + Number(map[id].data.nalog7))) * 100);

  if(map[id].data.marga !== tmp && !isNaN(tmp)){
    map[id].data.marga = tmp;
    if(document.querySelector('a[id=marga][idd='+id+']')){document.querySelector('a[id=marga][idd='+id+']').innerText = Number(tmp).toFixed(0).replace(re," ");}
    update_post_formul(tmp,'marga',article,barcode,image);
  }
}

function number_update(id,val,name,article,barcode) {
  $.post("/wb/update.php?type=11", {value:val, name:name, supplierArticle:article, barcode:barcode}, function (res){
    store.data.map[id].data[name] = Number(val).toFixed(0);
    update_formul_number(id,name,article,barcode);
  });
}

setInterval(() => {
  let map = store.data.map;
  for (var ma in map){
    if(map[ma].data.checkbox_del && !isNaN(map[ma].data.checkbox_del)){
      update_formul(ma,null,null,map[ma].data.checkbox_del);
    }else{
      update_formul(ma,map[ma].data.supplierArticle,map[ma].data.barcode);
    }
  }
}, 2000);

</script>
