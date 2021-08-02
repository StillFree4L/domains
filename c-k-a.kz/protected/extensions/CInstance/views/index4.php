
    <?php

    if (!empty($records)) {

        foreach ($records as $child)
        {

            $this->render("index5", array(
                "record"=>$child
            ));
        
        }

    }
    ?>

