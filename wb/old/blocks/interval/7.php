<script async type = "text/javascript">

setInterval(() => {
  <?php if ($_GET['rid']): ?>
        let i=0;
        let j=0;
        let re = /\B(?=(\d{3})+(?!\d))/g;
        let inputs = <?=json_encode($sum_s_r);?>;
        let inp = [];
        let sum_inp = [];
        let div_img = document.querySelectorAll('td.x-grid-cell-incomeId');
        let sum_in = 0;
        let p = -1;
        let o = 0;

        while (i<inputs.length){

                inp.push(document.querySelectorAll('input.inputValue#'+inputs[i]));

                sum_in = 0;
                j=0;
                while (j<div_img.length){

                    if (div_img[j].innerText !== '' && div_img[j].innerText !== 'Итого:'){

                        if(inp[i][j]) {
                            inp[i][j].hidden = false;

                            if (!isNaN(inp[i][j].value)) {
                                sum_in += Number(inp[i][j].value.replace(" ", "").replace(" ", ""))
                            }
                        }
                    }else{

                        p=j;
                    }
                    j++;
                }


if(p>0){
                if(inp[i].length>p){o=p;sum_in += Number(inp[i][o].value.replace(" ", "").replace(" ", ""));}
    document.querySelectorAll('td.x-grid-cell-'+inputs[i])[p].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">'+sum_in+'</div>';
}
            i++;
        }
        <?php endif; ?>

        let gridLength = grid.getStore().data.items.length;
        let cccc = Ext.select("table.x-grid-item").last();

        if(cccc && cccc.dom.id !== "tableview-1011-record-"+(gridLength+1)){
          let wer = Ext.select("table#tableview-1011-record-"+gridLength).elements[0];
          let werr = '<table id="tableview-1011-record-'+(gridLength+1)+'" role="presentation" data-boundview="tableview-1011" data-recordid="'+(gridLength-1)+'" data-recordindex="'+(gridLength-1)+'" class="x-grid-item" cellpadding="0" cellspacing="0" style=";width:0">';
          werr += wer.innerHTML;
          werr += '</table>';
          let body = Ext.select("div.x-grid-item-container").elements[0];
          Ext.core.DomHelper.append(body, werr);
          wer.hidden = true;

          let recordHrefs = Ext.select("table#tableview-1011-record-"+gridLength+" tr td").elements;
          i=1;
          while(i<recordHrefs.length){
            recordHrefs[i].innerHTML = '<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">'+recordHrefs[i].innerText+'</div>';
            i++;
          }
      }

    }, 1500);

</script>
