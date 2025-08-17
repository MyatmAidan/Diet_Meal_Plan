<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

// Query counts
$user_count = $mysqli->query("SELECT COUNT(*) AS `total` FROM `users`")->fetch_assoc()['total'];
$meal_plan_count = $mysqli->query("SELECT COUNT(*) AS total FROM meal_plans")->fetch_assoc()['total'];
$food_count = $mysqli->query("SELECT COUNT(*) AS total FROM foods")->fetch_assoc()['total'];
$progress_count = $mysqli->query("SELECT COUNT(*) AS total FROM user_progress")->fetch_assoc()['total'];


// Users per goal type (for chart)
$goals_result = $mysqli->query("
    SELECT goal, COUNT(*) AS total 
    FROM user_surveys 
    GROUP BY goal
");

$goals = [];
while ($row = $goals_result->fetch_assoc()) {
    $goals[] = $row;
}
?>

<div class="container-fluid p-3">

    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-panel p-4 text-center">
                <h2 class="fw-bold mb-2">ကြိုဆိုပါတယ် အက်မင်!</h2>
                <p class="text-secondary mb-0">သင့်အတွက် ကျန်းမာသောအစားအသောက်အစီအစဉ်စနစ်၏အကျဉ်းချုပ်ဖြစ်ပါသည်။</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="glass-panel p-3 text-center">
                <i class="bi bi-people fs-1 text-primary mb-2"></i>
                <h4 class="fw-bold"><?= $user_count ?></h4>
                <div class="text-secondary">အသုံးပြုသူများ</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel p-3 text-center">
                <i class="bi bi-journal-text fs-1 text-success mb-2"></i>
                <h4 class="fw-bold"><?= $meal_plan_count ?></h4>
                <div class="text-secondary">အစားအသောက်အစီအစဉ်</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel p-3 text-center">
                <i class="bi bi-egg-fried fs-1 text-warning mb-2"></i>
                <h4 class="fw-bold"><?= $food_count ?></h4>
                <div class="text-secondary">အစားအသောက်များ</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel p-3 text-center">
                <i class="bi bi-bar-chart-line fs-1 text-info mb-2"></i>
                <h4 class="fw-bold"><?= $progress_count ?></h4>
                <div class="text-secondary">တိုးတက်မှုမှတ်တမ်းများ</div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6 offset-md-3">
            <div class="glass-panel p-4">
                <h5 class="fw-bold mb-3 text-center">📊 အသုံးပြုသူ ရည်မှန်းချက် စီမံမှု</h5>
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="goalChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('goalChart').getContext('2d');
    const goalChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($goals, 'goal')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($goals, 'total')) ?>,
                backgroundColor: ['#198754', '#0d6efd', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            layout: {
                padding: 10
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 16,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once('../layout/footer.php') ?>