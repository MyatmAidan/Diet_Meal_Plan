<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../require/db.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM meal_foods WHERE id = $id");
}
header('Location: meal_food_list.php');
exit();
