<?php
require_once('../require/check_auth.php');
check_auth(0);
require_once('../layout/header.php');
require_once('../require/db.php');

$user_id = $_SESSION['user_id'];

// Fetch latest survey for this user
$survey_sql = "SELECT * FROM `user_surveys` WHERE `user_id` = '$user_id' ORDER BY created_at DESC LIMIT 1";
$survey_result = $mysqli->query($survey_sql);
$survey = $survey_result->fetch_assoc();
$surve_id = $survey['id'];

// Check if a recommendation already exists
$rec_sql = "SELECT r.*, m.name AS meal_plan_name, m.description 
            FROM user_diet_recommendations r
            JOIN meal_plans m ON r.meal_plan_id = m.id
            WHERE r.user_id = '$user_id' AND r.survey_id = '$surve_id'";
$rec_result = $mysqli->query($rec_sql);

if ($rec_result->num_rows > 0) {
    $recommendation = $rec_result->fetch_assoc();
} else {
    // No recommendation yet: find a plan matching goal
    $goal = $survey['goal'];
    $plan_sql = "SELECT * FROM meal_plans WHERE goal_type = '$goal' LIMIT 1";
    $plan_result = $mysqli->query($plan_sql);
    $plan = $plan_result->fetch_assoc();

    if ($plan) {
        // Save recommendation
        $plan_id = $plan['id'];
        $survey_id = $survey['id'];
        $insert_sql = "INSERT INTO user_diet_recommendations (user_id, survey_id, meal_plan_id) 
                       VALUES ('$user_id', '$survey_id', '$plan_id')";
        $mysqli->query($insert_sql);

        $recommendation = $plan;
        $recommendation['meal_plan_name'] = $plan['name'];
        $recommendation['description'] = $plan['description'];
    } else {
        $recommendation = null;
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center p-3">
        <div class="col-md-10">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">သင့်အတွက် အစားအသောက်အစီအစဉ်</h3>

                <?php if ($recommendation): ?>
                    <h4 class="mb-2"><?= htmlspecialchars($recommendation['meal_plan_name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($recommendation['description']) ?></p>

                    <?php
                    $plan_id = $recommendation['meal_plan_id'];
                    $meals_sql = "SELECT m.* FROM meal_plan_meals mp 
                                  JOIN meals m ON mp.meal_id = m.id 
                                  WHERE mp.meal_plan_id = '$plan_id'";
                    $meals_result = $mysqli->query($meals_sql);
                    while ($meal = $meals_result->fetch_assoc()):
                    ?>
                        <div class="mt-4 mb-3 glass-panel p-3">
                            <h5 class="fw-bold"><?= htmlspecialchars($meal['name']) ?> (<?= $meal['meal_type'] ?>)</h5>
                            <p><?= htmlspecialchars($meal['description']) ?></p>

                            <table class="table glass-table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>အစားအစာ</th>

                                        <th>ယူနစ်</th>
                                        <th>ကယ်လိုရီ</th>
                                        <th>ပရိုတိန်း (ဂရမ်)</th>
                                        <th>ကာဗိုဟိုဒရိုက်(ဂရမ်)</th>
                                        <th>အဆီ (ဂရမ်)</th>
                                        <th>အရွယ်အစား</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $meal_id = $meal['id'];
                                    $foods_sql = "SELECT f.*, mf.quantity 
                                                  FROM meal_foods mf 
                                                  JOIN foods f ON mf.food_id = f.id 
                                                  WHERE mf.meal_id = '$meal_id'";
                                    $foods_result = $mysqli->query($foods_sql);

                                    while ($food = $foods_result->fetch_assoc()):
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($food['name']) ?></td>
                                            <td><?= htmlspecialchars($food['unit']) ?></td>
                                            <td><?= htmlspecialchars($food['quantity']) ?></td>
                                            <td><?= $food['calories'] * $food['quantity'] ?></td>
                                            <td><?= $food['protein'] * $food['quantity'] ?></td>
                                            <td><?= $food['carbs'] * $food['quantity'] ?></td>
                                            <td><?= $food['fat'] * $food['quantity'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-warning">သင့်ရည်ရွယ်ချက်အတွက် အစားအသောက်အစီအစဉ် မတွေ့ရှိရပါ။</div>
                <?php endif; ?>
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

    .glass-table th,
    .glass-table td {
        background: rgba(255, 255, 255, 0.10) !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
    }
</style>

<?php require_once('../layout/footer.php'); ?>

<script>

</script>