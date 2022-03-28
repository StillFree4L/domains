<script async type = "text/javascript">
function number_update(id,val,name,real,rid,barcode) {
    $.post("/wb/update/update.php?type=7", {val:val, name:name, incomeId:real, supplierArticle:rid, barcode:barcode}, function (res){
        var input = document.querySelectorAll('input.inputValue');
    var sum = document.querySelectorAll('div.inputSum');
    var sumkol = document.querySelectorAll('div.inputSumKol');
    var kol = document.querySelectorAll('div.inputKol');
    var re = /\B(?=(\d{3})+(?!\d))/g;
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
    store.data.map[id].data[name] = val;
    store.data.map[id].data.Obschaya_sebestoimosty_edinicy_tovara = summinput.toFixed(2).replace(re," ");
    store.data.map[id].data.Obschaya_sebestoimosty_s_uchetom_kolichestva = (summinput*summkol).toFixed(2).replace(re," ");

    });
}
</script>
