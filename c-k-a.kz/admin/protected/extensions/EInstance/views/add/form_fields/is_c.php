<div class="well" style="margin-top:20px;">

    <?php

        if ($this->model->is_c === 0)
        {
            $this->model->is_c = "1";
        }

        echo $form->checkboxRow($this->model,"is_c", array(
            
        ));
    ?>

</div>