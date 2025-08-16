<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$meal_id = $food_id = $quantity = '';
$create_msg = '';

// Fetch meals and foods for dropdowns
$meals_result = $mysqli->query("SELECT id, name FROM meals");
$foods_result = $mysqli->query("SELECT id, name, unit FROM foods");

if (isset($_POST['submit'])) {
    $meal_id = trim($_POST['meal_id']);
    $food_id = trim($_POST['food_id']);
    $quantity = trim($_POST['quantity']);
    if ($meal_id && $food_id && $quantity) {
        $sql = "INSERT INTO `meal_foods` (`meal_id`, `food_id`, `quantity`) VALUES ('$meal_id', '$food_id', '$quantity')";
        $result = $mysqli->query($sql);
        if ($result) {
            header("Location: meal_food_list.php?msg=created");
            exit();
        } else {
            $create_msg = '<div class="alert alert-danger mb-3">Error adding meal food.</div>';
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
                <h3 class="fw-bold mb-3">Add Food to Meal</h3>
                <?php echo $create_msg; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="meal_id" class="form-label">Meal</label>
                        <select class="form-select glass-input" id="meal_id" name="meal_id" required>
                            <option value="">Select Meal</option>
                            <?php if ($meals_result && $meals_result->num_rows > 0):
                                while ($row = $meals_result->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= $meal_id == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                            <?php endwhile;
                            endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="food_id" class="form-label">Food</label>
                        <select class="form-select glass-input" id="food_id" name="food_id" required>
                            <option value="">Select Food</option>
                            <?php if ($foods_result && $foods_result->num_rows > 0):
                                // Reset pointer in case of previous fetch
                                $foods_result->data_seek(0);
                                while ($row = $foods_result->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= $food_id == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['unit']) ?>)</option>
                            <?php endwhile;
                            endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" step="0.01" class="form-control glass-input" id="quantity" name="quantity" value="<?= htmlspecialchars($quantity) ?>" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="meal_food_list.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-success fw-bold">Add</button>
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