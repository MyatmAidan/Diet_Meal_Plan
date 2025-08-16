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
        $age_err = "အသက်မှန်ကန်သော တန်ဖိုးကို ထည့်ပါ။";
    }
    if (!$gender) {
        $error = true;
        $gender_err = "ကျား/မ ကို ရွေးပါ။";
    }
    if ($weight <= 0) {
        $error = true;
        $weight_err = "အလေးချိန်မှန်ကန်သော တန်ဖိုးကို ထည့်ပါ။";
    }
    if ($height <= 0) {
        $error = true;
        $height_err = "အမြင့်မှန်ကန်သော တန်ဖိုးကို ထည့်ပါ။";
    }
    if (!$activity_level) {
        $error = true;
        $activity_level_err = "လှုပ်ရှားမှုအဆင့်ကို ရွေးပါ။";
    }
    if (!$goal) {
        $error = true;
        $goal_err = "ရည်ရွယ်ချက်ကို ရွေးပါ။";
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
            $survey_msg = '<div class="alert alert-danger">စစ်တမ်းကို သိမ်းဆည်းရာတွင် ပြဿနာဖြစ်ပွားခဲ့သည်။</div>';
        }
    } else {
        $survey_msg = '<div class="alert alert-warning">ကျေးဇူးပြု၍ အမှားများကို ပြင်ဆင်ပါ။</div>';
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center p-3">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">ကျန်းမာရေး စစ်တမ်း ပြုပြင်ရန်</h3>
                <?= $survey_msg ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">အသက်</label>
                        <input type="number" name="age" class="form-control glass-input" value="<?= htmlspecialchars($age) ?>">
                        <small class="text-danger"><?= $age_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ကျား/မ</label>
                        <select name="gender" class="form-select glass-input">
                            <option value="">ရွေးပါ</option>
                            <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>ကျား</option>
                            <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>မ</option>
                        </select>
                        <small class="text-danger"><?= $gender_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">အလေးချိန် (ကီလိုဂရမ်)</label>
                        <input type="number" step="0.1" name="weight" class="form-control glass-input" value="<?= htmlspecialchars($weight) ?>">
                        <small class="text-danger"><?= $weight_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">အမြင့် (စင်တီမီတာ)</label>
                        <input type="number" step="0.1" name="height" class="form-control glass-input" value="<?= htmlspecialchars($height) ?>">
                        <small class="text-danger"><?= $height_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">လှုပ်ရှားမှုအဆင့်</label>
                        <select name="activity_level" class="form-select glass-input">
                            <option value="">ရွေးပါ</option>
                            <option value="sedentary" <?= $activity_level === 'sedentary' ? 'selected' : '' ?>>မလှုပ်ရှားသေးသော</option>
                            <option value="light" <?= $activity_level === 'light' ? 'selected' : '' ?>>နည်းနည်းလှုပ်ရှားသော</option>
                            <option value="moderate" <?= $activity_level === 'moderate' ? 'selected' : '' ?>>အလယ်အလတ်လှုပ်ရှားသော</option>
                            <option value="active" <?= $activity_level === 'active' ? 'selected' : '' ?>>အရမ်းလှုပ်ရှားသော</option>
                        </select>
                        <small class="text-danger"><?= $activity_level_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ရည်ရွယ်ချက်</label>
                        <select name="goal" class="form-select glass-input">
                            <option value="">ရွေးပါ</option>
                            <option value="lose" <?= $goal === 'lose' ? 'selected' : '' ?>>အလေးချိန်လျှော့ချရန်</option>
                            <option value="maintain" <?= $goal === 'maintain' ? 'selected' : '' ?>>လက်ရှိအလေးချိန်ထိန်းရန်</option>
                            <option value="gain" <?= $goal === 'gain' ? 'selected' : '' ?>>အလေးချိန်တိုးရန်</option>
                            <option value="gain" <?= $goal === 'gain' ? 'selected' : '' ?>>ကြွက်သားတိုးရန်</option>
                            
                        </select>
                        <small class="text-danger"><?= $goal_err ?></small>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="submit" class="btn btn-success">စစ်တမ်းသိမ်းမည်</button>
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
        -webkit-backdrop-filter: blur(8px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #222;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
    }
</style>

<?php require_once('../layout/footer.php'); ?>