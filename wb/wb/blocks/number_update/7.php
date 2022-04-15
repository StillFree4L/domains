<script async type = "text/javascript">
function number_update(id,val,name,real,rid,barcode){
  $.post("/wb/update.php?type=7", {value:val, name:name, incomeId:real, supplierArticle:rid, barcode:barcode}, function (res){
    let map = store.data.map,ss_dop=<?=json_encode($ss_dom_lat)?>,sum=0,i=2;
    var re = /\B(?=(\d{3})+(?!\d))/g;

    map[id].data[name]=Number(val);

    while(i<ss_dop.length){
        if(map[id].data[ss_dop[i]]){
          sum += Number(map[id].data[ss_dop[i]]);
        }
        i++;
    }

    if(Number(sum) !== map[id].data.Obschaya_sebestoimosty_edinicy_tovara){
      map[id].data.Obschaya_sebestoimosty_edinicy_tovara = Number(sum);
      if(document.querySelector('a[id=Obschaya_sebestoimosty_edinicy_tovara][idd='+id+']')){
        document.querySelector('a[id=Obschaya_sebestoimosty_edinicy_tovara][idd='+id+']').innerText = Number(sum).toFixed(2).replace(re, " ");
      }
    }
    if(Number(Number(sum)*Number(map[id].data.quantity)) !== map[id].data.Obschaya_sebestoimosty_s_uchetom_kolichestva){
      map[id].data.Obschaya_sebestoimosty_s_uchetom_kolichestva = Number(Number(sum)*Number(map[id].data.quantity));
      if(document.querySelector('a[id=Obschaya_sebestoimosty_s_uchetom_kolichestva][idd='+id+']')){
        document.querySelector('a[id=Obschaya_sebestoimosty_s_uchetom_kolichestva][idd='+id+']').innerText = Number(Number(sum)*Number(map[id].data.quantity)).toFixed(2).replace(re, " ");
      }
    }

    let j=0,jSum=0,jSumKol=0;

    for (var ma in map){
      j += Number(map[ma].data[name]);
      jSum += Number(map[ma].data.Obschaya_sebestoimosty_edinicy_tovara);
      jSumKol += Number(map[ma].data.Obschaya_sebestoimosty_s_uchetom_kolichestva);
    }

    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div').innerText !== Number(j).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-'+name+' div').innerText = Number(j).toFixed(2).replace(re," ");
    }
    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-Obschaya_sebestoimosty_edinicy_tovara div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-Obschaya_sebestoimosty_edinicy_tovara div').innerText !== Number(jSum).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-Obschaya_sebestoimosty_edinicy_tovara div').innerText = Number(jSum).toFixed(2).replace(re," ");
    }
    if(document.querySelector('tr.x-grid-row-summary td.x-grid-cell-Obschaya_sebestoimosty_s_uchetom_kolichestva div')
    && document.querySelector('tr.x-grid-row-summary td.x-grid-cell-Obschaya_sebestoimosty_s_uchetom_kolichestva div').innerText !== Number(jSumKol).toFixed(2).replace(re," ")){
      document.querySelector('tr.x-grid-row-summary td.x-grid-cell-Obschaya_sebestoimosty_s_uchetom_kolichestva div').innerText = Number(jSumKol).toFixed(2).replace(re," ");
    }

});
}

</script>
