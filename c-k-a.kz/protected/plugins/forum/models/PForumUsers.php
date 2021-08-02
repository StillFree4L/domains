<?php

class PForumUsers extends Users
{

    var $author_type = "user";
    public function relations()
    {
        return array_merge(array(
            'posts' => array(self::STAT, 'PForumPosts','author_id'),
        ),parent::relations());
    }   
    
    public function afterFind()
    {
        if ($this->role == Users::ROLE_ADMIN OR $this->role == Users::ROLE_ROOT) {
            $this->author_type = "administrator";
        }
        parent::afterFind();
    }
    
    

}

?>
