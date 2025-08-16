<?php
require_once('../require/db.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM meals WHERE id = $id");
}
header('Location: meal_list.php');
exit(); 