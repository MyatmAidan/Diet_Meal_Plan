<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');
$name = $calories = $protein = $carbs = $fat = $serving_size = $unit = '';
$create_msg = '';
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $calories = trim($_POST['calories']);
    $protein = trim($_POST['protein']);
    $carbs = trim($_POST['carbs']);
    $fat = trim($_POST['fat']);
    $serving_size = trim($_POST['serving_size']);
    $unit = trim($_POST['unit']);
    if ($name && $calories && $protein && $carbs && $fat && $serving_size && $unit) {
        $sql = "INSERT INTO `foods` (`name`, `calories`, `protein`, `carbs`, `fat`, `serving_size`, `unit`)
        VALUES ('$name', '$calories', '$protein', '$carbs', '$fat', '$serving_size', '$unit')";
        $result = $mysqli->query($sql);

        if ($result) {
            header("Location:  $admin_url" . "food_list.php?msg=created");
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
                <h3 class="fw-bold mb-3">Create Food</h3>
                <?php echo $create_msg; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control glass-input" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="calories" class="form-label">Calories</label>
                        <input type="number" step="0.01" class="form-control glass-input" id="calories" name="calories" required>
                    </div>
                    <div class="mb-3">
                        <label for="protein" class="form-label">Protein (g)</label>
                        <input type="number" step="0.01" class="form-control glass-input" id="protein" name="protein" required>
                    </div>
                    <div class="mb-3">
                        <label for="carbs" class="form-label">Carbs (g)</label>
                        <input type="number" step="0.01" class="form-control glass-input" id="carbs" name="carbs" required>
                    </div>
                    <div class="mb-3">
                        <label for="fat" class="form-label">Fat (g)</label>
                        <input type="number" step="0.01" class="form-control glass-input" id="fat" name="fat" required>
                    </div>
                    <div class="mb-3">
                        <label for="serving_size" class="form-label">Serving Size</label>
                        <input type="number" step="0.01" class="form-control glass-input" id="serving_size" name="serving_size" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control glass-input" id="unit" name="unit" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="food_list.php" class="btn btn-secondary">Cancel</a>
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