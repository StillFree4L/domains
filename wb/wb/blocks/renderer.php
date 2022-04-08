<script type = "text/javascript">

//11
<?php if($_GET['type'] == 11): ?>

let ij = 0;
while(ij < columns.length){
  if((ij > 1 && ij < 7)){
    columns[ij].renderer = function(obj, x, y) {
      if(!obj){obj="";}
      let ids = x.classes[1].split('x-grid-cell-')[1]

      if(!y.data.checkbox_del || y.data.edit==='1'){
        return "<a id="+ids+" idd='"+y.id+"' style='text-decoration: none; pointer-events: none; cursor: default;' href='?page=wb&type=<?=$_GET['type']?>'>"+obj+"</a>";
      }
      let numberId = y.data.checkbox_del;
      return "<input type=\"text\" id="+ids+" idd='"+y.id+"' onblur=\"number_update_add('"+numberId+"',this.value,this.id,this.getAttribute('idd'))\" class='inputValue' value='"+obj+"'>";
    }
  }
  else if(ij == 16){
    columns[ij].renderer = function(obj, x, y) {
      if(!obj){obj="";}
      if(obj != 0){obj=Number(obj).toFixed(0);}
      let ids = x.classes[1].split('x-grid-cell-')[1];

      if(!y.data.checkbox_del){
        return "<input type=\"text\" id="+ids+" idd='"+y.id+"' onblur=\"number_update('"+y.id+"',this.value,this.id,'"+y.data.supplierArticle+"','"+y.data.barcode+"')\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";
      }
      let numberId = y.data.checkbox_del;
      return "<input type=\"text\" id="+ids+" idd='"+y.id+"' onblur=\"number_update_add('"+numberId+"',this.value,this.id,this.getAttribute('idd'))\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";
    }
  }
  else if(ij > 20 || ij === 18 || ij === 17 || ij === 14 || ij === 9){
    columns[ij].renderer = function(obj, x, y) {
      if(!obj){obj="";}
      var re = /\B(?=(\d{3})+(?!\d))/g
      let ids = x.classes[1].split('x-grid-cell-')[1]
      if(!isNaN(obj)){
        return "<a id="+ids+" style='text-decoration: none; pointer-events: none; cursor: default;' idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type']?>'>"+Number(obj).toFixed(0).replace(re," ")+"</a>";
      }
      return "<a id="+ids+" style='text-decoration: none; pointer-events: none; cursor: default;' idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type']?>'>"+obj+"</a>";
    }

  }else if(ij > 1){
    columns[ij].renderer = function(obj, x, y) {
      if(!obj){obj="";}
      if(obj != 0){obj=Number(obj).toFixed(0);}
      let ids = x.classes[1].split('x-grid-cell-')[1];

      if(!y.data.checkbox_del){
        return "<input type=\"text\" id="+ids+" idd='"+y.id+"' onblur=\"number_update('"+y.id+"',this.value,this.id,'"+y.data.supplierArticle+"','"+y.data.barcode+"')\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";
      }

      let numberId = y.data.checkbox_del;
      return "<input type=\"text\" id="+ids+" idd='"+y.id+"' onblur=\"number_update_add('"+numberId+"',this.value,this.id,this.getAttribute('idd'))\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";
    }
  }
  else if(ij === 0){
    columns[ij].renderer = function(obj, x, y) {
    //  console.log(obj);
    //  let val = y.id;
      //if(y.data.checkbox_del){val = y.data.checkbox_del;}
      if(!obj){obj=y.id;}
      if(y.data.checkbox_del || y.data.edit==='1'){
        return '<input type="checkbox" id="checkbox_del" idd="'+y.id+'" class="check_del" value="'+obj+'">';
      }
        return '<input type="checkbox" disabled id="checkbox_del" idd="'+y.id+'" class="check_del" value="'+obj+'">';
    }
  }
  ij++;
}

<?php endif; ?>


//1-6
<?php if(!in_array($_GET['type'],[5,7,8,9,11]) or ($_GET['type']==5 and $_GET['rid']) or ($_GET['type']==9 and $_GET['rid'])): ?>
  let colAll = {};
  let ij=0;
  let sums = <?=json_encode($sums_report)?>;
  while (ij < columns.length) {
    colAll[columns[ij].dataIndex] = ij;
    ij++;
  }

  ij=0;
  while (ij < sums.length) {
    if(colAll[sums[ij].replace("\r", "")]){
      columns[colAll[sums[ij].replace("\r", "")]].renderer = function(obj, x, y) {
        if(!obj){obj=0;}
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
          if(obj < 0 ){obj = Number(Number(obj) * -1);}
            return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&f2="+ids+"&f3="+obj+"'>"+Number(obj).toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&f2="+ids+"&f3="+obj+"'>"+obj+"</a>";
      }
    }
    ij++;
  }
<?php endif; ?>


