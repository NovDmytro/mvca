<?php
namespace Products\M;
use Services\Database;
class StorageModel
{
    private Database $database;
    public function __construct(
        Database $database,
    )
    {
        $this->database = $database;
    }
    public function getItemById($id): array|bool
    {
        $query=$this->database->query(
            "SELECT * FROM mvca_example WHERE id=:id",
            ['id'=>$id],
            'row'
        );
        return $query;
    }
}