<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');

$meal_plan_id = $meal_id = '';
$create_msg = '';

// Fetch meal plans and meals for dropdowns
$meal_plans_result = $mysqli->query("SELECT id, name FROM meal_plans");
$meals_result = $mysqli->query("SELECT id, name FROM meals");

if (isset($_POST['submit'])) {
    $meal_plan_id = trim($_POST['meal_plan_id']);
    $meal_id = trim($_POST['meal_id']);
    if ($meal_plan_id && $meal_id) {
        $sql = "INSERT INTO `meal_plan_meals` (`meal_plan_id`, `meal_id`) VALUES ('$meal_plan_id', '$meal_id')";
        $result = $mysqli->query($sql);
        if ($result) {
            header("Location: meal_plan_meals_list.php?msg=created");
            exit();
        } else {
            $create_msg = '<div class=\"alert alert-danger mb-3\">Error adding meal to plan.</div>';
        }
    } else {
        $create_msg = '<div class=\"alert alert-warning mb-3\">All fields are required.</div>';
    }
}
ob_end_flush();
?>
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">Add Meal to Meal Plan</h3>
                <?php echo $create_msg; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="meal_plan_id" class="form-label">Meal Plan</label>
                        <select class="form-select glass-input" id="meal_plan_id" name="meal_plan_id" required>
                            <option value="">Select Meal Plan</option>
                            <?php if ($meal_plans_result && $meal_plans_result->num_rows > 0):
                                while ($row = $meal_plans_result->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= $meal_plan_id == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                            <?php endwhile;
                            endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="meal_id" class="form-label">Meal</label>
                        <select class="form-select glass-input" id="meal_id" name="meal_id" required>
                            <option value="">Select Meal</option>
                            <?php if ($meals_result && $meals_result->num_rows > 0):
                                // Reset pointer in case of previous fetch
                                $meals_result->data_seek(0);
                                while ($row = $meals_result->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= $meal_id == $row['id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                            <?php endwhile;
                            endif; ?>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="meal_plan_meals_list.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-success fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>Add
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
</style>
<?php require_once('../layout/footer.php'); ?>