//7 rid
<?php if($_GET['type']==7 and $_GET['rid']): ?>
let fieldsArr = {};
ij=0;
while(ij<fields.length){
    if(ij == 6 || ij == 8 || ij >15){
        fieldsArr[ij] = {name:fields[ij],type:'int'};
        columns[ij].summaryType = 'sum';
        columns[ij].summaryRenderer = function(value, summaryData, dataIndex) {
            if(!isNaN(value)){
                var re = /\B(?=(\d{3})+(?!\d))/g;
                return Number(value).toFixed(2).replace(re," ");
            }
        }
    }else{
        fieldsArr[ij] = fields[ij];
    }

    if(ij == 17 || ij == 18){
        columns[ij].renderer = function(obj, x, y) {
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&f2="+ids+"&f3="+obj+"'>"+obj.toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&f2="+ids+"&f3="+obj+"'>"+obj+"</a>";
      }
    }else if(ij > 18){
        columns[ij].renderer = function(obj, x, y) {
            //console.log(obj);
      if(!obj){obj="";}
      let ids = x.classes[1].split('x-grid-cell-')[1];

    return "<input type='text' id="+ids+" idd='"+y.id+"' incomeId=<?=$_GET['rid']?> barcode='"+y.data.barcode+"' supplierArticle='"+y.data.supplierArticle+"' onblur=\"number_update('"+y.id+"',this.value,this.id,<?=$_GET['rid']?>,'"+y.data.supplierArticle+"','"+y.data.barcode+"')\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";

      }
    }

    ij++;
}

columns[0].summaryRenderer = function(value, summaryData, dataIndex) {
    return 'Итого:';
}
fields = fieldsArr;
<?php endif; ?>


// 7 !rid
<?php if($_GET['type']==7 and !$_GET['rid']): ?>
let fieldsArr = {};
ij=0;
while(ij<fields.length){
    if(ij == 3 || ij > 11){
        fieldsArr[ij] = {name:fields[ij],type:'int'};
        columns[ij].summaryType = 'sum';
        columns[ij].summaryRenderer = function(value, summaryData, dataIndex) {
          if(!isNaN(value)){
                var re = /\B(?=(\d{3})+(?!\d))/g;
                return Number(value).toFixed(2).replace(re," ");
            }
            return value;
        }
    }else{
        fieldsArr[ij] = fields[ij];
    }
    if(ij > 11){
      columns[ij].renderer = function(obj, x, y) {
        if(!obj){obj=0;}
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1];
        if(!isNaN(obj)){
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type']?>&rid="+y.data.incomeId+"&dt=<?=$_GET['dt']?>'>"+obj.toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type']?>&rid="+y.data.incomeId+"&dt=<?=$_GET['dt']?>'>"+obj+"</a>";
      }
    }
    ij++;
}

columns[0].summaryRenderer = function(value, summaryData, dataIndex) {
    return 'Итого:';
}

fields = fieldsArr;
<?php endif; ?>


//8 f1
<?php if($_GET['type']==8 and $_GET['f1']): ?>
let fieldsArr = {};
ij=0;

while(ij<fields.length){
  if(fields[ij]=='save'){break;}
    if(ij == 4 || ij == 6 || ij >13){
        fieldsArr[ij] = {name:fields[ij],type:'int'};
        columns[ij].summaryType = 'sum';
        columns[ij].summaryRenderer = function(value, summaryData, dataIndex) {
            if(!isNaN(value)){
                var re = /\B(?=(\d{3})+(?!\d))/g;
                return Number(value).toFixed(2).replace(re," ");
            }
        }
    }else{
        fieldsArr[ij] = fields[ij];
    }

    if(ij == 14 || ij == 15){
        columns[ij].renderer = function(obj, x, y) {
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>'>"+obj.toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&f2="+ids+"&f3="+obj+"'>"+obj+"</a>";
      }
    }else if(ij > 15){
        columns[ij].renderer = function(obj, x, y) {
            //console.log(obj);
      if(!obj){obj="";}
      let ids = x.classes[1].split('x-grid-cell-')[1];

    return "<input type='text' id="+ids+" idd='"+y.id+"' incomeId='"+y.data.incomeId+"' barcode='"+y.data.barcode+"' supplierArticle='"+y.data.supplierArticle+"' onblur=\"number_update('"+y.id+"',this.value,this.id,'"+y.data.incomeId+"','"+y.data.supplierArticle+"','"+y.data.barcode+"')\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";

      }
    }

    ij++;
}

columns[0].summaryRenderer = function(value, summaryData, dataIndex) {
    return 'Итого:';
}

fields = fieldsArr;
<?php endif; ?>


//8 !f1
<?php if($_GET['type']==8 and !$_GET['f1']): ?>
let fieldsArr = {};
ij=0;

