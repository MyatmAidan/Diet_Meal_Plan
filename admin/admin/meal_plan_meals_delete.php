<?php
require_once('../require/db.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM meal_plan_meals WHERE id = $id");
}
header('Location: meal_plan_meals_list.php');
exit(); 