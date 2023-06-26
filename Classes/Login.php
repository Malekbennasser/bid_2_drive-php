<?php

namespace Classes;

include_once __DIR__ . "/../DB/Database.php";

use Db\Database;
use PDO;

session_start();
class Login
{


    protected $username;
    protected $password;
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    public function login($username)
    {
        $dbh = Database::createDBConnection();

        $query = $dbh->prepare("SELECT * FROM `users` 
WHERE `username` = ?
");

        $query->execute([$username]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);


        if (count($result) > 0) {
            $password = $result[0]['password'];
            if ($password === $this->password) {
                $_SESSION['userid'] = $result[0]['id'];
                header("Location: createauction_index.php");
            } else {
                echo "<div class='alert alert-danger'>Password does not match.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Username does not exist.</div>";
        }
    }
}
