<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: http://localhost/healthy_meal_plan/" . ($_SESSION['user_role'] == 1 ? 'admin' : 'user') . "/index.php");
    exit();
}
require_once('./require/i18n.php');
require_once('./require/db.php');

$error = '';
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        $error = __("Please enter both email and password.");
    } else {
        $sql = "SELECT * FROM `users` WHERE `email` = '$email' LIMIT 1";
        $result = $mysqli->query($sql);

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_img'] = $user['image'];

                $_SESSION['user_id'] = $user['id'];

                if ($user['role'] == '1') {
                    header("Location: http://localhost/healthy_meal_plan/admin/");
                } else {
                    $check_survey = $mysqli->query("SELECT id FROM user_surveys WHERE user_id = " . $_SESSION['user_id']);
                    if ($check_survey->num_rows == 0) {
                        header("Location: http://localhost/healthy_meal_plan/user/survey_fill.php");
                    } else {
                        header("Location: http://localhost/healthy_meal_plan/user/planner.php");
                    }
                }
                exit();
            } else {
                $error = __("Invalid email or password.");
            }
        } else {
            $error = __("Account not found.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(html_lang()) ?>">

<head>
    <meta charset="UTF-8">
    <title>Login - Diet Corner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <h2 class="mb-4 text-center fw-bold"><?= __('လော့ဂ်အင်ဝင်ရန်') ?></h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label"><?= __('အီးမေးလ်လိပ်စာ') ?></label>
                    <input type="email" class="form-control glass-input" id="email" name="email" placeholder="<?= __('အီးမေးလ်လိပ်စာ') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><?= __('စကားဝှက်') ?></label>
                    <input type="password" class="form-control glass-input" id="password" name="password" placeholder="<?= __('စကားဝှက်') ?>" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary w-100 fw-bold"><?= __('လော့ဂ်အင်ဝင်မည်') ?></button>
            </form>

            <div class="text-center mt-3">
                <span class="text-secondary"><?= __('အကောင့်မရှိဘူးလား?') ?></span>
                <a href="register.php" class="text-decoration-none text-primary fw-semibold"><?= __('စာရင်းသွင်းရန်') ?></a>
            </div>
        </div>
    </div>

    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            color: #222;
        }
    </style>
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