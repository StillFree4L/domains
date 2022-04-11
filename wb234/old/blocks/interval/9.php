<script async type = "text/javascript">

  setInterval(() => {
    let re = /\B(?=(\d{3})+(?!\d))/g, map = store.data.map, tmp=0, i=0, j=0,pay = '<?=$pay?>', percent = <?=$perc?>,
        sumNalog7=0,sumAllCost=0,sumPribil=0,sumMarga=0;

    if(percent && percent!=""){
      for (var ma in map){
        if(pay==='on'){
          tmp = Number((Number(Number(map[ma].data.retail_amount) - (
            Number(map[ma].data.storage_cost)
            + Number(map[ma].data.acceptance_fee)
            + Number(map[ma].data.other_deductions)
            + Number(map[ma].data.delivery_rub)
            + Number(map[ma].data.ppvz_vw)
            + Number(map[ma].data.ppvz_vw_nds)
            + Number(map[ma].data.ss_one)
          ))) * (percent/100));
        }
        else{
          tmp = Number(Number(map[ma].data.retail_amount) * (percent/100));
        }
        if(tmp !== map[ma].data.nalog7){
          map[ma].data.nalog7 = tmp;//nalog
          if(document.querySelector('a[id=nalog7][idd='+ma+']')){
            document.querySelector('a[id=nalog7][idd='+ma+']').innerText = tmp.toFixed(2).replace(re, " ");
          }
        }
        sumNalog7 += tmp;

        tmp = Number(
          Number(map[ma].data.storage_cost)
          + Number(map[ma].data.acceptance_fee)
          + Number(map[ma].data.other_deductions)
          + Number(map[ma].data.delivery_rub)
          + Number(map[ma].data.ppvz_vw)
          + Number(map[ma].data.ppvz_vw_nds)
          + Number(map[ma].data.ss_one)
          + Number(map[ma].data.nalog7)
        );

        if(tmp !== map[ma].data.all_cost){
          map[ma].data.all_cost = tmp;//all_cost
          if(document.querySelector('a[id=all_cost][idd='+ma+']')){
            document.querySelector('a[id=all_cost][idd='+ma+']').innerText = tmp.toFixed(2).replace(re, " ");
          }
        }

        sumAllCost += tmp;

        tmp = Number(Number(map[ma].data.retail_amount) - Number(map[ma].data.all_cost));

        if(tmp != map[ma].data.pribil){
          map[ma].data.pribil = tmp;//pribil
            if(document.querySelector('a[id=pribil][idd='+ma+']')){
              document.querySelector('a[id=pribil][idd='+ma+']').innerText = tmp.toFixed(2).replace(re, " ");
            }
          }

        sumPribil += tmp;

        tmp = Number((Number(map[ma].data.pribil)/Number(map[ma].data.all_cost))*100);

        if(tmp !== map[ma].data.marga){
          map[ma].data.marga = tmp;//marga
          if(document.querySelector('a[id=marga][idd='+ma+']')){
            document.querySelector('a[id=marga][idd='+ma+']').innerText = tmp.toFixed(2).replace(re, " ");
          }
        }

        sumMarga += tmp;

      }
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

  }, 1500);

</script>
