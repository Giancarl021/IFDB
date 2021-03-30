<?php

class PreparedStatement
{
    private static $indexes = [];
    private $index, $connection, $result;

    public static function Indexes()
    {
        return self::$indexes;
    }

    private static function GenerateIndex()
    {
        $r = "";
        do {
            $r = bin2hex(random_bytes(16));
        } while (in_array($r, self::$indexes));

        array_push(self::$indexes, $r);

        return $r;
    }

    public function __construct($_connection, $statement)
    {
        if (!$_connection) throw new Exception("Invalid connection");

        $this->index = self::GenerateIndex();
        $this->connection = $_connection;

        pg_prepare($this->connection, $this->index, $statement);
    }

    public function run($values = [])
    {
        $this->result = pg_execute($this->connection, $this->index, $values);
        return $this;
    }

    public function digest()
    {
        if (!$this->result) throw new Error("No result founded");

        return pg_fetch_array($this->result);
    }
}

class IFDB
{
    private $connection;
    private $testConnectionTimeout = 3;

    public function __construct($username, $password)
    {
        $connection = $this->getConnection($username, $password);

        $this->connection = $connection;
    }

    public function prepare($statement)
    {
        if (!$this->connection) throw new Exception("Invalid connection");

        return new PreparedStatement($this->connection, $statement);
    }

    public function query($statement)
    {
        if (!$this->connection) throw new Exception("Invalid connection");

        $result = pg_query($this->connection, $statement);
        return pg_fetch_array($result);
    }

    private function buildConnectionString($host, $username, $password)
    {
        return "host=$host user=$username password=$password dbname=$username port=5432";
    }

    private function getConnection($username, $password)
    {
        $connection_string = null;
        $production_host = "192.168.20.18";
        $dev_host = "200.19.1.18";

        if ($fp = @fsockopen($production_host, 5432, $errno, $errstr, $this->testConnectionTimeout)) {
            $connection_string = $this->buildConnectionString($production_host, $username, $password);
            fclose($fp);
        } else {
            $connection_string = $this->buildConnectionString($dev_host, $username, $password);
        }

        $connection = @pg_connect($connection_string);

        if (!$connection) {
            throw new Error("Failed to connect to both database endpoints");
        }

        return $connection;
    }
}
