<?php


namespace Sourcegr\QueryBuilder\Grammars;


use PDO;


class MySQL
{
    /**
     * @var PDO
     */
    private $dbh = null;
    private $config = [];
    private $inited = false;

    private function getConnection()
    {
        if ($this->dbh) {
            return $this->dbh;
        }

        $host = $this->config['HOST'] ?? '127.0.0.1';
        $user = $this->config['USER'] ?? 'root';
        $pass = $this->config['PASS'] ?? 'root';
        $port = $this->config['PORT'] ?? 3306;
        $enc = $this->config['ENC'] ?? 'UTF8';
        $db = $this->config['DB'] ?? 'test';
        $PDOParams = $this->config['PDOCONFIG'] ?? [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            ];

        $this->dbh = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, $PDOParams);
        $this->dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES '$enc'");
        return $this->dbh;
    }

    /**
     * MySQL constructor.
     *
     * @param array $config [HOST=127.0.0.1, USER=root, PASS=root, PORT=3306, ENC=UTF8, DB=test]
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getPlaceholder()
    {
        return '?';
    }

    public function createLimit($count = null, $startAt = null)
    {
        if ($count && $startAt) {
            return "LIMIT $count OFFSET $startAt";
        }

        if ($count) {
            return "LIMIT $count";
        }

        return null;
    }


    public function select($sqlString, $sqlParams, $mode = null)
    {
        $st = $this->getConnection()->prepare($sqlString);
        $res = $st->execute($sqlParams);

        if ($res === false) {
            $info = $st->errorInfo();
            throw new SelectErrorException($info[0] . ': ' . $info[2] . ' (' . $info[1] . ')');
        }

        return $st->fetchAll($mode);
    }

    public function insert($sqlString, $sqlParams)
    {
        $st = $this->getConnection()->prepare($sqlString);
        $res = $st->execute($sqlParams);

        if ($res === false) {
            $info = $st->errorInfo();
            throw new InsertErrorException($info[0] . ': ' . $info[2] . ' (' . $info[1] . ')');
        }

        return $this->getConnection()->lastInsertId();
    }

    public function update($sqlString, $sqlParams)
    {
        $st = $this->getConnection()->prepare($sqlString);
        $res = $st->execute($sqlParams);

        if ($res === false) {
            $info = $st->errorInfo();
            throw new UpdateErrorException($info[0] . ': ' . $info[2] . ' (' . $info[1] . ')');
        }


        return $st->rowCount();
    }

    public function delete($sqlString, $sqlParams)
    {
        $st = $this->getConnection()->prepare($sqlString);
        $res = $st->execute($sqlParams);

        if ($res === false) {
            $info = $st->errorInfo();
            throw new DeleteErrorException($info[0] . ': ' . $info[2] . ' (' . $info[1] . ')');
        }

        return $st->rowCount();
    }
}