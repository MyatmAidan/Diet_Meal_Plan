<?php
require_once('../require/db.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM meal_plans WHERE id = $id");
}
header('Location: meal_plan_list.php');
exit(); 