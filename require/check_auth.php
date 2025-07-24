<?php
session_start();

function check_auth($role)
{
    $authRole = $_SESSION['user_role'] == 1 ? 'admin' : 'user';
    if (!isset($_SESSION['user_id'])) {
        header("Location: http://localhost/healthy_meal_plan/login.php");
        exit();
    }

    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != $role) {
        header("Location: http://localhost/healthy_meal_plan/" . $authRole . "/index.php?error_msg=Unauthorized access");
        exit();
    }
}
