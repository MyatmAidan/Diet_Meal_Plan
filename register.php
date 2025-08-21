<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: http://localhost/healthy_meal_plan/" . ($_SESSION['user_role'] == 1 ? 'admin' : 'user') . "/index.php");
    exit();
}
require_once './require/common.php';
require_once './require/i18n.php';
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
        $name_err = __("Please Enter Name");
    }
    if ($email == "") {
        $error = true;
        $email_err = __("Please Enter Email");
    }
    if ($password == "") {
        $error = true;
        $psw_err = __("Please Enter Password");
    }
    if ($confirm_password == "") {
        $error = true;
        $confirm_password_err = __("Please Enter Confirm Password");
    }

    if (selectUserEmail($mysqli, $email) > 0) {
        $error = true;
        $email_err = __("Email is already register");
    }

    if ($password != $confirm_password) {
        $error = true;
        $psw_err = $confirm_password_err  = __("Password And Confirm Password must be same");
    }

    if (strlen($password) < 8) {
        $error = true;
        $psw_err = __("Password must be greater than 8 character.");
    }


    if (!$error) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES ('$name', '$email', '$password', '0')";
        $result = $mysqli->query($sql);

        if ($result) {
            header("Location: " . $base_url . "login.php?msg=" . __("Register Success"));
            exit();
        } else {
            $create_msg = '<div class="alert alert-danger mb-3">' . __("Error") . '</div>';
        }
    } else {
        $create_msg = '<div class="alert alert-warning mb-3">' . __("All fields are required.") . '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(html_lang()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Diet Corner</title>
    <script>
        (function() {
            try {
                var stored = localStorage.getItem('theme');
                var theme = stored === 'dark' || stored === 'light' ? stored : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.colorScheme = theme;
            } catch (e) {}
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="d-flex align-items-center justify-content-center" style="min-height:100vh; background: var(--main-bg-gradient);">
        <div class="glass-panel p-5" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <a href="index.php">
                    <img src="images/logo.png" alt="Diet Corner Logo" style="height:60px;" class="theme-invert">
                </a>
            </div>
            <h2 class="mb-4 text-center fw-bold"><?= __('စာရင်းသွင်းရန်') ?></h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label"><?= __('အမည်') ?></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="<?= __('အမည်') ?>" value="<?= $name ?>">
                    <?php if (!empty($name_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($name_err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><?= __('အီးမေးလ်') ?></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="<?= __('အီးမေးလ်') ?>" value="<?= $email ?>">
                    <?php if (!empty($email_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($email_err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><?= __('စကားဝှက်') ?></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="<?= __('စကားဝှက်ဖန်တီးပါ') ?>" value="<?= $password ?>">
                    <?php if (!empty($psw_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($psw_err) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label"><?= __('စကားဝှက်အတည်ပြုခြင်း') ?></label>
                    <input type="password" class="form-control" id="" name="confirm_password" placeholder="<?= __('စကားဝှက်အတည်ပြုခြင်း') ?>" value="<?= $confirm_password ?>">
                    <?php if (!empty($confirm_password_err)): ?>
                        <div class="text-danger small"><?= htmlspecialchars($confirm_password_err) ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="register" class="btn w-100 mb-3"><?= __('စာရင်းသွင်းရန်') ?></button>
            </form>
            <div class="text-center">
                <span class="text-secondary"><?= __('သင့်မှာအကောင့်ရှိပါသလား?') ?></span>
                <a href="login.php" class="text-decoration-none text-primary fw-semibold"><?= __('အကောင့်ဝင်ရန်') ?></a>
            </div>
        </div>
    </div>
    <div class="position-fixed" style="right:1rem; top:1rem;">
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <?= strtoupper($_SESSION['lang'] ?? 'my') ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="require/set_lang.php?lang=my">MY - Burmese</a></li>
                    <li><a class="dropdown-item" href="require/set_lang.php?lang=en">EN - English</a></li>
                </ul>
            </div>
            <button id="themeToggle" class="btn btn-sm btn-outline-secondary theme-toggle" title="Toggle theme">
                <i data-theme-toggle-icon class="bi bi-moon"></i>
            </button>
        </div>
    </div>
    <script src="assets/js/theme.js"></script>
</body>

</html>