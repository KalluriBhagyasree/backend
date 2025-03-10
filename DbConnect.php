<?php 
class DbConnect {
    private $server = '127.0.0.1:3307';
    private $dbname = 'project_db';
    private $user = 'root';  // Change to 'root' if using XAMPP
    private $pass = '';      // Leave empty if using XAMPP

    public function connect() {
        try {
            // Change port to 3307 if MySQL is running on a different port
            $conn = new PDO('mysql:host=' . $this->server . ';dbname=' . $this->dbname, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
}
?>
