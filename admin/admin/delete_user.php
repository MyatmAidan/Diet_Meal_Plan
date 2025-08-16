<?php
require_once('../require/db.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM users WHERE id = $id");
}
header('Location: user_list.php');
exit(); 