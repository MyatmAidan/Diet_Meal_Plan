<?php
ob_start();
require_once('../layout/header.php');
$error = false;
$create_msg =
    $name =
    $email =
    $name_err =
    $email_err = '';
function selectUserEnail($mysqli, $email)
{
    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $result = $mysqli->query($sql);
    return $result;
}
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = intval($_POST['role']);

    if ($name == "") {
        $error = true;
        $name_err = "Please Enter Name";
    }

    if (selectUserEnail($mysqli, $email)) {
        $error = true;
        $email_err = "Email is already register";
    }

    if (!$error) {
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES ('$name', '$email', '$password', '$role')";
        $result = $mysqli->query($sql);

        if ($result) {
            header("Location: " . $admin_url . "user_list.php?msg=created");
            exit();
        } else {
            $create_msg = '<div class="alert alert-danger mb-3">Error</div>';
        }
    } else {
        $create_msg = '<div class="alert alert-warning mb-3">All fields are required.</div>';
    }
}
ob_end_flush();
?>
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">Create User</h3>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control glass-input" id="name" name="name">
                        <?php if (!empty($name_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($name_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control glass-input" id="email" name="email">
                        <?php if (!empty($email_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($email_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control glass-input" id="password" name="password">

                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Password</label>
                        <input type="password" class="form-control glass-input" id="confirm_password" name="confirm_password">

                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select glass-input" id="role" name="role" required>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="<?= $admin_url ?>user_list.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-success fw-bold">Create</button>
                    </div>
                </form>
            </div>
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
<?php require_once('../layout/footer.php'); ?>