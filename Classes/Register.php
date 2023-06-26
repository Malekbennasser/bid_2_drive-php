<?php

namespace Classes;

include_once __DIR__ . "/../DB/Database.php";

use Db\Database;
use PDO;

class Register

{
    protected $username;
    protected $email;
    protected $password;

    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
    public function register($username, $email, $password)
    {
        $dbh = Database::createDBConnection();

        $query = $dbh->prepare("SELECT * FROM `users` 
        WHERE `username` = ? OR `email`= ?
        ");
        $query->execute([$username, $email]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            echo "<div class='alert alert-danger'>Username or email already exist.</div>";
        } else {
            $query = $dbh->prepare("INSERT 
            INTO `users` (`username`, `email`, `password`) VALUES (?,?,?) 
            ");

            $query->execute([$username, $email, $password]);
            header('Location: login_index.php');
        }
    }
}
