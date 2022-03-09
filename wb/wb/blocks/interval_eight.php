<script type = "text/javascript">
setInterval(() => {
    let img = document.querySelectorAll('td.x-grid-cell-supplierArticle img');
    let div_img = document.querySelectorAll('td.x-grid-cell-supplierArticle');
    let i=0;

    <?php if ($_GET['f1']): ?>
        let j=0;
        let re = /\B(?=(\d{3})+(?!\d))/g;
        let inputs = <?=json_encode($sum_s_r);?>;
        let inp = [];
        let sum_inp = [];
        let sum_in = 0;
        let o = 0;
        while (i<inputs.length){
                inp.push(document.querySelectorAll('input.inputValue#'+inputs[i]));
                sum_in = 0;
                j=0;
                while (j<inp[i].length){
                    if (div_img[j].innerText != '' && div_img[j].innerText != 'Итого:'){
                        img[j].hidden = false;
                        inp[i][j].hidden = false;
                        if (!isNaN(inp[i][j].value)){
                            sum_in += Number(inp[i][j].value.replace(" ", "").replace(" ", ""))
                            }
                    }else{
                        p=j;
                    }
                    j++;
                }
                if(inp[i].length>p){o=p;sum_in += Number(inp[i][o].value.replace(" ", "").replace(" ", ""));}
                document.querySelectorAll('td.x-grid-cell-'+inputs[i])[p].innerText = sum_in;
            i++;
        }
    <?php else: ?>
        while (i<div_img.length){
            if (div_img[i].innerText != '' && div_img[i].innerText != 'Итого:'){img[i].hidden = false;}
            i++;
        }
    <?php endif; ?>
    }, 1500);
</script>