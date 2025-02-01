<?php
namespace General\EmailSending\Domain\Database;

use General\EmailSending\Application\Response\Response;
use PDO;
use PDOException;

class Connection
{
    private static $instance = null;
    private $connection;
    private string $host;
    private string $database;
    private $dns;
    private string $user;
    private string $password;

    private function __construct()
    {

        $this->host = $_ENV['DB_HOST'];
        $this->database = $_ENV['DB_DATABASE'];
        $this->dns = "mysql:host={$this->host};dbname={$this->database}";
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];

        try {
            $this->connection = new PDO(
                $this->dns,
                $this->user,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log('Falha na conexão: ' . $e->getMessage(), 3, './error.log');
            Response::error(400, 'Falha na conexão: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->connection;
    }

}
