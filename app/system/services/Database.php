<?php

namespace Services;

class Database
{
    private $connection;
    private string $scheme;
    private string $host;
    private string $port;
    private string $user;
    private string $pass;
    private string $database;

    public function __construct($DSN)
    {
        $DSNParts = parse_url($DSN);
        $this->scheme = $DSNParts['scheme'];
        $this->host = $DSNParts['host'];
        $this->port = $DSNParts['port'];
        $this->user = $DSNParts['user'];
        $this->pass = $DSNParts['pass'];
        $this->database = ltrim($DSNParts['path'], '/');
    }

    public function init(): void
    {
        try {
            if ($this->scheme == 'mysql' ||
                $this->scheme == 'mysqli' ||
                $this->scheme == 'mariadb') { // mysqli & mariadb - aliases, do not use them
                $connection = new \PDO(
                    'mysql:host=' . $this->host .
                        ';port=' . $this->port .
                        ';dbname=' . $this->database,
                    $this->user,
                    $this->pass
                );
                $connection->exec("SET NAMES 'utf8';SET CHARACTER SET utf8;SET CHARACTER_SET_CONNECTION=utf8");
                $this->connection = $connection;

            }elseif ($this->scheme == 'pgsql' ||
                $this->scheme == 'postgresql' ||
                $this->scheme == 'postgres' ||
                $this->scheme == 'postgre'){ // postgresql & postgres & postgre - aliases, do not use them
                $connection = new \PDO(
                    'pgsql:host=' . $this->host .
                        ';port=' . $this->port .
                        ';dbname=' .
                        $this->database.
                        ";options='--client_encoding=UTF8'",
                    $this->user,
                    $this->pass
                );
                $this->connection = $connection;
            }
        } catch (\Throwable $th) {
            echo '<pre>'.$th.'</pre>';
            //   $this->_console->addDebugInfo('Error loading database');
        }
    }
    /**
     * @param string $sql
     *   PDO SQL Query
     * @param array $params
     *   PDO SQL Params array
     * @param string $returnType
     *   Row, array or lastInsertId (default - array)
     */
    public function query(string $sql, array $params = array(), string $returnType='array')
    {
        $statement = $this->connection->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);

        if ((count($params, COUNT_RECURSIVE) - count($params)) > 0) {
            foreach ($params as $param) {
                $query = $statement->execute($param);
            }
        } else {
            $query = $statement->execute($params);
        }

//MY FAST DEBUG MOD
/*
        if ($statement->errorCode() !== '00000') {
            echo '<pre>';
            var_dump($statement);
            print_r($statement->errorInfo());
            echo '</pre>';
        }
*/
//MY FAST DEBUG MOD end
        $data = array();

        if ($query) {
            while ($row = $statement->fetch()) {
                $data[] = $row;
            }
            $statement = null;
            if($data[0] && ($returnType=='row' || $returnType=='one')){$data = $data[0];}
            if($returnType=='lastInsertId'){$this->connection->lastInsertId();}
            if (is_array($data) && count($data) === 0) {
                $data = false;
            }
            if($returnType=='row' || $returnType=='one'){
                if (strpos($sql, 'INSERT INTO') !== false ||
                    strpos($sql, 'INSERT IGNORE INTO') !== false) {
                    $data = $this->connection->lastInsertId();
                }
            }
        }
        return $data;
    }

    public function getLastId(): int
    {
        return $this->connection->lastInsertId();
    }
}