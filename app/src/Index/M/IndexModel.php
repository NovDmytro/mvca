<?php

namespace Index\M;

use Services\Database;

class IndexModel
{
    public function __construct(Database $database){
        $this->database=$database;    //Database
    }
	
	    public function getProductById($id): mixed
    {
        $query=$this->database->query(
            "SELECT * FROM products WHERE id=:id",
            ['id'=>$id],
            'row'
        );
        return $query;
    }
}