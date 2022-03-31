<script async type = "text/javascript">
/*
  setInterval(() => {
    let re = /\B(?=(\d{3})+(?!\d))/g;
    let sums = <?=json_encode($sums_report)?>;
    let i =0,j = 0;
    const f = x => ( (x.toString().includes('.')) ? (x.toString().split('.').pop().length) : (0) );
if(sums){
    while(i<sums.length){
      j = 0;
      let all = document.querySelectorAll('td.x-grid-cell-'+sums[i]+' a');
    if(all){
        while(j<all.length){
        if(all[j] && all[j].innerHTML.trim().search(' ') == -1 && (f(all[j].innerHTML) > 2 || Number(all[j].innerHTML) > 999)){
          all[j].innerHTML = Number(all[j].innerHTML).toFixed(2).replace(re, " ");
        }else if(all[j] && all[j].innerHTML.trim().search(' ') != -1){
          break;
        }
        j++;
      }
    }
      i++;
    }
  }
    }, 1500);
*/
</script>