while(ij<fields.length){
  if(fields[ij]=='save'){break;}
    if(ij == 1 || ij == 4 || ij >7){
        fieldsArr[ij] = {name:fields[ij],type:'int'};
        columns[ij].summaryType = 'sum';
        columns[ij].summaryRenderer = function(value, summaryData, dataIndex) {
            if(!isNaN(value)){
                var re = /\B(?=(\d{3})+(?!\d))/g;
                return Number(value).toFixed(2).replace(re," ");
            }
        }
    }else{
        fieldsArr[ij] = fields[ij];
    }

    if(ij > 7){
        columns[ij].renderer = function(obj, x, y) {
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type'].$f1?>&f1="+y.data.supplierArticle+"&dt=<?=$_GET['dt']?>'>"+obj.toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&f1="+y.data.supplierArticle+"&dt=<?=$_GET['dt']?>'>"+obj+"</a>";
      }
    }
    if(ij == 1){
        columns[ij].renderer = function(obj, x, y) {
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type'].$f1?>&f1="+y.data.supplierArticle+"&dt=<?=$_GET['dt']?>'>"+obj+" шт</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&f1="+y.data.supplierArticle+"&dt=<?=$_GET['dt']?>'>"+obj+"</a>";
      }
    }
    ij++;
}

columns[0].summaryRenderer = function(value, summaryData, dataIndex) {
    return 'Итого:';
}

fields = fieldsArr;
<?php endif; ?>

//5 !rid
<?php if($_GET['type']==5 and !$_GET['rid']): ?>
let fieldsArr = {};
ij=0;

while(ij<fields.length){
    if(ij == 3 || ij > 4){
        fieldsArr[ij] = {name:fields[ij],type:'int'};
        columns[ij].summaryType = 'sum';
        columns[ij].summaryRenderer = function(value, summaryData, dataIndex) {
            if(!isNaN(value)){
                var re = /\B(?=(\d{3})+(?!\d))/g;
                return Number(value).toFixed(2).replace(re," ");
            }
        }
    }else{
        fieldsArr[ij] = fields[ij];
    }

    if(ij == 3 || ij == 5 || ij == 6 || ij == 12 || (ij > 11 && ij < 20) || ij == 22){
        columns[ij].renderer = function(obj, x, y) {
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
        //  console.log(obj);
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type'].$f1?>&rid="+y.data.realizationreport_id+"'>"+Number(obj).toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&rid="+y.data.realizationreport_id+"'>"+obj+"</a>";
      }
    }else if(ij > 7 && ij < 11){
      columns[ij].renderer = function(obj, x, y) {
        if(!obj){obj="";}
        let ids = x.classes[1].split('x-grid-cell-')[1];
        return "<input type='text' id="+ids+" idd='"+y.id+"' realizationreport_id='"+y.data.realizationreport_id+"' onblur=\"number_update('"+y.id+"',this.value,this.id,'"+y.data.realizationreport_id+"')\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";
      }
    }
    ij++;
}

columns[1].summaryRenderer = function(value, summaryData, dataIndex) {
    return 'Итого:';
}

fields = fieldsArr;
<?php endif; ?>

//9 !rid
<?php if($_GET['type']==9 and !$_GET['rid']): ?>
let fieldsArr = {};
ij=0;

while(ij<fields.length){
    if(ij > 2){
        fieldsArr[ij] = {name:fields[ij],type:'int'};
        columns[ij].summaryType = 'sum';
        columns[ij].summaryRenderer = function(value, summaryData, dataIndex) {

            if(!isNaN(value)){
                var re = /\B(?=(\d{3})+(?!\d))/g;
                return Number(value).toFixed(2).replace(re," ");
            }
            return value;
        }
    }else{
        fieldsArr[ij] = fields[ij];
    }
    if((ij > 2 && ij < 5) || ij > 7){
        columns[ij].renderer = function(obj, x, y) {
          if(!obj){obj=0;}
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let ids = x.classes[1].split('x-grid-cell-')[1]
        if(!isNaN(obj)){
            return "<a id="+ids+" idd='"+y.id+"' href='?page=wb&type=<?=$_GET['type'].$f1?>&rid="+y.data.realizationreport_id+"'>"+Number(obj).toFixed(2).replace(re, " ")+"</a>";
        }
        return "<a href='?page=wb&type=<?=$_GET['type'].$f1?>&dt=<?=$_GET['dt']?>&rid="+y.data.realizationreport_id+"'>"+obj+"</a>";
      }
    }else if(ij > 4 && ij < 8){
      columns[ij].renderer = function(obj, x, y) {
        if(!obj){obj="";}
        let ids = x.classes[1].split('x-grid-cell-')[1];
        return "<input type='text' id="+ids+" idd='"+y.id+"' realizationreport_id='"+y.data.realizationreport_id+"' onblur=\"number_update('"+y.id+"',this.value,this.id,'"+y.data.realizationreport_id+"')\" class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='"+obj+"'>";
      }
    }
    ij++;
}

columns[0].summaryRenderer = function(value, summaryData, dataIndex) {
    return 'Итого:';
}

fields = fieldsArr;
<?php endif; ?>

  //console.log(columns);

</script>
