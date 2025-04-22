<?php
include('db.php');

$sql = $db->query("SELECT * FROM canvas");

echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));

?>