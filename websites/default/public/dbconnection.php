<?php
class DatabaseConnection {
    private $servername;
    private $username;
    private $password;
    private $databasename;
    private $connection;

    public function __construct($servername = 'mysql', $username = 'root', $password = 'root', $databasename = 'wucdb') {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->databasename = $databasename;
        $this->connect();
    }

    private function connect() {
        try {
            $this->connection = new PDO('mysql:dbname=' . $this->databasename . ';host=' . $this->servername, $this->username, $this->password);
        } catch (PDOException $e) {
            // Handle connection errors (you can add logging or error messages here)
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>