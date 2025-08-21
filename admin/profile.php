<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../require/i18n.php');
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$error = false;
$success_msg = '';
$name_err = $email_err = $password_err = $image_err = '';

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $mysqli->query($sql);

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$user = $result->fetch_assoc();

// Initialize form data
$name = $user['name'];
$email = $user['email'];
$current_image = $user['image'];

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($name)) {
        $error = true;
        $name_err = __("အမည်ထည့်သွင်းပါ");
    }

    if (empty($email)) {
        $error = true;
        $email_err = __("အီးမေးလ်ထည့်သွင်းပါ");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $email_err = __("မှန်ကန်သောအီးမေးလ်လိပ်စာထည့်သွင်းပါ");
    }

    // Check if email already exists for other users
    $email_check_sql = "SELECT id FROM users WHERE email = '$email' AND id != $user_id";
    $email_check = $mysqli->query($email_check_sql);

    if ($email_check->num_rows > 0) {
        $error = true;
        $email_err = __("ဤအီးမေးလ်လိပ်စာကို အသုံးပြုပြီးဖြစ်သည်");
    }

    // Password validation (only if password is provided)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $error = true;
            $password_err = __("စကားဝှက်သည် အနည်းဆုံး ၆ လုံးရှိရမည်");
        } elseif ($password !== $confirm_password) {
            $error = true;
            $password_err = __("စကားဝှက်များ မတူညီပါ");
        }
    }

    // Handle image upload
    $image_name = $current_image; // Keep current image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = true;
            $image_err = __("ဓာတ်ပုံအမျိုးအစားမှန်ကန်ပါသည်။ (JPG, PNG, GIF သာလက်ခံသည်)");
        } elseif ($_FILES['image']['size'] > $max_size) {
            $error = true;
            $image_err = __("ဓာတ်ပုံအရွယ်အစားသည် 5MB ထက်မကြီးရပါ။");
        } else {
            // Create uploads directory if it doesn't exist
            $upload_dir = "../images/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = "user_" . $user_id . "_" . time() . "." . $file_extension;
            $upload_path = $upload_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if ($current_image && file_exists($upload_dir . $current_image)) {
                    unlink($upload_dir . $current_image);
                }
            } else {
                $error = true;
                $image_err = __("ဓာတ်ပုံတင်ရာတွင်အမှားရှိနေပါသည်။");
            }
        }
    }

    if (!$error) {
        if (!empty($password)) {
            // Update with new password
            $update_sql = "UPDATE users SET name = '$name', email = '$email', password = '$password', image = '$image_name' WHERE id = $user_id";
        } else {
            // Update without changing password
            $update_sql = "UPDATE users SET name = '$name', email = '$email', image = '$image_name' WHERE id = $user_id";
        }

        if ($mysqli->query($update_sql)) {
            // Update session data
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            $success_msg = '<div class="alert alert-success mb-3">' . __("ပရိုဖိုင် အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။") . '</div>';
        } else {
            $error = true;
            $success_msg = '<div class="alert alert-danger mb-3">' . __("အမှားရှိနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။") . '</div>';
        }
    }
}
ob_end_flush();
// 
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-panel p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0"><?= __('ပရိုဖိုင်') ?></h3>
                    <a href="index.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> <?= __('ပြန်သွားမည်') ?>
                    </a>
                </div>

                <?= $success_msg ?>

                <!-- Profile Info Card -->
                <div class="glass-panel p-4 mb-4" style="background: rgba(255, 255, 255, 0.1);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="profile-avatar-large">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                            <p class="mb-0 text-muted"><?= htmlspecialchars($user['email']) ?></p>
                            <span class="badge bg-primary"><?= __('အက်မင်') ?></span>
                        </div>
                    </div>
                </div>

                <form method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label"><?= __('အမည်') ?></label>
                        <input type="text" class="form-control glass-input <?= !empty($name_err) ? 'is-invalid' : '' ?>"
                            id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                        <?php if (!empty($name_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($name_err) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><?= __('အီးမေးလ်') ?></label>
                        <input type="email" class="form-control glass-input <?= !empty($email_err) ? 'is-invalid' : '' ?>"
                            id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        <?php if (!empty($email_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($email_err) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label"><?= __('စကားဝှက် (ပြောင်းလဲရန် ထည့်သွင်းပါ)') ?></label>
                        <input type="password" class="form-control glass-input" id="password" name="password"
                            placeholder="စကားဝှက်ပြောင်းလဲရန် ထည့်သွင်းပါ">
                        <small class="form-text text-muted"><?= __('စကားဝှက်မပြောင်းလဲလိုပါက ဗလာထားပါ') ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label"><?= __('စကားဝှက်အတည်ပြုခြင်း') ?></label>
                        <input type="password" class="form-control glass-input <?= !empty($password_err) ? 'is-invalid' : '' ?>"
                            id="confirm_password" name="confirm_password"
                            placeholder="စကားဝှက်အတည်ပြုခြင်း">
                        <?php if (!empty($password_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($password_err) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label"><?= __('ဓာတ်ပုံ') ?></label>
                        <?php if ($current_image): ?>
                            <div class="mb-2">
                                <img src="../uploads/users/<?= htmlspecialchars($current_image) ?>"
                                    alt="Current Profile" class="profile-image-preview">
                                <small class="d-block text-muted"><?= __('လက်ရှိဓာတ်ပုံ') ?></small>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control glass-input <?= !empty($image_err) ? 'is-invalid' : '' ?>"
                            id="image" name="image" accept="image/*">
                        <small class="form-text text-muted"><?= __('JPG, PNG, GIF ဖိုင်များသာလက်ခံသည် (အများဆုံး 5MB)') ?></small>
                        <?php if (!empty($image_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($image_err) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary"><?= __('ပယ်ဖျက်မည်') ?></a>
                        <button type="submit" name="submit" class="btn btn-primary fw-bold">
                            <i class="bi bi-check-circle me-2"></i><?= __('ပြင်ဆင်မည်') ?>
                        </button>
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

    .glass-input:focus {
        background: rgba(255, 255, 255, 0.35) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25) !important;
    }

    .profile-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .profile-avatar-large i {
        font-size: 40px;
        color: #fff;
    }
</style>

<?php require_once('../layout/footer.php'); ?>