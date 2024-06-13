<?php
$company      = $_POST["company"];
$postcode     = $_POST["postcode"];
$postcode     = substr($postcode, 0, 7);
$address      = $_POST["address"];
$registeredBy = $_POST["registeredBy"];
$id           = $_POST["id"];

include "funcs.php";
$pdo = db_con();

$update_date = date("Y-m-d");

$spl ="UPDATE cardnest_company SET company=:company,postcode=:postcode,address=:address,registered_by=:registeredBy,update_date=:update_date WHERE id=:id";
$stmt = $pdo->prepare($spl);
$stmt->bindValue(':company', $company, PDO::PARAM_STR);
$stmt->bindValue(':postcode', $postcode, PDO::PARAM_STR);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':registeredBy', $registeredBy, PDO::PARAM_STR);
$stmt->bindValue(':update_date', $update_date, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    $redirect = "company.php?id=".$id;
    redirect($redirect);
}

?>