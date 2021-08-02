<script language="javascript">

function linkFile_<?=$this->id?>()
{

    this.init = true;
    this.setUrl = function(value)
    {
        $("#<?=$this->id?>").attr("value",value);
    }

}



</script>

<?php

$this->widget("bootstrap.widgets.TbButton", array(
    "label"=>"...",
    "type"=>"info",    
    "htmlOptions"=>array(
        "onclick"=>"js:linkFileBrowser = new linkFile_$this->id(); window.open('".$browserUrl."', 'browser_$this->id', '_blank');",
        "style"=>"color:#fff; margin-bottom:10px;"
    )
))


?>
