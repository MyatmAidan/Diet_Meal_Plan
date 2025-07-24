<?php
require_once('../require/check_auth.php');
check_auth(0);
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
                <h3 class="fw-bold mb-2">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?> 👋</h3>
                <p class="text-muted mb-0">Here’s your current health overview and plan.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="glass-panel p-4">
                        <h5 class="fw-bold mb-3">🎯 Your Goal</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Goal:</strong> <?= ucfirst($survey['goal']) ?></li>
                            <li><strong>BMR:</strong> <?= round($survey['bmr']) ?> kcal/day</li>
                            <li><strong>Age:</strong> <?= $survey['age'] ?></li>
                            <li><strong>Gender:</strong> <?= ucfirst($survey['gender']) ?></li>
                            <li><strong>Height:</strong> <?= $survey['height'] ?> cm</li>
                            <li><strong>Weight:</strong> <?= $survey['weight'] ?> kg</li>
                            <li><strong>Activity:</strong> <?= ucfirst($survey['activity_level']) ?></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="glass-panel p-4">
                        <h5 class="fw-bold mb-3">📈 Progress</h5>
                        <?php if ($progress): ?>
                            <p class="mb-1"><strong>Date:</strong> <?= $progress['record_date'] ?></p>
                            <p class="mb-1"><strong>Weight:</strong> <?= $progress['weight'] ?> kg</p>
                            <p class="mb-1"><strong>Notes:</strong> <?= htmlspecialchars($progress['notes']) ?></p>
                        <?php else: ?>
                            <p class="text-muted">No progress recorded yet.</p>
                        <?php endif; ?>
                        <a href="progress.php" class="btn btn-outline-light btn-sm mt-2">Update Progress</a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="glass-panel p-4">
                        <h5 class="fw-bold mb-3">🍽️ Recommended Meal Plan</h5>
                        <?php if ($plan): ?>
                            <h6><?= htmlspecialchars($plan['plan_name']) ?></h6>
                            <p class="mb-0"><?= htmlspecialchars($plan['description']) ?></p>
                        <?php else: ?>
                            <p class="text-muted">Meal plan not yet generated.</p>
                        <?php endif; ?>
                        <a href="planner.php" class="btn btn-success btn-sm mt-2">View My Plan</a>
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