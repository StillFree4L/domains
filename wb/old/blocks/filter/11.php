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
