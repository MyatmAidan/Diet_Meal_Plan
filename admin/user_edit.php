<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$error = false;
$success_msg = '';
$name_err = $email_err = $image_err = '';

// Get user ID from URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) {
    header("Location: user_list.php");
    exit();
}

// Fetch user data
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: user_list.php");
    exit();
}

$user = $result->fetch_assoc();

// Initialize form data
$name = $user['name'];
$email = $user['email'];
$current_image = $user['image'];
$role = $user['role'];

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = intval($_POST['role']);

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
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $email_check = $stmt->get_result();

    if ($email_check->num_rows > 0) {
        $error = true;
        $email_err = "ဤအီးမေးလ်လိပ်စာကို အသုံးပြုပြီးဖြစ်သည်";
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
        $update_sql = "UPDATE users SET name = '$name', email = '$email', role = $role, image = '$image_name' WHERE id = $user_id";

        if ($mysqli->query($update_sql)) {
            header("Location: user_list.php?msg=updated");
            exit();
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
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0">အသုံးပြုသူပြင်ဆင်ခြင်း</h3>
                    <a href="user_list.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ပြန်သွားမည်
                    </a>
                </div>

                <?= $success_msg ?>

                <form method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" class="form-control glass-input <?= !empty($name_err) ? 'is-invalid' : '' ?>"
                            id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                        <?php if (!empty($name_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($name_err) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">အီးမေးလ်</label>
                        <input type="email" class="form-control glass-input <?= !empty($email_err) ? 'is-invalid' : '' ?>"
                            id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        <?php if (!empty($email_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($email_err) ?></div>
                        <?php endif; ?>
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

                    <div class="mb-3">
                        <label for="role" class="form-label">အခန်းကဏ္ဍ</label>
                        <div class="role-selector">
                            <div class="role-option <?= $role == 0 ? 'active' : '' ?>" data-value="0">
                                <i class="fas fa-user"></i>
                                <span>အသုံးပြုသူ</span>
                            </div>
                            <div class="role-option <?= $role == 1 ? 'active' : '' ?>" data-value="1">
                                <i class="fas fa-user-shield"></i>
                                <span>အက်ဒမင်</span>
                            </div>
                        </div>
                        <input type="hidden" name="role" id="role" value="<?= $role ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="user_list.php" class="btn btn-secondary">ပယ်ဖျက်မည်</a>
                        <button type="submit" name="submit" class="btn btn-primary fw-bold">
                    <i class="bi bi-check-circle me-2"></i>ပြင်ဆင်မည်
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

    .form-select.glass-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e") !important;
    }

    .role-selector {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }

    .role-option {
        flex: 1;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        backdrop-filter: blur(8px);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .role-option:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .role-option.active {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    }

    .role-option i {
        font-size: 24px;
        color: #333;
    }

    .role-option.active i {
        color: #0d6efd;
    }

    .role-option span {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .role-option.active span {
        color: #0d6efd;
    }

    .profile-image-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleOptions = document.querySelectorAll('.role-option');
        const roleInput = document.getElementById('role');

        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                roleOptions.forEach(opt => opt.classList.remove('active'));

                // Add active class to clicked option
                this.classList.add('active');

                // Update hidden input value
                roleInput.value = this.getAttribute('data-value');
            });
        });
    });
</script>

<?php require_once('../layout/footer.php'); ?>