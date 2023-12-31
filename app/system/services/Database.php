<?php
namespace Services;

use Engine\Debug;

class Database
{
    private $connection;
    private string $scheme;
    private string $host;
    private string $port;
    private string $user;
    private string $pass;
    private string $database;
    private string $charset = 'UTF8';

    /**
     * @param string $DSN
     *   Examples:
     *     mariadb or mysql - "mysql://user:pass@database:3306/table?charset=UTF8"
     *     postgresql - "pgsql://user:pass@database:5432/table?charset=UTF8"
     */
    public function __construct(string $DSN)
    {
        $DSNParts = parse_url($DSN);
        $this->scheme = $DSNParts['scheme'];
        $this->host = $DSNParts['host'];
        $this->port = $DSNParts['port'];
        $this->user = $DSNParts['user'];
        $this->pass = $DSNParts['pass'];
        $this->database = ltrim($DSNParts['path'], '/');
        if (isset($DSNParts['query'])) {
            parse_str($DSNParts['query'], $queryParameters);
            if ($queryParameters['charset']) {
                $this->charset = $queryParameters['charset'];
            }
        }
    }

    /**
     * @param string $sql
     *   PDO SQL Query
     * @param array $params
     *   PDO SQL Params array
     * @param string $returnType
     *   row, array or lastInsertId (default - array)
     */
    public function query(string $sql, array $params = array(), string $returnType = 'array')
    {
        $debug = Debug::init();
        $debug->addReport('SQL: "' . $sql . '" PARAMS: ' . json_encode($params), 'Database', 'Info');
        $statement = $this->connection->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        if ((count($params, COUNT_RECURSIVE) - count($params)) > 0) {
            foreach ($params as $param) {
                $query = $statement->execute($param);
            }
        } else {
            $query = $statement->execute($params);
        }
        if ($statement->errorCode() !== '00000') {
            $debug->addReport($statement->errorInfo(), 'Database', 'FatalError');
            return false;
        }
        if (empty($query)) {
            return false;
        }
        while ($row = $statement->fetch()) {
            $data[] = $row;
        }
        if (isset($data[0]) && ($returnType == 'row' || $returnType == 'one')) {
            return $data[0];
        }
        if (str_contains($sql, 'INSERT INTO') ||
            str_contains($sql, 'INSERT IGNORE INTO') ||
            $returnType == 'lastInsertId') {
            return $this->connection->lastInsertId();
        }
        if (empty($data)) {
            return true;
        }
        return $data;
    }

    public function init(): void
    {
        try {
            if ($this->scheme == 'mysql' ||
                $this->scheme == 'mysqli' ||
                $this->scheme == 'mariadb') {
                $connection = new \PDO(
                    'mysql:host=' . $this->host .
                    ';port=' . $this->port .
                    ';dbname=' . $this->database .
                    ';charset=' . $this->charset,
                    $this->user,
                    $this->pass,
                    [\PDO::ATTR_EMULATE_PREPARES => false]
                );
                $this->connection = $connection;

            } elseif ($this->scheme == 'pgsql' ||
                $this->scheme == 'postgresql' ||
                $this->scheme == 'postgres' ||
                $this->scheme == 'postgre') {
                $connection = new \PDO(
                    'pgsql:host=' . $this->host .
                    ';port=' . $this->port .
                    ';dbname=' .
                    $this->database .
                    ";options='--client_encoding=" . $this->charset . "'",
                    $this->user,
                    $this->pass,
                    [\PDO::ATTR_EMULATE_PREPARES => false]
                );
                $this->connection = $connection;
            }
        } catch (\Throwable $error) {
            $debug = Debug::init();
            if ($debug->enabled()) {
                $debug->addReport($error, 'Database', 'FatalError');
            }
        }
    }

    public function getLastId(): int
    {
        return $this->connection->lastInsertId();
    }
}
