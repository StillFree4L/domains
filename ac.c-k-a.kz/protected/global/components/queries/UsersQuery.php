<?php

namespace glob\components\queries;

use glob\components\ActiveQuery;

class UsersQuery extends ActiveQuery {

    public function onlyDoctors()
    {
        return $this->andWhere("(info LIKE '%\"role\":\"doctor\"%' OR info LIKE '%\"role\":\"head\"%')");
    }

}

?>