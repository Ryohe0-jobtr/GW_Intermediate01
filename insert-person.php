<?php

$name           = $_POST["name"];
$affiliation    = $_POST["affiliation"];
$tel            = isset($_POST["tel"]) ? $_POST["tel"] : null; 
$mail           = isset($_POST["mail"]) ? $_POST["mail"] : null; 
$registeredBy   = $_POST["registeredBy"]; 

$companyId      = $_POST["id"];

include "funcs.php";
$pdo = db_con();

$sql = "INSERT INTO cardnest_person (company_id, name, affiliation, tel, mail, registered_by, update_by, registered_date, update_date)
        VALUES(:companyId, :name, :affiliation, :tel, :mail, :registeredBy, :registeredBy, CURDATE(), CURDATE())"; 

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':companyId', $companyId, PDO::PARAM_INT);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':affiliation', $affiliation, PDO::PARAM_STR);
$stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
$stmt->bindValue(':registeredBy', $registeredBy, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    $id = $pdo->lastInsertId();
    redirect("personal.php?id=".$id);
}
?>
