<?php

namespace Samples\M;   //Samples - is the main folder src/Samples; M - is the models folder src/Samples/M

use Services\Database; //Class that works with databases


class BasicsModel
{
    private Database $database;    //Database object variable
    public function __construct(
        Database $database    //Database object will come from container
    )
    {
        $this->database = $database;    //Database object variable
    }

    public function getExampleById($id): mixed //Sample models method
    {
        /*
        $this->database->query('sql','params','Return type')
        'sql' - is your sql query 
        'params' - not required, array of params, that you used in sql query. 
            Also, it can contain array of arrays, in this case you will have multiple requests for each
        'returnType' - not required,
            'array' - will return array of rows,
            'row' - will return only first row,
            'lastInsertId' - will return last insert id. Default is 'array'
         */
        $query=$this->database->query(
            "SELECT * FROM mvca_example WHERE id=:id",
            ['id'=>$id],
            'row'
        );
        return $query;
    }
}