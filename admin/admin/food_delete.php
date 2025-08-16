<?php
require_once('../require/db.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM foods WHERE id = $id");
}
header('Location: food_list.php');
exit(); 