<script async type = "text/javascript">
function number_update(id,val,name,article,barcode) {
    $.post("/wb/update/update.php?type=11", {val:val, name:name, supplierArticle:article, barcode:barcode}, function (res){

      let all = document.querySelectorAll('[idd='+id+']');
      let re = /\B(?=(\d{3})+(?!\d))/g;
      let pay = '<?=$pay?>';
      let percent = <?=$perc?>;

      let arr = {strikethrough_price:0,sale_percent:1,totalPrice:3,stoimost:4,zatrat:5,wb_commission:6,cost_delivery:7,cost_amout:8,nalog7:10,ransom:11,defect:12};

      store.data.map[id].data[name] = Number(val).toFixed(2);
      $.post("/wb/update/update.php?type=11", {val:all[arr[name]].value, name:all[arr[name]].id, supplierArticle:article, barcode:barcode}, function (res){});

      if((all[3].id == name) && !isNaN(all[1].value) && !isNaN(all[3].value)){
        all[0].value = Number(Number(all[3].value) + ((Number(all[3].value)*Number(all[1].value))/(100-Number(all[1].value)))).toFixed(0);
      //console.log(Number(all[3].value) + ((Number(all[3].value)*Number(all[1].value))/(100-Number(all[1].value))))
        store.data.map[id].data[all[0].id] = all[0].value;
        $.post("/wb/update/update.php?type=11", {val:all[0].value, name:all[0].id, supplierArticle:article, barcode:barcode}, function (res){});
      }
    /*  if((all[0].id == name) && !isNaN(all[0].value) && !isNaN(all[3].value)){
        all[1].value = Number(((all[0].value - all[3].value)*100)/all[0].value).toFixed(2);
        store.data.map[id].data[all[1].id] = all[1].value;
        $.post("/wb/update/update.php?type=11", {val:all[1].value, name:all[1].id, supplierArticle:article, barcode:barcode}, function (res){});
      }*/
      if((all[0].id == name || all[1].id == name) && !isNaN(all[0].value) && !isNaN(all[1].value)){
        all[2].innerText = ((Number(all[0].value) * Number(all[1].value))/100).toFixed(0);
        store.data.map[id].data.sale_total = all[2].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[2].innerText, name:'sale_total', supplierArticle:article, barcode:barcode}, function (res){});
      }
      if((all[0].id == name || all[1].id == name) && !isNaN(all[1].value) && !isNaN(all[0].value)){
        all[3].value = Number(Number(all[0].value) - ((Number(all[0].value) * Number(all[1].value))/100)).toFixed(0);
        store.data.map[id].data[all[3].id] = all[3].value;
        $.post("/wb/update/update.php?type=11", {val:all[3].value, name:all[3].id, supplierArticle:article, barcode:barcode}, function (res){});
      }

      if(!isNaN(all[7].value) && !isNaN(all[8].value) && !isNaN(all[11].value)){
        all[9].innerText = (Number(all[7].value) + Number((1 - (Number(all[11].value)/100)) * Number(all[8].value))).toFixed(0);
        store.data.map[id].data.cost_log = all[9].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[9].innerText, name:'cost_log', supplierArticle:article, barcode:barcode}, function (res){});
      }

      if(!isNaN(all[4].value) && !isNaN(all[5].value) && !isNaN(all[7].value) && !isNaN(all[8].value) && !isNaN(all[12].value)){
        all[13].innerText = (Number(Number(all[4].value) + Number(all[5].value) + Number(all[7].value) + Number(all[8].value)) * Number(all[12].value)/100).toFixed(0);
        store.data.map[id].data.cost_log = all[13].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[13].innerText, name:'cost_defect', supplierArticle:article, barcode:barcode}, function (res){});
      }

      if((pay != 'on' && !isNaN(all[3].value)) || (pay === 'on' && !isNaN(all[4].value) && !isNaN(all[5].value) && !isNaN(all[6].value) && !isNaN(all[7].value) && !isNaN(all[8].value)))
      {
        if(pay === 'on'){
          all[10].value = (Number(Number(all[3].value) - (Number(all[4].value) + Number(all[5].value) + Number(all[6].value) + Number(all[7].value) + Number(all[8].value))) * (percent/100)).toFixed(0);
        }else{
          all[10].value = (Number(all[3].value) * (percent/100)).toFixed(0);
        }

        store.data.map[id].data.nalog7 = all[10].value;
        $.post("/wb/update/update.php?type=11", {val:all[10].value, name:'nalog7', supplierArticle:article, barcode:barcode}, function (res){});
      }

      if(!isNaN(all[3].value) && !isNaN(all[4].value) && !isNaN(all[5].value) && !isNaN(all[6].value) && !isNaN(all[7].value) && !isNaN(all[8].value) && !isNaN(all[10].innerText)){
        all[14].innerText = (Number(all[3].value) - (Number(all[4].value) + Number(all[5].value) + Number(all[6].value) + Number(all[7].value) + Number(all[8].value) + Number(all[10].innerText))).toFixed(0);
        store.data.map[id].data.pribil = all[14].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[14].innerText, name:'pribil', supplierArticle:article, barcode:barcode}, function (res){});
      }

      if(!isNaN(all[14].innerText) && !isNaN(all[4].value) && !isNaN(all[5].value) && !isNaN(all[6].value) && !isNaN(all[7].value) && !isNaN(all[8].value) && !isNaN(all[10].innerText)){
        all[15].innerText = (Number(Number(all[14].innerText)/(Number(all[4].value) + Number(all[5].value) + Number(all[6].value) + Number(all[7].value) + Number(all[8].value) + Number(all[10].innerText))) * 100).toFixed(0);
        store.data.map[id].data.marga = all[15].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[15].innerText, name:'marga', supplierArticle:article, barcode:barcode}, function (res){});
      }

    //  console.log(all)
/*
      let all_cost = (Number(all[3].value) + Number(all[4].value) + Number(all[5].value) + Number(all[6].value) + Number(all[1].value)).toFixed(2);
      if(all[2].value && all[7].id != name){
        if(pay === 'on'){
          all[7].value = Number((Number(all[2].value) - Number(all_cost))*(<?=$perc?>/100)).toFixed(2);
        }else{
          all[7].value = Number(all[2].value*(<?=$perc?>/100)).toFixed(2);
        }
        store.data.map[id].data[all[7].id] = all[7].value;
        $.post("/wb/update/update.php?type=11", {val:all[7].value, name:all[7].id, supplierArticle:article, barcode:barcode}, function (res){});
      }
      if(all[2].value){
        all[10].innerText = (Number(all[2].value) - Number(all_cost) - Number(all[7].value)).toFixed(2);
        store.data.map[id].data.pribil = all[10].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[10].innerText, name:'pribil', supplierArticle:article, barcode:barcode}, function (res){});
      }
      if(all[10].innerText){
          //console.log(Number(all_cost) + Number(all[7].value));
        all[11].innerText = Number((Number(all[10].innerText) / (Number(all_cost) + Number(all[7].value))) * 100).toFixed(2);
        store.data.map[id].data.marga = all[11].innerText;
        $.post("/wb/update/update.php?type=11", {val:all[11].innerText, name:'marga', supplierArticle:article, barcode:barcode}, function (res){});
      }*/

    });
}
</script>
