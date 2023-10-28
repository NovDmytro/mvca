<?php

namespace Services;

class Database
{
    private $DSN;
    private mixed $connection;

    private string $scheme;
    private string $host;
    private string $port;
    private string $user;
    private string $pass;
    private string $database;

    public function __construct($DSN)
    {
        $this->DSN = $DSN;
        $DSNParts = parse_url($DSN);
        $this->scheme = $DSNParts['scheme'];
        $this->host = $DSNParts['host'];
        $this->port = $DSNParts['port'];
        $this->user = $DSNParts['user'];
        $this->pass = $DSNParts['pass'];
        $this->database = ltrim($DSNParts['path'], '/');
    }


    public function query(string $sqlQuery, array $params = array(),$returnType='row')
    {
        $statement = $this->connection->prepare($sqlQuery);
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

            if($data[0] && $returnType=='row'){$data = $data[0];}

            if (is_array($data) && count($data) === 0) {
                $data = false;
            }

            if($returnType=='row'){
                if (strpos($sqlQuery, 'INSERT INTO') !== false || strpos($sqlQuery, 'INSERT IGNORE INTO') !== false) {
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


    /*
        $this->DSN = $DSN;
        $DSNParts = parse_url($DSN);
        $this->scheme = $DSNParts['scheme'];
        $this->host = $DSNParts['host'];
        $this->port = $DSNParts['port'];
        $this->user = $DSNParts['user'];
        $this->pass = $DSNParts['pass'];
        $this->database = ltrim($DSNParts['path'], '/');
    */


    public function init()
    {
        try {
            if ($this->scheme == 'mariadb' || $this->scheme == 'mysql') {
                $connection = new \PDO('mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->database,
                    $this->user,
                    $this->pass,
                    [\PDO::ATTR_EMULATE_PREPARES => false]);
                $connection->exec("SET NAMES 'utf8';SET CHARACTER SET utf8;SET CHARACTER_SET_CONNECTION=utf8");
                $this->connection = $connection;
            }
        } catch (\Throwable $th) {
         //   $this->_console->addDebugInfo('Error loading database');
        }
    }

    public function __destruct()
    {
        $this->connection->query('SELECT pg_terminate_backend(pg_backend_pid());');
        $this->connection = null;
    }
}
