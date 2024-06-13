<?php
$id = $_GET["id"];

include "funcs.php";
$pdo = db_con();

$stmt = $pdo->prepare("SELECT company_id FROM cardnest_person WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    $result = $stmt->fetch();
    $companyId = $result['company_id'];
}

$stmt = $pdo->prepare("DELETE FROM cardnest_person WHERE id =:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    redirect("company.php?id=".$companyId);
}
?>