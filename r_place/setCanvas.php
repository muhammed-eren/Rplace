<?php
include('db.php');
$x = $_POST["x"];
$y = $_POST["y"];
$color = $_POST["color"];

$sql = $db->query("SELECT * FROM canvas WHERE x = $x AND y = $y");
if($sql->rowCount() > 0){
    $db->query("UPDATE canvas SET x = $x, y = $y, color = '$color' WHERE x = $x AND y = $y");
}
else
{
    $db->query("INSERT INTO canvas (x, y,color) VALUES ($x, $y, '$color')");
}