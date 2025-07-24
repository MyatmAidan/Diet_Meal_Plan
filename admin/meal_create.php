<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');
$name = $meal_type = $description = '';
$create_msg = '';
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $meal_type = trim($_POST['meal_type']);
    $description = trim($_POST['description']);
    if ($name && $meal_type && $description) {
        $sql = "INSERT INTO `meals` (`name`, `meal_type`, `description`) VALUES ('$name', '$meal_type', '$description')";
        $result = $mysqli->query($sql);
        if ($result) {
            header("Location: $admin_url" . "meal_list.php?msg=Create Successfully!");
            exit();
        } else {
            $create_msg = '<div class=\"alert alert-danger mb-3\">Error: </div>';
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
                <h3 class="fw-bold mb-3">Create Meal</h3>
                <?php echo $create_msg; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control glass-input" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="meal_type" class="form-label">Meal Type</label>
                        <select class="form-select glass-input" id="meal_type" name="meal_type" required>
                            <option value="">Select type</option>
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="dinner">Dinner</option>
                            <option value="snack">Snack</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control glass-input" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="meal_list.php" class="btn btn-secondary">Cancel</a>
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