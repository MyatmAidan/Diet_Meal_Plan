<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$error = false;
$success_msg = '';
$name_err = $meal_type_err = $description_err = '';

// Get meal ID from URL
$meal_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($meal_id <= 0) {
    header("Location: meal_list.php");
    exit();
}

// Fetch meal data
$stmt = $mysqli->prepare("SELECT * FROM meals WHERE id = ?");
$stmt->bind_param("i", $meal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: meal_list.php");
    exit();
}

$meal = $result->fetch_assoc();

// Initialize form data
$name = $meal['name'];
$meal_type = $meal['meal_type'];
$description = $meal['description'];

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $meal_type = trim($_POST['meal_type']);
    $description = trim($_POST['description']);

    // Validation
    if (empty($name)) {
        $error = true;
        $name_err = "အမည်ထည့်သွင်းပါ";
    }

    if (empty($meal_type)) {
        $error = true;
        $meal_type_err = "အစားအစာအမျိုးအစားရွေးချယ်ပါ";
    }

    if (empty($description)) {
        $error = true;
        $description_err = "ပါဝင်ပစ္စည်းများထည့်သွင်းပါ";
    }

    if (!$error) {
        $stmt = $mysqli->prepare("UPDATE meals SET name = ?, meal_type = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $meal_type, $description, $meal_id);

        if ($stmt->execute()) {
            header("Location: meal_list.php?msg=updated");
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
                    <h3 class="fw-bold mb-0">အစားအစာပြင်ဆင်ခြင်း</h3>
                    <a href="meal_list.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ပြန်သွားမည်
                    </a>
                </div>
                
                <?= $success_msg ?>
                
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" class="form-control glass-input <?= !empty($name_err) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                        <?php if (!empty($name_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($name_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="meal_type" class="form-label">အစားအစာအမျိုးအစား</label>
                        <div class="meal-type-selector">
                            <div class="meal-type-option <?= $meal_type == 'breakfast' ? 'active' : '' ?>" data-value="breakfast">
                                <i class="fas fa-sun"></i>
                                <span>နံနက်စာ</span>
                            </div>
                            <div class="meal-type-option <?= $meal_type == 'lunch' ? 'active' : '' ?>" data-value="lunch">
                                <i class="fas fa-cloud-sun"></i>
                                <span>နေ့လည်စာ</span>
                            </div>
                            <div class="meal-type-option <?= $meal_type == 'dinner' ? 'active' : '' ?>" data-value="dinner">
                                <i class="fas fa-moon"></i>
                                <span>ညစာ</span>
                            </div>
                            <div class="meal-type-option <?= $meal_type == 'snack' ? 'active' : '' ?>" data-value="snack">
                                <i class="fas fa-cookie-bite"></i>
                                <span>Snack</span>
                            </div>
                        </div>
                        <input type="hidden" name="meal_type" id="meal_type" value="<?= $meal_type ?>" required>
                        <?php if (!empty($meal_type_err)): ?>
                            <div class="text-danger small mt-1"><?= htmlspecialchars($meal_type_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">ပါဝင်ပစ္စည်းများ</label>
                        <textarea class="form-control glass-input <?= !empty($description_err) ? 'is-invalid' : '' ?>" 
                                  id="description" name="description" rows="3" required><?= htmlspecialchars($description) ?></textarea>
                        <?php if (!empty($description_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($description_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="meal_list.php" class="btn btn-secondary">ပယ်ဖျက်မည်</a>
                        <button type="submit" name="submit" class="btn btn-primary fw-bold">ပြင်ဆင်မည်</button>
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

    .meal-type-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 8px;
    }

    .meal-type-option {
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

    .meal-type-option:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .meal-type-option.active {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    }

    .meal-type-option i {
        font-size: 20px;
        color: #333;
    }

    .meal-type-option.active i {
        color: #0d6efd;
    }

    .meal-type-option span {
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .meal-type-option.active span {
        color: #0d6efd;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mealTypeOptions = document.querySelectorAll('.meal-type-option');
    const mealTypeInput = document.getElementById('meal_type');

    mealTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            mealTypeOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Update hidden input value
            mealTypeInput.value = this.getAttribute('data-value');
        });
    });
});
</script>

<?php require_once('../layout/footer.php'); ?>
