<script async type = "text/javascript">

function number_update(id,val,name,real){
  $.post("/wb/update.php?type=5", {value:val, name:name, realizationreport_id:real}, function (res){
    let map = store.data.map,tmp=0,i=0,sum=0,sumT=0,re = /\B(?=(\d{3})+(?!\d))/g,j=0,pay = '<?=$pay?>', percent = <?=$perc?>,sumNalog7=0,sumAllCost=0,sumPribil=0,sumMarga=0;

    map[id].data[name] = Number(val);

    if(!map[id].data.ppvz_for_pay){
      map[id].data.ppvz_for_pay=0;
    }
    if(!map[id].data.acceptance_fee){
      map[id].data.acceptance_fee=0;
    }
    if(!map[id].data.other_deductions){
      map[id].data.other_deductions=0;
    }
    if(!map[id].data.storage_cost){
      map[id].data.storage_cost=0;
    }
    if(!map[id].data.delivery_rub){
      map[id].data.delivery_rub=0;
    }

    tmp = Number(map[id].data.ppvz_for_pay) - (Number(map[id].data.delivery_rub) + Number(map[id].data.acceptance_fee) + Number(map[id].data.other_deductions) + Number(map[id].data.storage_cost));

    if(Number(tmp) !== map[id].data.total_payable){
      map[id].data.total_payable = Number(tmp);
      if(document.querySelector('a[id=total_payable][idd='+id+']')){
        document.querySelector('a[id=total_payable][idd='+id+']').innerText = Number(tmp).toFixed(2).replace(re, " ");
      }
    }

    <?php if($_GET['type']==9): ?>
    if(percent && percent!=""){
      //nalog
      if(pay==='on'){
        tmp = Number((Number(Number(map[id].data.retail_amount) - (
          Number(map[id].data.storage_cost)
          + Number(map[id].data.acceptance_fee)
          + Number(map[id].data.other_deductions)
          + Number(map[id].data.delivery_rub)
          + Number(map[id].data.ppvz_vw)
          + Number(map[id].data.ppvz_vw_nds)
          + Number(map[id].data.ss_one)
        ))) * (percent/100));
      }else{
        tmp = Number(Number(map[id].data.retail_amount) * (percent/100));
      }

      if(Number(tmp) !== map[id].data.nalog7){
        map[id].data.nalog7 = Number(tmp);
        if(document.querySelector('a[id=nalog7][idd='+id+']')){
          document.querySelector('a[id=nalog7][idd='+id+']').innerText = Number(tmp).toFixed(2).replace(re, " ");
        }
      }

      //all_cost
      tmp = Number(
        Number(map[id].data.storage_cost)
        + Number(map[id].data.acceptance_fee)
        + Number(map[id].data.other_deductions)
        + Number(map[id].data.delivery_rub)
        + Number(map[id].data.ppvz_vw)
        + Number(map[id].data.ppvz_vw_nds)
        + Number(map[id].data.ss_one)
        + Number(map[id].data.nalog7)
      );

      if(Number(tmp) !== map[id].data.all_cost){
        map[id].data.all_cost = Number(tmp);//all_cost
        if(document.querySelector('a[id=all_cost][idd='+id+']')){
          document.querySelector('a[id=all_cost][idd='+id+']').innerText = Number(tmp).toFixed(2).replace(re, " ");
        }
      }

      tmp = Number(Number(map[id].data.retail_amount) - Number(map[id].data.all_cost));

      if(Number(tmp) != map[id].data.pribil){
        map[id].data.pribil = Number(tmp);//pribil
          if(document.querySelector('a[id=pribil][idd='+id+']')){
            document.querySelector('a[id=pribil][idd='+id+']').innerText = Number(tmp).toFixed(2).replace(re, " ");
          }
        }

      tmp = Number((Number(map[id].data.pribil)/Number(map[id].data.all_cost))*100);

      if(Number(tmp) !== map[id].data.marga){
        map[id].data.marga = Number(tmp);//marga
        if(document.querySelector('a[id=marga][idd='+id+']')){
          document.querySelector('a[id=marga][idd='+id+']').innerText = Number(tmp).toFixed(2).replace(re, " ");
        }
      }
    }
    <?php endif; ?>

    for (var ma in map){
      sum += Number(map[ma].data[name]);
      sumT += Number(map[ma].data.total_payable);
      <?php if($_GET['type']==9): ?>
      if(percent && percent!=""){
        sumNalog7 += Number(map[ma].data.nalog7);
        sumAllCost += Number(map[ma].data.all_cost);
        sumPribil += Number(map[ma].data.pribil);
        sumMarga += Number(map[ma].data.marga);
      }
      <?php endif; ?>
    }

    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div').innerText !== Number(sum).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div').innerText = Number(sum).toFixed(2).replace(re," ");
    }
    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-total_payable div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-total_payable div').innerText !== Number(sumT).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-total_payable div').innerText = Number(sumT).toFixed(2).replace(re," ");
    }
    <?php if($_GET['type']==9): ?>
    if(percent && percent!=""){
      if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-nalog7 div')
      && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-nalog7 div').innerText !== Number(sumNalog7).toFixed(2).replace(re," ")){
        document.querySelector('tr.x-grid-row-summary td.x-grid-cell-nalog7 div').innerText = Number(sumNalog7).toFixed(2).replace(re," ");
      }
      if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-all_cost div')
      && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-all_cost div').innerText !== Number(sumAllCost).toFixed(2).replace(re," ")){
        document.querySelector('tr.x-grid-row-summary td.x-grid-cell-all_cost div').innerText = Number(sumAllCost).toFixed(2).replace(re," ");
      }
      if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-pribil div')
      && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-pribil div').innerText !== Number(sumPribil).toFixed(2).replace(re," ")){
        document.querySelector('tr.x-grid-row-summary td.x-grid-cell-pribil div').innerText = Number(sumPribil).toFixed(2).replace(re," ");
      }
      if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-marga div')
      && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-marga div').innerText !== Number(sumMarga).toFixed(2).replace(re," ")){
        document.querySelector('tr.x-grid-row-summary td.x-grid-cell-marga div').innerText = Number(sumMarga).toFixed(2).replace(re," ");
      }
    }
    <?php endif; ?>
  });
}

</script>
