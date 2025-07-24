<?php
require_once('../require/check_auth.php');
check_auth(0);
require_once('../layout/header.php');
require_once('../require/db.php');

$user_id = $_SESSION['user_id'];
$error = false;
$survey_msg = '';
$age = $gender = $weight = $height = $activity_level = $goal = '';
$age_err = $gender_err = $weight_err = $height_err = $activity_level_err = $goal_err = '';

// Get latest survey to prefill
$latest = $mysqli->query("SELECT * FROM user_surveys WHERE user_id = $user_id ORDER BY id DESC LIMIT 1");
if ($latest && $latest->num_rows > 0) {
    $row = $latest->fetch_assoc();
    $age = $row['age'];
    $gender = $row['gender'];
    $weight = $row['weight'];
    $height = $row['height'];
    $activity_level = $row['activity_level'];
    $goal = $row['goal'];
}

if (isset($_POST['submit'])) {
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $weight = trim($_POST['weight']);
    $height = trim($_POST['height']);
    $activity_level = trim($_POST['activity_level']);
    $goal = trim($_POST['goal']);

    // Validation
    if ($age <= 0) {
        $error = true;
        $age_err = "Enter a valid age.";
    }
    if (!$gender) {
        $error = true;
        $gender_err = "Select gender.";
    }
    if ($weight <= 0) {
        $error = true;
        $weight_err = "Enter valid weight.";
    }
    if ($height <= 0) {
        $error = true;
        $height_err = "Enter valid height.";
    }
    if (!$activity_level) {
        $error = true;
        $activity_level_err = "Select activity level.";
    }
    if (!$goal) {
        $error = true;
        $goal_err = "Select goal.";
    }

    if (!$error) {
        // Calculate BMR
        $bmr = ($gender === 'male')
            ? 10 * $weight + 6.25 * $height - 5 * $age + 5
            : 10 * $weight + 6.25 * $height - 5 * $age - 161;

        // Save new record (no overwrite)
        $sql = "INSERT INTO user_surveys 
                (user_id, age, gender, weight, height, activity_level, goal, bmr) 
                VALUES 
                ('$user_id', '$age', '$gender', '$weight', '$height', '$activity_level', '$goal', '$bmr')";

        if ($mysqli->query($sql)) {
            header("Location: index.php?msg=Survey+Updated");
            exit();
        } else {
            $survey_msg = '<div class="alert alert-danger">Failed to save survey.</div>';
        }
    } else {
        $survey_msg = '<div class="alert alert-warning">Please fix the form errors.</div>';
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center p-3">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">Update Health Survey</h3>
                <?= $survey_msg ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control glass-input" value="<?= htmlspecialchars($age) ?>">
                        <small class="text-danger"><?= $age_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select glass-input">
                            <option value="">Select</option>
                            <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>Female</option>
                        </select>
                        <small class="text-danger"><?= $gender_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" class="form-control glass-input" value="<?= htmlspecialchars($weight) ?>">
                        <small class="text-danger"><?= $weight_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Height (cm)</label>
                        <input type="number" step="0.1" name="height" class="form-control glass-input" value="<?= htmlspecialchars($height) ?>">
                        <small class="text-danger"><?= $height_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Activity Level</label>
                        <select name="activity_level" class="form-select glass-input">
                            <option value="">Select</option>
                            <option value="sedentary" <?= $activity_level === 'sedentary' ? 'selected' : '' ?>>Sedentary</option>
                            <option value="light" <?= $activity_level === 'light' ? 'selected' : '' ?>>Lightly Active</option>
                            <option value="moderate" <?= $activity_level === 'moderate' ? 'selected' : '' ?>>Moderately Active</option>
                            <option value="active" <?= $activity_level === 'active' ? 'selected' : '' ?>>Very Active</option>
                        </select>
                        <small class="text-danger"><?= $activity_level_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Goal</label>
                        <select name="goal" class="form-select glass-input">
                            <option value="">Select</option>
                            <option value="lose" <?= $goal === 'lose' ? 'selected' : '' ?>>Lose Weight</option>
                            <option value="maintain" <?= $goal === 'maintain' ? 'selected' : '' ?>>Maintain Weight</option>
                            <option value="gain" <?= $goal === 'gain' ? 'selected' : '' ?>>Gain Weight</option>
                        </select>
                        <small class="text-danger"><?= $goal_err ?></small>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="submit" class="btn btn-success">Save Survey</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.18);
        backdrop-filter: blur(8px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #222;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }
</style>

<?php require_once('../layout/footer.php'); ?>