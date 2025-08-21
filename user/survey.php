<?php
require_once('../require/check_auth.php');
check_auth(0);
require_once('../require/i18n.php');
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
        $age_err = __("အသက်မှန်ကန်သော တန်ဖိုးကို ထည့်ပါ။");
    }
    if (!$gender) {
        $error = true;
        $gender_err = __("ကျား/မ ကို ရွေးပါ။");
    }
    if ($weight <= 0) {
        $error = true;
        $weight_err = __("အလေးချိန်မှန်ကန်သော တန်ဖိုးကို ထည့်ပါ။");
    }
    if ($height <= 0) {
        $error = true;
        $height_err = __("အမြင့်မှန်ကန်သော တန်ဖိုးကို ထည့်ပါ။");
    }
    if (!$activity_level) {
        $error = true;
        $activity_level_err = __("လှုပ်ရှားမှုအဆင့်ကို ရွေးပါ။");
    }
    if (!$goal) {
        $error = true;
        $goal_err = __("ရည်ရွယ်ချက်ကို ရွေးပါ။");
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
            $survey_msg = '<div class="alert alert-danger">' . __("စစ်တမ်းကို သိမ်းဆည်းရာတွင် ပြဿနာဖြစ်ပွားခဲ့သည်။") . '</div>';
        }
    } else {
        $survey_msg = '<div class="alert alert-warning">' . __("ကျေးဇူးပြု၍ အမှားများကို ပြင်ဆင်ပါ။") . '</div>';
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center p-3">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3"><?= __('ကျန်းမာရေး စစ်တမ်း') ?> <?= __('စစ်တမ်း ပြင်ဆင်မည်') ?></h3>
                <?= $survey_msg ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label"><?= __('အသက်') ?></label>
                        <input type="number" name="age" class="form-control glass-input" value="<?= htmlspecialchars($age) ?>">
                        <small class="text-danger"><?= $age_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('ကျား/မ') ?></label>
                        <select name="gender" class="form-select glass-input">
                            <option value=""><?= __('ရွေးပါ') ?></option>
                            <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>><?= __('ကျား') ?></option>
                            <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>><?= __('မ') ?></option>
                        </select>
                        <small class="text-danger"><?= $gender_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('ကိုယ်အလေးချိန် (ကီလိုဂရမ်)') ?></label>
                        <input type="number" step="0.1" name="weight" class="form-control glass-input" value="<?= htmlspecialchars($weight) ?>">
                        <small class="text-danger"><?= $weight_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('ကိုယ်ရေပမာဏ (စင်တီမီတာ)') ?></label>
                        <input type="number" step="0.1" name="height" class="form-control glass-input" value="<?= htmlspecialchars($height) ?>">
                        <small class="text-danger"><?= $height_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('လှုပ်ရှားမှုအဆင့်') ?></label>
                        <select name="activity_level" class="form-select glass-input">
                            <option value=""><?= __('ရွေးပါ') ?></option>
                            <option value="sedentary" <?= $activity_level === 'sedentary' ? 'selected' : '' ?>><?= __('အနည်းငယ်သာ လှုပ်ရှားသူ') ?></option>
                            <option value="light" <?= $activity_level === 'light' ? 'selected' : '' ?>><?= __('နည်းနည်းလှုပ်ရှားသူ') ?></option>
                            <option value="moderate" <?= $activity_level === 'moderate' ? 'selected' : '' ?>><?= __('အလယ်အလတ် လှုပ်ရှားသူ') ?></option>
                            <option value="active" <?= $activity_level === 'active' ? 'selected' : '' ?>><?= __('အလွန်လှုပ်ရှားသူ') ?></option>
                        </select>
                        <small class="text-danger"><?= $activity_level_err ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('ရည်ရွယ်ချက်') ?></label>
                        <select name="goal" class="form-select glass-input">
                            <option value=""><?= __('ရွေးပါ') ?></option>
                            <option value="lose" <?= $goal === 'lose' ? 'selected' : '' ?>><?= __('ကိုယ်အလေးချိန် လျှော့ချရန်') ?></option>
                            <option value="maintain" <?= $goal === 'maintain' ? 'selected' : '' ?>><?= __('ကျန်းမာရေး ထိန်းသိမ်းရန်') ?></option>
                            <option value="gain" <?= $goal === 'gain' ? 'selected' : '' ?>><?= __('ကိုယ်အလေးချိန် တိုးမြှင့်ရန်') ?></option>
                            <option value="muscle" <?= $goal === 'muscle' ? 'selected' : '' ?>><?= __('ကြွက်သား တိုးမြှင့်ရန်') ?></option>
                        </select>
                        <small class="text-danger"><?= $goal_err ?></small>
                    </div>
                    <div class="text-end">
                        <button type="submit" id="submit" name="submit" class="btn btn-success"><?= __('စစ်တမ်း ပေးပို့မည်') ?></button>
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

<script>
    $(document).ready(function() {

        $('#submit').on('click', function() {
            console.log("Survey page loaded");

            $.ajax({
                url: 'https://dummyjson.com/recipes',


            }).then(res => {
                console.log(res);
            }).catch(err => {
                console.error("Error fetching recipes:", err);
            })
        });
    });
</script>