<?php
$company      = $_POST["company"];
$postcode     = $_POST["postcode"];
$postcode     = substr($postcode, 0, 7);
$address      = $_POST["address"];
$registeredBy = $_POST["registeredBy"];

include "funcs.php";
$pdo = db_con();


$spl ="INSERT INTO cardnest_company(company,postcode,address,registered_by,registered_date,update_date)VALUES(:company,:postcode,:address,:registeredBy,CURDATE(),CURDATE())";
$stmt = $pdo->prepare($spl);
$stmt->bindValue(':company', $company, PDO::PARAM_STR);
$stmt->bindValue(':postcode', $postcode, PDO::PARAM_STR);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':registeredBy', $registeredBy, PDO::PARAM_STR);
$status = $stmt->execute();


if ($status == false) {
    sqlError($stmt);
} else {
    $id = $pdo->lastInsertId(); 
    redirect("company.php?id=".$id);
}

?>