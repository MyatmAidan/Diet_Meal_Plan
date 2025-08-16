<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: http://localhost/healthy_meal_plan/" . ($_SESSION['user_role'] == 1 ? 'admin' : 'user') . "/index.php");
    exit();
}
require_once './require/common.php';
require_once './require/db.php';
$error = false;
$create_msg =
    $name =
    $email =
    $name_err =
    $email_err =
    $password =
    $confirm_password =
    $psw_err =
    $confirm_password_err = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    function selectUserEmail($mysqli, $email)
    {
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = $mysqli->query($sql);
        $row = $result->num_rows;
        return $row;
    }
    if ($name == "") {
        $error = true;
        $name_err = "Please Enter Name";
    }
    if ($email == "") {
        $error = true;
        $email_err = "Please Enter Email";
    }
    if ($password == "") {
        $error = true;
        $psw_err = "Please Enter Password";
    }
    if ($confirm_password == "") {
        $error = true;
        $confirm_password_err = "Please Enter Confirm Password";
    }

    if (selectUserEmail($mysqli, $email) > 0) {
        $error = true;
        $email_err = "Email is already register";
    }

    if ($password != $confirm_password) {
        $error = true;
        $psw_err = $confirm_password_err  = "Password And Confirm Password must be same";
    }

    if (strlen($password) < 8) {
        $error = true;
        $psw_err = "Password must be greater than 8 character.";
    }


    if (!$error) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES ('$name', '$email', '$password', '0')";
        $result = $mysqli->query($sql);

        if ($result) {
            header("Location: " . $base_url . "login.php?msg=Register Success");
            exit();
        } else {
            $create_msg = '<div class="alert alert-danger mb-3">Error</div>';
        }
    } else {
        $create_msg = '<div class="alert alert-warning mb-3">All fields are required.</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Diet Corner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <div class="d-flex align-items-center justify-content-center" style="min-height:100vh; background: var(--main-bg-gradient);">
        <div class="glass-panel p-5" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <a href="index.php">
                    <img src="images/logo.png" alt="Diet Corner Logo" style="height:60px;">
                </a>
            </div>
            <h2 class="mb-4 text-center fw-bold">စာရင်းသွင်းရန်</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">အမည်</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="သင့်အမည်ထည့်ပါ" value="<?= $name ?>">
                    <?php if (!empty($name_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($name_err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">အီးမေးလ်</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="သင့်အီးမေးလ်ထည့်ပါ" value="<?= $email ?>">
                    <?php if (!empty($email_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($email_err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">စကားဝှက်</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="စကားဝှက်ဖန်တီးပါ" value="<?= $password ?>">
                    <?php if (!empty($psw_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($psw_err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">စကားဝှက်အတည်ပြုခြင်း</label>
                    <input type="password" class="form-control" id="" name="confirm_password" placeholder="စကားဝှက်အတည်ပြုပါ" value="<?= $confirm_password ?>">
                    <?php if (!empty($confirm_password_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($confirm_password_err) ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="register" class="btn w-100 mb-3">စာရင်းသွင်းရန်</button>
            </form>
            <div class="text-center">
                <span class="text-secondary">သင့်မှာအကောင့်ရှိပါသလား?</span>
                <a href="login.php" class="text-decoration-none text-primary fw-semibold">အကောင့်ဝင်ရန်</a>
            </div>
        </div>
    </div>
</body>

</html>