<?php
$id = $_GET["id"];

include "funcs.php";
$pdo = db_con();

$stmt = $pdo->prepare("DELETE FROM cardnest_company WHERE id =:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    redirect("index.php");
}
?>