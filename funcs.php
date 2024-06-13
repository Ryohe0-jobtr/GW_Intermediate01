<?php

function h($a){
    return htmlspecialchars($a, ENT_QUOTES);
}

function space($string) {
    $string = str_replace('　', ' ', $string);
    return trim($string);
}

function db_con(){
    try {
        $pdo = new PDO('mysql:dbname=dbname;charset=utf8mb4;host=localhost','root','');
        return $pdo;
    } catch (PDOException $e) {
        exit('DB-Connection-Error:'.$e->getMessage());
    }
}

function redirect($page) {
    header("Location: ".$page);
    exit;
}

function sqlError($stmt){
    $error = $stmt->errorInfo();
    exit("ErrorSQL:".$error[2]);
}

?>