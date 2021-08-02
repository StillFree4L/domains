<?= \yii\helpers\Html::beginTag("div", $this->context->htmlOptions) ?>
    <div class="choser-list">
        <?php
        foreach ($this->context->list as $l) {
            ?>
            <p class="text-muted choser-item <?=in_array($l->id, ($this->context->values  ? : [])) ? "active" : ""?>" vid="<?=$l->id?>"><?=$l->{$this->context->list_label_attribute}?></p>
        <?php
        }
        ?>
    </div>
    <input type="hidden" value='<?=json_encode($this->context->values)?>' class="choser-input" name="<?=$this->context->name?>" />
<?= \yii\helpers\Html::endTag("div"); ?>