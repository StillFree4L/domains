<script async type = "text/javascript">

function update_post_formul(val,name,article=null,barcode=null,image=null){
  if(image === null){
    $.post("/wb/update/update.php?type=11", {val:String(val), name:String(name), supplierArticle:String(article), barcode:String(barcode)}, function (res){});
  }else{
    $.post("/wb/update/update.php?products=add", {val:String(val), name:String(name), checkbox_del:String(image)}, function (res){});
  }
}

function update_formul_number(id,name,article=null,barcode=null,image=null){

        let all = document.querySelectorAll('[idd='+id+']');
        let re = /\B(?=(\d{3})+(?!\d))/g;
        let pay = '<?=$pay?>';
        let percent = <?=$perc?>;
        let columns = {};
        let i = 0;
        let tmp = 0;

        while(i<all.length){
          columns[all[i].id] = all[i];
          i++;
        }

  /*----------------------------------------------------------------------------------------------------------------------------*/
        //зачеркнутая цена
        if((name==='totalPrice') && columns['sale_percent'].value!=="" && !isNaN(columns['sale_percent'].value) && !isNaN(columns['totalPrice'].value)){

          tmp = Number(Number(columns['totalPrice'].value) + ((Number(columns['totalPrice'].value)*Number(columns['sale_percent'].value))/(100-Number(columns['sale_percent'].value)))).toFixed(0);

          if(columns['strikethrough_price'].value !== tmp && !isNaN(tmp) && tmp !== "0"){
            store.data.map[id].data.strikethrough_price = tmp;
            columns['strikethrough_price'].value = tmp;
            update_post_formul(columns['strikethrough_price'].value,'strikethrough_price',article,barcode,image);
          }
        }
  /*----------------------------------------------------------------------------------------------------------------------------*/
        //скидка %
        if(('strikethrough_price' == name || 'totalPrice' == name) && (columns['strikethrough_price'].value!=="" && columns['totalPrice'].value!=="")
        && columns['sale_percent'].value==="" && !isNaN(columns['strikethrough_price'].value) && !isNaN(columns['totalPrice'].value)){

          tmp = Number(((columns['strikethrough_price'].value - columns['totalPrice'].value)*100)/columns['strikethrough_price'].value).toFixed(0);

          if(columns['sale_percent'].value !== tmp && !isNaN(tmp) && tmp !== "0"){
            store.data.map[id].data.sale_percent = tmp;
            columns['sale_percent'].value = tmp;
            update_post_formul(columns['sale_percent'].value,'sale_percent',article,barcode,image);
          }
        }
  /*----------------------------------------------------------------------------------------------------------------------------*/
        //розничная цена
        if(('strikethrough_price' == name || 'sale_percent' == name) && columns['sale_percent'].value!=="" && !isNaN(columns['sale_percent'].value) && !isNaN(columns['strikethrough_price'].value)){

          tmp = Number(Number(columns['strikethrough_price'].value) - ((Number(columns['strikethrough_price'].value) * Number(columns['sale_percent'].value))/100)).toFixed(0);

          if(columns['totalPrice'].value !== tmp && !isNaN(tmp) && tmp !== "0"){
            store.data.map[id].data.totalPrice = tmp;
            columns['totalPrice'].value = tmp;
            update_post_formul(columns['totalPrice'].value,'totalPrice',article,barcode,image);
          }
        }
}

