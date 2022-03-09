<script type = "text/javascript">
setInterval(() => {
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
    //console.log(inp);
                while (j<div_img.length){

                    if (div_img[j].innerText != '' && div_img[j].innerText != 'Итого:'){

                        inp[i][j].hidden = false;
                        if (!isNaN(inp[i][j].value)){
                            sum_in += Number(inp[i][j].value.replace(" ", "").replace(" ", ""))
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
    }, 1500);
</script>