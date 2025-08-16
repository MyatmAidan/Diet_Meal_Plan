<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$error = false;
$success_msg = '';
$name_err = $calories_err = $protein_err = $carbs_err = $fat_err = $serving_size_err = $unit_err = '';

// Get food ID from URL
$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($food_id <= 0) {
    header("Location: food_list.php");
    exit();
}

// Fetch food data
$stmt = $mysqli->prepare("SELECT * FROM foods WHERE id = ?");
$stmt->bind_param("i", $food_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: food_list.php");
    exit();
}

$food = $result->fetch_assoc();

// Initialize form data
$name = $food['name'];
$calories = $food['calories'];
$protein = $food['protein'];
$carbs = $food['carbs'];
$fat = $food['fat'];
$serving_size = $food['serving_size'];
$unit = $food['unit'];

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $calories = trim($_POST['calories']);
    $protein = trim($_POST['protein']);
    $carbs = trim($_POST['carbs']);
    $fat = trim($_POST['fat']);
    $serving_size = trim($_POST['serving_size']);
    $unit = trim($_POST['unit']);

    // Validation
    if (empty($name)) {
        $error = true;
        $name_err = "အမည်ထည့်သွင်းပါ";
    }

    if (empty($calories) || !is_numeric($calories) || $calories < 0) {
        $error = true;
        $calories_err = "မှန်ကန်သောကလိုရီပမာဏထည့်သွင်းပါ";
    }

    if (empty($protein) || !is_numeric($protein) || $protein < 0) {
        $error = true;
        $protein_err = "မှန်ကန်သောပရိုတိန်းပမာဏထည့်သွင်းပါ";
    }

    if (empty($carbs) || !is_numeric($carbs) || $carbs < 0) {
        $error = true;
        $carbs_err = "မှန်ကန်သောကာဗိုဟိုက်ဒြိတ်ပမာဏထည့်သွင်းပါ";
    }

    if (empty($fat) || !is_numeric($fat) || $fat < 0) {
        $error = true;
        $fat_err = "မှန်ကန်သောအဆီပမာဏထည့်သွင်းပါ";
    }

    if (empty($serving_size) || !is_numeric($serving_size) || $serving_size <= 0) {
        $error = true;
        $serving_size_err = "မှန်ကန်သောပမာဏထည့်သွင်းပါ";
    }

    if (empty($unit)) {
        $error = true;
        $unit_err = "ယူနစ်ထည့်သွင်းပါ";
    }

    if (!$error) {
        $stmt = $mysqli->prepare("UPDATE foods SET name = ?, calories = ?, protein = ?, carbs = ?, fat = ?, serving_size = ?, unit = ? WHERE id = ?");
        $stmt->bind_param("sdddddsi", $name, $calories, $protein, $carbs, $fat, $serving_size, $unit, $food_id);

        if ($stmt->execute()) {
            header("Location: food_list.php?msg=updated");
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
                    <a href="food_list.php" class="btn btn-secondary btn-sm">
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
                        <label for="calories" class="form-label">ကလိုရီ</label>
                        <input type="number" step="0.01" class="form-control glass-input <?= !empty($calories_err) ? 'is-invalid' : '' ?>" 
                               id="calories" name="calories" value="<?= htmlspecialchars($calories) ?>" required>
                        <?php if (!empty($calories_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($calories_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="protein" class="form-label">ပရိုတိန်း (g)</label>
                        <input type="number" step="0.01" class="form-control glass-input <?= !empty($protein_err) ? 'is-invalid' : '' ?>" 
                               id="protein" name="protein" value="<?= htmlspecialchars($protein) ?>" required>
                        <?php if (!empty($protein_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($protein_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="carbs" class="form-label">ကာဗိုဟိုက်ဒြိတ် (g)</label>
                        <input type="number" step="0.01" class="form-control glass-input <?= !empty($carbs_err) ? 'is-invalid' : '' ?>" 
                               id="carbs" name="carbs" value="<?= htmlspecialchars($carbs) ?>" required>
                        <?php if (!empty($carbs_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($carbs_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fat" class="form-label">အဆီ (g)</label>
                        <input type="number" step="0.01" class="form-control glass-input <?= !empty($fat_err) ? 'is-invalid' : '' ?>" 
                               id="fat" name="fat" value="<?= htmlspecialchars($fat) ?>" required>
                        <?php if (!empty($fat_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($fat_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="serving_size" class="form-label">ပမာဏ</label>
                        <input type="number" step="0.01" class="form-control glass-input <?= !empty($serving_size_err) ? 'is-invalid' : '' ?>" 
                               id="serving_size" name="serving_size" value="<?= htmlspecialchars($serving_size) ?>" required>
                        <?php if (!empty($serving_size_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($serving_size_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="unit" class="form-label">ယူနစ်</label>
                        <input type="text" class="form-control glass-input <?= !empty($unit_err) ? 'is-invalid' : '' ?>" 
                               id="unit" name="unit" value="<?= htmlspecialchars($unit) ?>" required>
                        <?php if (!empty($unit_err)): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($unit_err) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="food_list.php" class="btn btn-secondary">ပယ်ဖျက်မည်</a>
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
</style>

<?php require_once('../layout/footer.php'); ?>
