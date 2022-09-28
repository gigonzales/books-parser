<?php
require('config/envvariables.php');

class PDOConnection extends PDO
{
    private string $engine;
    private string $host;
    private string $database;
    private string $user;
    private string $pass;
    private string $port;

    public function __construct(
        string $engine = DATABASE_ENGINE,
        string $host = DATABASE_HOST,
        string $database = DATABASE_NAME,
        string $user = DATABASE_USER,
        string $pass = DATABASE_PASSWORD,
        string $port = DATABASE_PORT
    ) {
        $this->port = $port;
        $this->pass = $pass;
        $this->user = $user;
        $this->database = $database;
        $this->host = $host;
        $this->engine = $engine;
        $dsn = $this->engine
            . ':dbname=' . $this->database
            . ';host=' . $this->host
            . ';port=' . $this->port;

        parent::__construct($dsn, $this->user, $this->pass);
    }
}
