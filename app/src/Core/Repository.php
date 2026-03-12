<?php 

namespace App\Core;

use App\Config;

use PDO;

class Repository{
       private static ?PDO $connection = null;

    public function getConnection() : PDO{
        if (self::$connection ===null) {
            $this->connect();
        }
        return self::$connection;
    }

    public function connect(){
        $connectionString = 'mysql:host=' . Config::DB_SERVER_NAME . ';dbname=' . 
              Config::DB_NAME . ';charset=utf8mb4';
              // Create new PDO connection
          self::$connection = new \PDO(
              $connectionString,
              Config::DB_USERNAME,
              Config::DB_PASSWORD,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
              ]

          );
           return self::$connection;
    }
}