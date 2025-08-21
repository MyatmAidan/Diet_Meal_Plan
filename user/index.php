<?php
require_once('../require/check_auth.php');
check_auth(0);
require_once('../require/i18n.php');
require_once('../layout/header.php');
require_once('../require/db.php');

$user_id = $_SESSION['user_id'];

// Get latest survey
$survey_sql = "SELECT * FROM user_surveys WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$survey_result = $mysqli->query($survey_sql);
$survey = $survey_result->fetch_assoc();

// Get latest progress
$progress_sql = "SELECT * FROM user_progress WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
$progress_result = $mysqli->query($progress_sql);
$progress = $progress_result->fetch_assoc();

// Get meal plan recommendation
$recommendation_sql = "
    SELECT mp.name AS plan_name, mp.description
    FROM user_diet_recommendations ur
    JOIN meal_plans mp ON ur.meal_plan_id = mp.id
    WHERE ur.user_id = $user_id
    ORDER BY ur.id DESC
    LIMIT 1
";
$recommendation_result = $mysqli->query($recommendation_sql);
$plan = $recommendation_result->fetch_assoc();
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass-panel p-4 mb-4">
                <h3 class="fw-bold mb-2"><?= __('မင်္ဂလာပါ၊') ?> <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h3>
                <p class="text-muted mb-0"><?= __('သင်၏လက်ရှိကျန်းမာရေးအခြေအနေနှင့် အစားအသောက်အစီအစဉ်ကို ဤနေရာတွင်ကြည့်နိုင်ပါသည်။') ?></p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="glass-panel p-4">
                        <h5 class="fw-bold mb-3"><?= __('🎯 သင်၏ ကိုယ်ရေးအချက်အလက်') ?></h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong><?= __('ရည်ရွယ်ချက်:') ?></strong> <?= ucfirst($survey['goal']) ?></li>
                            <li><strong>BMI:</strong> <?= round($survey['bmr']) ?> ကီလိုကယ်လိုရီ</li>
                            <li><strong><?= __('အသက်:') ?></strong> <?= $survey['age'] ?> နှစ်</li>
                            <li><strong><?= __('ကျား/မ:') ?></strong> <?= ucfirst($survey['gender']) ?></li>
                            <li><strong><?= __('အမြင့်:') ?></strong> <?= $survey['height'] ?> စင်တီမီတာ</li>
                            <li><strong><?= __('အလေးချိန်:') ?></strong> <?= $survey['weight'] ?> ကီလိုဂရမ်</li>
                            <li><strong><?= __('လှုပ်ရှားမှုအဆင့်:') ?></strong> <?= ucfirst($survey['activity_level']) ?></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="glass-panel p-4">
                        <h5 class="fw-bold mb-3"><?= __('📈 တိုးတက်မှု') ?></h5>
                        <?php if ($progress): ?>
                            <p class="mb-1"><strong><?= __('ရက်စွဲ:') ?></strong> <?= $progress['record_date'] ?></p>
                            <p class="mb-1"><strong><?= __('ကိုယ်အလေးချိန်:') ?></strong> <?= $progress['weight'] ?> ကီလိုဂရမ်</p>
                            <p class="mb-1"><strong><?= __('မှတ်ချက်:') ?></strong> <?= htmlspecialchars($progress['notes']) ?></p>
                        <?php else: ?>
                            <p class="text-muted"><?= __('မည်သည့်တိုးတက်မှုမှတ်တမ်းမျှ မရှိသေးပါ။') ?></p>
                        <?php endif; ?>
                        <a href="progress.php" class="btn btn-outline-light btn-sm mt-2"><?= __('တိုးတက်မှု အသစ်ထည့်ရန်') ?></a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="glass-panel p-4">
                        <h5 class="fw-bold mb-3"><?= __('🍽️ အကြံပြုထားသော အစားအသောက်အစီအစဉ်') ?></h5>
                        <?php if ($plan): ?>
                            <h6><?= htmlspecialchars($plan['plan_name']) ?></h6>
                            <p class="mb-0"><?= htmlspecialchars($plan['description']) ?></p>
                        <?php else: ?>
                            <p class="text-muted"><?= __('Meal plan မရှိသေးပါ။') ?></p>
                        <?php endif; ?>
                        <a href="planner.php" class="btn btn-success btn-sm mt-2"><?= __('ကျွန်ုပ်၏အစီအစဉ်ကြည့်ရန်') ?></a>
                    </div>
                </div>
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
        color: #fff;
    }
</style>

<?php require_once('../layout/footer.php'); ?>