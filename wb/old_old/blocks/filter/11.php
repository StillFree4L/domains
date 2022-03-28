<input class='btn btn-default' id='btn_pd_val'  value='Добавить свободный товар' style='margin-left:10px;width: 360px;' onclick="$('#dop_delete_fields_div').hide();$('#dop_fields_div').show();$('#btn_pd_val').addClass('btn-warning');$('#btn_pd_del_val').removeClass('btn-warning');">
<input class='btn btn-default' id='btn_pd_del_val'  value='Удалить свободный товар' style='margin-left:10px;width: 360px;' onclick="$('#dop_fields_div').hide();$('#dop_delete_fields_div').show();$('#btn_pd_del_val').addClass('btn-warning');$('#btn_pd_val').removeClass('btn-warning');">

<br clear=all>
<div id="dop_fields_div" style="float: left; display: none;  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; padding: 10px; margin: 10px; border: 1px solid #ccc; ">
  <img onclick="$('#dop_fields_div').hide();$('#btn_pd_val').removeClass('btn-warning');" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png">
  <b>Заполните поля</b>
<input type=button  onclick='addProduct()' class='btn btn-sm btn-success' value='Сохранить свободный товар'>
<table class="items table table-striped" style="width: 700px;margin-top: 20px;">
  <tr><th>Поле</th><th>Значение</th></tr>

  <tr><td style="width: 250px;">Код WB</td><td style="width: 100px;"><input type="text" id="nm_idID" class="form-control inputValueAdd"></td></tr>
  <tr><td style="width: 250px;">Артикул</td><td style="width: 100px;"><input type="text" id="supplierArticleID" class="form-control inputValueAdd"></td></tr>
  <tr><td style="width: 250px;">Баркод</td><td style="width: 100px;"><input type="text" id="barcodeID" class="form-control inputValueAdd"></td></tr>
  <tr><td style="width: 250px;">Предмет</td><td style="width: 100px;"><input type="text" id="subjectID" class="form-control inputValueAdd"></td></tr>
  <tr><td style="width: 250px;">Категория</td><td style="width: 100px;"><input type="text" id="categoryID" class="form-control inputValueAdd"></td></tr>
  <tr><td style="width: 250px;">Бренд</td><td style="width: 100px;"><input type="text" id="brandID" class="form-control inputValueAdd"></td></tr>

  </table>
</div>
<br clear=all>
<div id="dop_delete_fields_div" style="float: left; display: none;  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; padding: 10px; margin: 10px; border: 1px solid #ccc; ">
  <img onclick="$('#dop_delete_fields_div').hide();$('#btn_pd_del_val').removeClass('btn-warning');" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png">
  <b>Заполните поля. Будут удалены товары с соответствующими артикулом и баркодом</b><br>
<input type=button  onclick='' class='btn btn-sm btn-success' value='Удалить свободный товар'>
<table class="items table table-striped" style="width: 700px;margin-top: 20px;">
  <tr><th>Поле</th><th>Значение</th></tr>

  <tr><td style="width: 250px;">Артикул</td><td style="width: 100px;"><input type="text" id="supplierArticleID" class="form-control inputValueDel"></td></tr>
  <tr><td style="width: 250px;">Баркод</td><td style="width: 100px;"><input type="text" id="barcodeID" class="form-control inputValueDel"></td></tr>

  </table>
</div>
<br clear=all>

<script async type = "text/javascript">
function addProduct(){
  console.log($('.inputValueAdd'));
}
</script>

<?php

        if ($tbl_rows){
          $last_key = -1;

        foreach ($tbl_rows as $g) {
            if (isset($_GET['f1']) && isset($_GET['bc'])) {
                if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

            } else if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            } else {
                if (isset($keys_bc[$g->barcode])) continue;
            }

            $reps[] = $g;

            $last_key = count($reps) - 1;
            $keys_bc[$g->barcode] = $last_key;
            $keys_bc2[$g->barcode] = $last_key;
        }
        $tbl_rows = $reps;
      }

 ?>
