<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$error = false;
$success_msg = '';
$name_err = $description_err = $goal_type_err = '';

// Get meal plan ID from URL
$meal_plan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($meal_plan_id <= 0) {
    header("Location: meal_plan_list.php");
    exit();
}

// Fetch meal plan data
$stmt = $mysqli->prepare("SELECT * FROM meal_plans WHERE id = ?");
$stmt->bind_param("i", $meal_plan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: meal_plan_list.php");
    exit();
}

$meal_plan = $result->fetch_assoc();

// Initialize form data
$name = $meal_plan['name'];
$description = $meal_plan['description'];
$goal_type = $meal_plan['goal_type'];

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $goal_type = trim($_POST['goal_type']);

    // Validation
    if (empty($name)) {
        $error = true;
        $name_err = "အမည်ထည့်သွင်းပါ";
    }

    if (empty($description)) {
        $error = true;
        $description_err = "ဖော်ပြချက်ထည့်သွင်းပါ";
    }

    if (empty($goal_type)) {
        $error = true;
        $goal_type_err = "ရည်ရွယ်ချက်ရွေးချယ်ပါ";
    }

    if (!$error) {
        $stmt = $mysqli->prepare("UPDATE meal_plans SET name = ?, description = ?, goal_type = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $description, $goal_type, $meal_plan_id);

        if ($stmt->execute()) {
            header("Location: meal_plan_list.php?msg=updated");
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
                    <h3 class="fw-bold mb-0">အစားအစာအစီအစဉ်ပြင်ဆင်ခြင်း</h3>
                    <a href="meal_plan_list.php" class="btn btn-secondary btn-sm">
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
                        <label for="description" class="form-label">ဖော်ပြချက်</label>
                        <textarea class="form-control glass-input <?= !empty($description_err) ? 'is-invalid' : '' ?>" 
                                  id="description" name="description" rows="3" required><?= htmlspecialchars($description) ?></textarea>
                        <?php if (!empty($description_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($description_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="goal_type" class="form-label">ရည်ရွယ်ချက်</label>
                        <div class="goal-type-selector">
                            <div class="goal-type-option <?= $goal_type == 'lose' ? 'active' : '' ?>" data-value="lose">
                                <i class="fas fa-arrow-down"></i>
                                <span>အလေးချိန်လျှော့ချရန်</span>
                            </div>
                            <div class="goal-type-option <?= $goal_type == 'maintain' ? 'active' : '' ?>" data-value="maintain">
                                <i class="fas fa-equals"></i>
                                <span>အလေးချိန်ထိန်းရန်</span>
                            </div>
                            <div class="goal-type-option <?= $goal_type == 'gain' ? 'active' : '' ?>" data-value="gain">
                                <i class="fas fa-arrow-up"></i>
                                <span>အလေးချိန်တိုးရန်</span>
                            </div>
                        </div>
                        <input type="hidden" name="goal_type" id="goal_type" value="<?= $goal_type ?>" required>
                        <?php if (!empty($goal_type_err)): ?>
                            <div class="text-danger small mt-1"><?= htmlspecialchars($goal_type_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="meal_plan_list.php" class="btn btn-secondary">ပယ်ဖျက်မည်</a>
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

    .goal-type-selector {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 8px;
    }

    .goal-type-option {
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: left;
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .goal-type-option:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .goal-type-option.active {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    }

    .goal-type-option i {
        font-size: 18px;
        color: #333;
        min-width: 20px;
    }

    .goal-type-option.active i {
        color: #0d6efd;
    }

    .goal-type-option span {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .goal-type-option.active span {
        color: #0d6efd;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const goalTypeOptions = document.querySelectorAll('.goal-type-option');
    const goalTypeInput = document.getElementById('goal_type');

    goalTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            goalTypeOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Update hidden input value
            goalTypeInput.value = this.getAttribute('data-value');
        });
    });
});
</script>

<?php require_once('../layout/footer.php'); ?>
