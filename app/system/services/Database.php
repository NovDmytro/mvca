<?php
namespace Services;

use Engine\Config;
use Engine\Debug;
use Engine\Console;
use Engine\Output;

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
    public function query(string $sql, array $params = array(), string $returnType = 'array'):mixed
    {
        $debug = Debug::init();
        $debug->addReport('SQL: "' . $sql . '" PARAMS: ' . json_encode($params), 'Database', 'Info');
        $data=[];
        global $container;
        try {
        $statement = $this->connection->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        if ((count($params, COUNT_RECURSIVE) - count($params)) > 0) {
            foreach ($params as $param) {
                $query = $statement->execute($param);
            }
        } else {
            $query = $statement->execute($params);
        }
        if ($statement->errorCode() !== '00000' || empty($query)) {
            $debug->addReport($statement->errorInfo(), 'Database', 'Error');
            if($debug->enabled()) {

                $config = $container->get(Config::class);
                $output = new Output($config);
                $console = new Console($config);
                $content = $output->loadFile('system/Core/Console/V/Console.php', $console->render());
                echo $content;
            }
            die();
        }
        while ($row = $statement->fetch()) {
            $data[] = $row;
        }
        if (str_contains($sql, 'INSERT INTO') ||
            str_contains($sql, 'INSERT IGNORE INTO') ||
            $returnType == 'lastInsertId' || $returnType == 'id') {
            return $this->connection->lastInsertId();
        }
        if (isset($data[0]) && ($returnType == 'row' || $returnType == 'one' || $returnType == 'string')) {
            return $data[0];
        }
        if ($returnType == 'array') {
            return $data;
        }
		return true;
        } catch (\PDOException $e) {
            $debug->addReport($e->getMessage().' in '.$e->getFile().' on line '.$e->getLine(), 'Database', 'Error');
            $debug->addReport($e->getTrace(), 'Database', 'Trace');
            if($debug->enabled()) {
                $config = $container->get(Config::class);
                $console = new Console($config);
                if($debug->jsonView()) {
                    echo json_encode($console->render(),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } else {
                    $output = new Output($config);
                    $content = $output->loadFile('system/Core/Console/V/Console.php', $console->render());
                    echo $content;
                }
            }
            die();
        }
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
