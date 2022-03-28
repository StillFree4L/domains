<script async type = "text/javascript">

  setInterval(() => {
    let re = /\B(?=(\d{3})+(?!\d))/g;
    let sums = <?=json_encode($sums_report)?>;
    let i =0;
    let rest = 0;
    let j = 0;
if(sums){
    while(i<sums.length){
      j = 0;
      let all = document.querySelectorAll('td.x-grid-cell-'+sums[i]+' a');
    if(all){
        while(j<all.length){
          rest = Number(all[j].innerHTML.replace(" ", "").replace(" ", ""))
          if (!isNaN(rest)){
                var c = "" + rest;
                var ce = c.split('.');
                var ress = ce[0].replace(/[^0-9]/g, '');
                //console.log(ress.length)
              }
        if(all[j] && all[j].innerHTML.trim().search(' ') == -1 && ress.length > 3){
          //console.log(all[j].innerHTML);
          all[j].innerHTML = Number(all[j].innerHTML).toFixed(2).replace(re, " ");
        }else if(all[j] && all[j].innerHTML.trim().search(' ') != -1){
        //  console.log(all[j].innerHTML);
          break;
        }
        j++;
      }
    }
    //  console.log(all);
      i++;
    }
  }
      //  console.log(sums.length);
    }, 2000);
</script>
