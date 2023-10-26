<?php

namespace Services;

class Database
{
    private $_connection;
    private $_scheme;
    private $_host;
    private $_port;
    private $_user;
    private $_pass;
    private $_db_name;

    public function __construct($database_url)
    {
        $url_parts = parse_url($database_url);
        $this->_scheme = $url_parts['scheme'];
        $this->_host = $url_parts['host'];
        $this->_port = $url_parts['port'];
        $this->_user = $url_parts['user'];
        $this->_pass = $url_parts['pass'];
        $this->_db_name = ltrim($url_parts['path'], '/');
    }


    public function query(string $sql_query, array $params = array(),$returnType='row')
    {
        $statement = $this->_connection->prepare($sql_query);
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
                if (strpos($sql_query, "INSERT INTO") !== false || strpos($sql_query, "INSERT IGNORE INTO") !== false) {
                    $data = $this->_connection->lastInsertId();
                }
            }

        }

        return $data;
    }



    public function getLastId(): int
    {
        return $this->_connection->lastInsertId();
    }

    public function initialize()
    {
        try {
            if ($this->_scheme == 'pdo-mysql') {
                $temp_connection = new \PDO("mysql:host=" . $this->_host . ";port=" . $this->_port . ";dbname=" . $this->_db_name,
                    $this->_user,
                    $this->_pass,
                    [\PDO::ATTR_EMULATE_PREPARES => false]);
                $temp_connection->exec("SET NAMES 'utf8';SET CHARACTER SET utf8;SET CHARACTER_SET_CONNECTION=utf8");
                $this->_connection = $temp_connection;

            }

        } catch (\Throwable $th) {
            $this->_console->addDebugInfo("Error loading database");
        }
    }

    public function __destruct()
    {
        $this->_connection->query('SELECT pg_terminate_backend(pg_backend_pid());');
        $this->_connection = null;
    }
}
