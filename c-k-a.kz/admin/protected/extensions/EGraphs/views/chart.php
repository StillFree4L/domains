<div id="<?=$this->graph_uniq_name?>">
<strong>You need to upgrade your Flash Player</strong>
</div>
<script type="text/javascript">
    // <![CDATA[
    var so = new SWFObject("<?=$this->baseUrl.'/'.$this->type.'/am' . $this->type . '.swf'?>", "chart_<?=$this->graph_uniq_name?>", "<?=$this->width?>", "<?=$this->height?>", "8", "#FFFFFF");
    so.addVariable("path", "<?=$this->baseUrl?>");
    <?=$str?>
    so.addVariable("chart_data", "<?=$this->data?>");
    so.addVariable("preloader_color", "#999999");
    so.addParam("wmode", "transparent");
    so.write("<?=$this->graph_uniq_name?>");
    // ]]>
</script>
