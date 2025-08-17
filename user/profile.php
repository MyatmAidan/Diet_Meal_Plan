<?php
require_once('../require/check_auth.php');
check_auth(0);
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

// Fetch latest survey data
$survey_sql = "SELECT * FROM user_surveys WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$survey_result = $mysqli->query($survey_sql);
$survey = $survey_result->fetch_assoc();

// Fetch latest progress
$progress_sql = "SELECT * FROM user_progress WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
$progress_result = $mysqli->query($progress_sql);
$progress = $progress_result->fetch_assoc();

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
        $name_err = "အမည်ထည့်သွင်းပါ";
    }

    if (empty($email)) {
        $error = true;
        $email_err = "အီးမေးလ်ထည့်သွင်းပါ";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $email_err = "မှန်ကန်သောအီးမေးလ်လိပ်စာထည့်သွင်းပါ";
    }

    // Check if email already exists for other users
    $email_check_sql = "SELECT id FROM users WHERE email = '$email' AND id != $user_id";
    $email_check = $mysqli->query($email_check_sql);

    if ($email_check->num_rows > 0) {
        $error = true;
        $email_err = "ဤအီးမေးလ်လိပ်စာကို အသုံးပြုပြီးဖြစ်သည်";
    }

    // Password validation (only if password is provided)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $error = true;
            $password_err = "စကားဝှက်သည် အနည်းဆုံး ၆ လုံးရှိရမည်";
        } elseif ($password !== $confirm_password) {
            $error = true;
            $password_err = "စကားဝှက်များ မတူညီပါ";
        }
    }

    // Handle image upload
    $image_name = $current_image; // Keep current image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = true;
            $image_err = "ဓာတ်ပုံအမျိုးအစားမှန်ကန်ပါသည်။ (JPG, PNG, GIF သာလက်ခံသည်)";
        } elseif ($_FILES['image']['size'] > $max_size) {
            $error = true;
            $image_err = "ဓာတ်ပုံအရွယ်အစားသည် 5MB ထက်မကြီးရပါ။";
        } else {
            // Create uploads directory if it doesn't exist
            $upload_dir = "../uploads/users/";
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
                $image_err = "ဓာတ်ပုံတင်ရာတွင်အမှားရှိနေပါသည်။";
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

            $success_msg = '<div class="alert alert-success mb-3">ပရိုဖိုင် အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။</div>';
        } else {
            $error = true;
            $success_msg = '<div class="alert alert-danger mb-3">အမှားရှိနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။</div>';
        }
    }
}
ob_end_flush();
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-panel p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">ပရိုဖိုင်</h3>
                    <a href="index.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ပြန်သွားမည်
                    </a>
                </div>

                <?= $success_msg ?>

                <div class="row">
                    <!-- Profile Info Card -->
                    <div class="col-md-4">
                        <div class="glass-panel p-4 mb-4" style="background: rgba(255, 255, 255, 0.1);">
                            <div class="text-center mb-3">
                                <?php if ($current_image): ?>
                                    <img src="../uploads/users/<?= htmlspecialchars($current_image) ?>"
                                        alt="Profile Image" class="profile-image-large mb-3">
                                <?php else: ?>
                                    <div class="profile-avatar-large mx-auto mb-3">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                <?php endif; ?>
                                <h5 class="mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                                <p class="mb-2 text-muted"><?= htmlspecialchars($user['email']) ?></p>
                                <span class="badge bg-success">အသုံးပြုသူ</span>
                            </div>

                            <!-- Quick Stats -->
                            <?php if ($survey): ?>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h6 class="mb-1"><?= $survey['age'] ?></h6>
                                            <small class="text-muted">အသက်</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h6 class="mb-1"><?= $survey['weight'] ?> kg</h6>
                                            <small class="text-muted">အလေးချိန်</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Profile Edit Form -->
                    <div class="col-md-8">
                        <div class="glass-panel p-4">
                            <h5 class="fw-bold mb-3">ပရိုဖိုင်ပြင်ဆင်ခြင်း</h5>
                            <form method="post" enctype="multipart/form-data" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">အမည်</label>
                                            <input type="text" class="form-control glass-input <?= !empty($name_err) ? 'is-invalid' : '' ?>"
                                                id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                                            <?php if (!empty($name_err)): ?>
                                                <div class="invalid-feedback"><?= htmlspecialchars($name_err) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">အီးမေးလ်</label>
                                            <input type="email" class="form-control glass-input <?= !empty($email_err) ? 'is-invalid' : '' ?>"
                                                id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                                            <?php if (!empty($email_err)): ?>
                                                <div class="invalid-feedback"><?= htmlspecialchars($email_err) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">စကားဝှက် (ပြောင်းလဲရန် ထည့်သွင်းပါ)</label>
                                            <input type="password" class="form-control glass-input" id="password" name="password"
                                                placeholder="စကားဝှက်ပြောင်းလဲရန် ထည့်သွင်းပါ">
                                            <small class="form-text text-muted">စကားဝှက်မပြောင်းလဲလိုပါက ဗလာထားပါ</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">စကားဝှက်အတည်ပြုခြင်း</label>
                                            <input type="password" class="form-control glass-input <?= !empty($password_err) ? 'is-invalid' : '' ?>"
                                                id="confirm_password" name="confirm_password"
                                                placeholder="စကားဝှက်အတည်ပြုခြင်း">
                                            <?php if (!empty($password_err)): ?>
                                                <div class="invalid-feedback"><?= htmlspecialchars($password_err) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">ဓာတ်ပုံ</label>
                                    <?php if ($current_image): ?>
                                        <div class="mb-2">
                                            <img src="../uploads/users/<?= htmlspecialchars($current_image) ?>"
                                                alt="Current Profile" class="profile-image-preview">
                                            <small class="d-block text-muted">လက်ရှိဓာတ်ပုံ</small>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control glass-input <?= !empty($image_err) ? 'is-invalid' : '' ?>"
                                        id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">JPG, PNG, GIF ဖိုင်များသာလက်ခံသည် (အများဆုံး 5MB)</small>
                                    <?php if (!empty($image_err)): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($image_err) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="index.php" class="btn btn-secondary">ပယ်ဖျက်မည်</a>
                                    <button type="submit" name="submit" class="btn btn-primary fw-bold">ပြင်ဆင်မည်</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Health Information -->
                <?php if ($survey): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="glass-panel p-4">
                                <h5 class="fw-bold mb-3">ကျန်းမာရေးအချက်အလက်</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-primary"><?= $survey['age'] ?> နှစ်</h6>
                                            <small class="text-muted">အသက်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-success"><?= $survey['weight'] ?> kg</h6>
                                            <small class="text-muted">အလေးချိန်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-info"><?= $survey['height'] ?> cm</h6>
                                            <small class="text-muted">အမြင့်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-warning"><?= round($survey['bmr']) ?> kcal</h6>
                                            <small class="text-muted">BMR</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <strong>ရည်ရွယ်ချက်:</strong> <?= ucfirst($survey['goal']) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>လှုပ်ရှားမှုအဆင့်:</strong> <?= ucfirst($survey['activity_level']) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>ကျား/မ:</strong> <?= ucfirst($survey['gender']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Progress Information -->
                <?php if ($progress): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="glass-panel p-4">
                                <h5 class="fw-bold mb-3">လက်ရှိတိုးတက်မှု</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="progress-stat">
                                            <h6 class="text-primary"><?= $progress['record_date'] ?></h6>
                                            <small class="text-muted">ရက်စွဲ</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="progress-stat">
                                            <h6 class="text-success"><?= $progress['weight'] ?> kg</h6>
                                            <small class="text-muted">လက်ရှိအလေးချိန်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="progress-stat">
                                            <h6 class="text-info"><?= ucfirst($progress['goal']) ?></h6>
                                            <small class="text-muted">ရည်ရွယ်ချက်</small>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($progress['notes'])): ?>
                                    <div class="mt-3">
                                        <strong>မှတ်ချက်:</strong> <?= htmlspecialchars($progress['notes']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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

    .stat-item,
    .health-stat,
    .progress-stat {
        padding: 10px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        margin-bottom: 10px;
    }

    .stat-item h6,
    .health-stat h6,
    .progress-stat h6 {
        margin: 0;
        font-weight: 600;
    }

    .profile-image-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
</style>

<?php require_once('../layout/footer.php'); ?>