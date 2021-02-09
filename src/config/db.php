<?php
class db{
    public function connect(){
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "slim_crud";


        //Connect Database using PHP PDO CRUD
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

        //Set Error Mode
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Set Default fetch mode from the DB
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

        return $pdo;
    }
}