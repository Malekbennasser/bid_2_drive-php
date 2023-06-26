<?php

namespace Db;

use PDO;

abstract class Database
{
    const ADDRESS = "mysql:dbname=autobase;host:localhost;port:8889";
    const USER = "root";
    const PASSWORD = "root";

    /**
     * Création d'un connexion à la base de données
     */
    public static function createDBConnection()
    {
        return new PDO(self::ADDRESS, self::USER, self::PASSWORD);
    }
}
