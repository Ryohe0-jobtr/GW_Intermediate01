<?php
$name        = $_POST["name"];
$affiliation = $_POST["affiliation"];
$tel         = $_POST["tel"];
$updateBy    = $_POST["updateBy"];
$id          = $_POST["id"];

include "funcs.php";
$pdo = db_con();

$update_date = date("Y-m-d");

$spl = "UPDATE cardnest_person SET name=:name, affiliation=:affiliation, tel=:tel, update_by=:updateBy, update_date=:update_date WHERE id=:id";
$stmt = $pdo->prepare($spl);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':affiliation', $affiliation, PDO::PARAM_STR);
$stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
$stmt->bindValue(':updateBy', $updateBy, PDO::PARAM_STR);
$stmt->bindValue(':update_date', $update_date, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    redirect("personal.php?id=".$id);
}

?>
