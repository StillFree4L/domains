<script async>
function number_update(id,val,name,real,rid,barcode) {
    $.post("/wb/update/update.php?type=7", {val:val, name:name, incomeId:real, supplierArticle:rid, barcode:barcode}, function (res){

    let store_data = store.data.map;
    let input = document.querySelectorAll('input.inputValue');
    let sum = document.querySelectorAll('div.inputSum');
    let sumkol = document.querySelectorAll('div.inputSumKol');
    let kol = document.querySelectorAll('div.inputKol');
    let re = /\B(?=(\d{3})+(?!\d))/g;
    let i = 0;
    while (i < sum.length) {
        if (sum[i].getAttribute('incomeid') ==real && sum[i].getAttribute('barcode') ==barcode && sum[i].getAttribute('supplierarticle') ==rid){
            summdiv = sum[i];
        }
        i++;
    }
    i = 0;
    while (i < sumkol.length) {
        if (sumkol[i].getAttribute('incomeid') ==real && sumkol[i].getAttribute('barcode') ==barcode
        && sumkol[i].getAttribute('supplierarticle') ==rid) {
            summdivkol = sumkol[i];
        }
        i++;
    }
    i = 0;
    while (i < kol.length) {
        if (kol[i].getAttribute('incomeid') ==real && kol[i].getAttribute('barcode') ==barcode && kol[i].getAttribute('supplierarticle') ==rid){
            summkol = Number(kol[i].innerHTML);
        }
        i++;
    }
    i = 0;
    let summinput = 0;
    while (i < input.length) {
        if (input[i].getAttribute('incomeid') ==real && input[i].getAttribute('barcode') ==barcode && input[i].getAttribute('supplierarticle') ==rid){
            summinput += Number(input[i].value);
        }
        if (input[i].getAttribute('incomeid') ==real && input[i].getAttribute('barcode') ==barcode
        && input[i].getAttribute('supplierarticle') ==rid && input[i].getAttribute('id') ==name){
            input[i].setAttribute('value',val);
        }
        i++;
    }
    summdiv.innerHTML = summinput.toFixed(2).replace(re," ");
    summdivkol.innerHTML = (summinput*summkol).toFixed(2).replace(re," ");

    store_data[id].data[name] = val;
    store_data[id].data.ss_one = summinput.toFixed(2).replace(re," ").replace(" ","");
    store_data[id].data.ss_all = (summinput*summkol).toFixed(2).replace(re," ").replace(" ","");

    let inputs = document.querySelectorAll("input.inputValue#"+name);

    sum = 0;
    i = 0;
    while (i<inputs.length){
            sum += Number((inputs[i].value).toString().replace(" ","").replace(" ",""));
            i++;
        }

   store_data["Data-"+(Ext.select("td.x-grid-cell-"+name).elements.length-1)].data[name] = sum.toFixed(2).replace(re," ");

    let sum_inp = document.querySelectorAll("div.inputSum");
    sum = 0;
    i = 0;
    while (i<sum_inp.length){
            sum += Number((sum_inp[i].innerHTML).toString().replace(" ","").replace(" ",""));
            i++;
    }

    store_data["Data-"+(Ext.select("td.x-grid-cell-ss_one").elements.length-1)].data.ss_one = sum.toFixed(2).replace(re," ");

    let sum_kol = document.querySelectorAll("div.inputSumKol");
    sum = 0;
    i = 0;
    while (i<sum_kol.length){
            sum += Number((sum_kol[i].innerHTML).toString().replace(" ","").replace(" ",""));
            i++;
    }
    store_data["Data-"+(Ext.select("td.x-grid-cell-ss_all").elements.length-1)].data.ss_all = sum.toFixed(2).replace(re," ");
    });
}
</script>
