<?php

namespace Samples\M;

use Services\Database;


class DatabaseModel
{
    private Database $database;
    public function __construct(
        Database $database
    )
    {
        $this->database = $database;
    }

    public function getExampleById($id): mixed
    {
        $query=$this->database->query(
            "SELECT * FROM mvca_example WHERE id=:id",
            ['id'=>1],
            'array'
        );
        return $query;
    }
}