function update_formul(id,article=null,barcode=null,image=null){

        let all = document.querySelectorAll('[idd='+id+']');
        let re = /\B(?=(\d{3})+(?!\d))/g;
        let pay = '<?=$pay?>';
        let percent = <?=$perc?>;
        let columns = {},i = 0,tmp = 0;

        while(i<all.length){
          columns[all[i].id] = all[i];
          i++;
        }

  /*----------------------------------------------------------------------------------------------------------------------------*/
        //скидка руб
        if(!isNaN(columns['strikethrough_price'].value) && !isNaN(columns['sale_percent'].value)){

          tmp = ((Number(columns['strikethrough_price'].value) * Number(columns['sale_percent'].value))/100).toFixed(0);

          if(columns['sale_total'].innerText !== tmp && !isNaN(tmp) && tmp !== "" && tmp !== "0"){
            store.data.map[id].data.sale_total = tmp;
            columns['sale_total'].innerText = tmp;
            update_post_formul(columns['sale_total'].innerText,'sale_total',article,barcode,image);
          }
        }
  /*----------------------------------------------------------------------------------------------------------------------------*/
        //стоимость логистики
        if(!isNaN(columns['cost_delivery'].value) && !isNaN(columns['cost_amout'].value) && !isNaN(columns['ransom'].value)){

          tmp = (Number(columns['cost_delivery'].value) + Number((1 - (Number(columns['ransom'].value)/100)) * Number(columns['cost_amout'].value))).toFixed(0);

          if(columns['cost_log'].innerText !== tmp && !isNaN(tmp) && tmp !== "" && tmp !== "0"){
            store.data.map[id].data.cost_log = tmp;
            columns['cost_log'].innerText = tmp;
            update_post_formul(columns['cost_log'].innerText,'cost_log',article,barcode,image);
          }
        }
  /*----------------------------------------------------------------------------------------------------------------------------*/
        //затраты на брак
        if(!isNaN(columns['stoimost'].value) && !isNaN(columns['zatrat'].value) && !isNaN(columns['cost_delivery'].value) && !isNaN(columns['cost_amout'].value) && !isNaN(columns['defect'].value)){

          tmp = (Number(Number(columns['stoimost'].value) + Number(columns['zatrat'].value) + Number(columns['cost_delivery'].value) + Number(columns['cost_amout'].value)) * Number(columns['defect'].value)/100).toFixed(0);

          if(columns['cost_defect'].innerText !== tmp && !isNaN(tmp) && tmp !== "" && tmp !== "0"){
            store.data.map[id].data.cost_defect = tmp;
            columns['cost_defect'].innerText = tmp;
            update_post_formul(columns['cost_defect'].innerText,'cost_defect',article,barcode,image);
          }
        }
  /*----------------------------------------------------------------------------------------------------------------------------*/
        //Комиссия WB, руб.
        if(!isNaN(columns['totalPrice'].value) && !isNaN(columns['wb_commission'].value)){

          tmp = (Number(columns['totalPrice'].value) * Number(columns['wb_commission'].value)/100).toFixed(0);

          if(columns['cost_wb_commission'].innerText !== tmp && !isNaN(tmp) && tmp !== "" && tmp !== "0"){
            store.data.map[id].data.cost_wb_commission = tmp;
            columns['cost_wb_commission'].innerText = tmp;
            update_post_formul(columns['cost_wb_commission'].innerText,'cost_wb_commission',article,barcode,image);
          }
        }
        /*----------------------------------------------------------------------------------------------------------------------------*/
               //налоги
               if((pay !== 'on' && !isNaN(columns['totalPrice'].value))
                 || (pay === 'on' && !isNaN(columns['totalPrice'].value) && !isNaN(columns['stoimost'].value) && !isNaN(columns['zatrat'].value) && !isNaN(columns['cost_wb_commission'].innerText)
                   && !isNaN(columns['cost_log'].innerText) && !isNaN(columns['cost_defect'].innerText))){
                 if(pay === 'on'){
                   tmp = (Number(Number(columns['totalPrice'].value) - (Number(columns['stoimost'].value) + Number(columns['zatrat'].value)
                            + Number(columns['cost_wb_commission'].innerText)
                            + Number(columns['cost_log'].innerText)
                            + Number(columns['cost_defect'].innerText))) * (percent/100)).toFixed(0);
                 }else{
                   tmp = (Number(columns['totalPrice'].value) * (percent/100)).toFixed(0);
                 }

                 if(columns['nalog7'].innerText !== tmp && !isNaN(tmp) && tmp !== "0"){
                   store.data.map[id].data.nalog7 = tmp;
                   columns['nalog7'].innerText = tmp;
                   update_post_formul(columns['nalog7'].innerText,'nalog7',article,barcode,image);
                 }
               }
  /*----------------------------------------------------------------------------------------------------------------------------*/
  //чистая прибыль
  if(!isNaN(columns['totalPrice'].value)){

    if(Number(columns['ransom'].value) === 0 || Number(columns['ransom'].value) === "" || Number(columns['ransom'].value) === null){
      tmp = (Number(Number(columns['totalPrice'].value) * (Number(columns['ransom'].value)/100))
                    - Number(columns['stoimost'].value)
                    - Number(columns['zatrat'].value)
                    - Number(Number(columns['totalPrice'].value) * (Number(columns['ransom'].value)/100) * (Number(columns['wb_commission'].value)/100))
                    - Number(columns['cost_log'].innerText)
                    - Number(columns['cost_defect'].innerText)
                    - Number(Number(columns['totalPrice'].value) * (Number(columns['ransom'].value)/100) * (Number(columns['nalog7'].innerText)/100))).toFixed(0);
    }else{
    /*  tmp = Number((Number(columns['totalPrice'].value) * (Number(columns['ransom'].value)/100))
                    - Number(columns['stoimost'].value)
                    - Number(columns['zatrat'].value)
                    - Number(columns['cost_wb_commission'].innerText)
                    - Number(columns['cost_log'].innerText)
                    - Number(columns['cost_defect'].innerText)
                    - Number(columns['nalog7'].innerText)).toFixed(0);*/
        tmp = Number((Number(columns['totalPrice'].value))
                    - Number(columns['stoimost'].value)
                    - Number(columns['zatrat'].value)
                    - Number(columns['cost_wb_commission'].innerText)
                    - Number(columns['cost_log'].innerText)
                    - Number(columns['cost_defect'].innerText)
                    - Number(columns['nalog7'].innerText)).toFixed(0);
    }
    if(columns['pribil'].innerText !== tmp && !isNaN(tmp) && tmp !== "" && tmp !== "0"){
      store.data.map[id].data.pribil = tmp;
      columns['pribil'].innerText = tmp;
      update_post_formul(columns['pribil'].innerText,'pribil',article,barcode,image);
    }
  }
  /*----------------------------------------------------------------------------------------------------------------------------*/
  //маржинальность
  if(!isNaN(columns['pribil'].innerText)){

      tmp = Number(Number(Number(columns['pribil'].innerText)/(Number(columns['stoimost'].value) + Number(columns['zatrat'].value)
      + Number(columns['cost_wb_commission'].innerText) + Number(columns['cost_log'].innerText) + Number(columns['cost_defect'].innerText) + Number(columns['nalog7'].innerText))) * 100).toFixed(0);

    if(columns['marga'].innerText !== tmp && !isNaN(tmp) && tmp !== "" && tmp !== "0"){
      store.data.map[id].data.marga = tmp;
      columns['marga'].innerText = tmp;
      update_post_formul(marga,'marga',article,barcode,image);
    }
  }
  /*----------------------------------------------------------------------------------------------------------------------------*/

}

function number_update(id,val,name,article,barcode) {
    $.post("/wb/update/update.php?type=11", {val:val, name:name, supplierArticle:article, barcode:barcode}, function (res){
      store.data.map[id].data[name] = Number(val).toFixed(0);
      update_formul_number(id,name,article,barcode)
    });
}

setInterval(() => {
  let all = '';
  let check_del = Ext.select(".check_del").elements;

  if(check_del && check_del[0]){
    let i=0,j=0;
    while(i<check_del.length){
        if(!check_del[i].value.includes('Data-')){
          update_formul(check_del[i].getAttribute('idd'),null,null,check_del[i].value);
        }else{
          update_formul(check_del[i].getAttribute('idd'),store.data.map[check_del[i].getAttribute('idd')].data.supplierArticle,store.data.map[check_del[i].getAttribute('idd')].data.barcode);
        }
      i++;
    }
  }
}, 2000);
</script>
