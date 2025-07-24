<?php
require_once('../require/check_auth.php');
check_auth(0);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');
$error = false;
$survey_msg =
    $age =
    $gender =
    $weight =
    $height =
    $activity_level =
    $goal =
    $age_err =
    $gender_err =
    $weight_err =
    $height_err =
    $activity_level_err =
    $goal_err = '';

if (isset($_POST['submit'])) {
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $weight = trim($_POST['weight']);
    $height = trim($_POST['height']);
    $activity_level = trim($_POST['activity_level']);
    $goal = trim($_POST['goal']);

    if ($age == "" || $age <= 0) {
        $error = true;
        $age_err = "Please enter a valid age";
    }

    if ($gender == "") {
        $error = true;
        $gender_err = "Please select gender";
    }

    if ($weight == "" || $weight <= 0) {
        $error = true;
        $weight_err = "Please enter valid weight";
    }

    if ($height == "" || $height <= 0) {
        $error = true;
        $height_err = "Please enter valid height";
    }

    if ($activity_level == "") {
        $error = true;
        $activity_level_err = "Please select activity level";
    }

    if ($goal == "") {
        $error = true;
        $goal_err = "Please select goal";
    }


    if (!$error) {
        // Calculate BMR
        $user_id = $_SESSION['user_id'];
        $bmr = ($gender === 'male')
            ? 10 * $weight + 6.25 * $height - 5 * $age + 5
            : 10 * $weight + 6.25 * $height - 5 * $age - 161;

        $sql = "INSERT INTO `user_surveys` (`user_id`, `age`, `gender`, `weight`, `height`, `activity_level`, `goal`, `bmr`) 
                VALUES ('$user_id', '$age', '$gender', '$weight', '$height', '$activity_level', '$goal', '$bmr')";
        $result = $mysqli->query($sql);

        if ($result) {
            header("Location: " . $user_url . "planner.php?msg=created");
            exit();
        } else {
            $survey_msg = '<div class="alert alert-danger mb-3">Error saving survey.</div>';
        }
    } else {
        $survey_msg = '<div class="alert alert-warning mb-3">Please fix the errors below.</div>';
    }
}
ob_end_flush();
?>

<div class="container-fluid">
    <div class="row justify-content-center p-3">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">Health Survey</h3>
                <?= $survey_msg ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control glass-input" id="age" name="age" value="<?= htmlspecialchars($age) ?>">
                        <?php if (!empty($age_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($age_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select glass-input" name="gender" id="gender">
                            <option value="">Select</option>
                            <option value="male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
                        </select>
                        <?php if (!empty($gender_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($gender_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" class="form-control glass-input" id="weight" name="weight" value="<?= htmlspecialchars($weight) ?>">
                        <?php if (!empty($weight_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($weight_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="height" class="form-label">Height (cm)</label>
                        <input type="number" step="0.1" class="form-control glass-input" id="height" name="height" value="<?= htmlspecialchars($height) ?>">
                        <?php if (!empty($height_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($height_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="activity_level" class="form-label">Activity Level</label>
                        <select class="form-select glass-input" name="activity_level" id="activity_level">
                            <option value="">Select</option>
                            <option value="sedentary" <?= $activity_level == 'sedentary' ? 'selected' : '' ?>>Sedentary</option>
                            <option value="light" <?= $activity_level == 'light' ? 'selected' : '' ?>>Lightly Active</option>
                            <option value="moderate" <?= $activity_level == 'moderate' ? 'selected' : '' ?>>Moderately Active</option>
                            <option value="active" <?= $activity_level == 'active' ? 'selected' : '' ?>>Very Active</option>
                        </select>
                        <?php if (!empty($activity_level_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($activity_level_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="goal" class="form-label">Goal</label>
                        <select class="form-select glass-input" name="goal" id="goal">
                            <option value="">Select</option>
                            <option value="lose" <?= $goal == 'lose' ? 'selected' : '' ?>>Lose Weight</option>
                            <option value="maintain" <?= $goal == 'maintain' ? 'selected' : '' ?>>Maintain Weight</option>
                            <option value="gain" <?= $goal == 'gain' ? 'selected' : '' ?>>Gain Weight</option>
                        </select>
                        <?php if (!empty($goal_err)): ?>
                            <div class="text-danger small"><?= htmlspecialchars($goal_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="submit" class="btn btn-success fw-bold">Save Survey</button>
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