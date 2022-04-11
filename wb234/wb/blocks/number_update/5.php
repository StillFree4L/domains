<script async type = "text/javascript">

function number_update(id,val,name,real){
  $.post("/wb/update.php?type=5", {value:val, name:name, realizationreport_id:real}, function (res){
    let map = store.data.map,ss_dop=<?=json_encode($ss_dom_lat)?>,tmp=0,i=0,sum=0,sumT=0;
    var re = /\B(?=(\d{3})+(?!\d))/g;

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

    map[id].data.total_payable = Number(tmp);

    if(document.querySelector('a[id=total_payable][idd='+id+']')
    && document.querySelector('a[id=total_payable][idd='+id+']').innerText !==  Number(tmp).toFixed(2).replace(re," ")){
      document.querySelector('a[id=total_payable][idd='+id+']').innerText =  Number(tmp).toFixed(2).replace(re," ");
    }

    for (var ma in map){
      sum += Number(map[ma].data[name]);
      sumT += Number(map[ma].data.total_payable);
    }

    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div').innerText !== Number(sum).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div').innerText = Number(sum).toFixed(2).replace(re," ");
    }
    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-total_payable div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-total_payable div').innerText !== Number(sumT).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-total_payable div').innerText = Number(sumT).toFixed(2).replace(re," ");
    }

  });
}

</script>
