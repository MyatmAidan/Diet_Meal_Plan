<?php
session_start();
session_unset();
session_destroy();
header("Location: http://localhost/healthy_meal_plan/index.php");
exit();
