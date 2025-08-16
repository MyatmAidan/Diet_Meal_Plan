<?php
require_once '../require/db.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true);

    // ─── 1. DELETE USER ─────────────────────────
    if (isset($payload['userId']) && !empty($payload['userId'])) {
        $userId = (int)$payload['userId'];

        $check = $mysqli->prepare("SELECT 1 FROM user_surveys WHERE user_id = ? UNION SELECT 1 FROM user_progress WHERE user_id = ? LIMIT 1");
        $check->bind_param("ii", $userId, $userId);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'ဤအသုံးပြုသူနှင့် ဆက်စပ်ဒေတာများရှိသဖြင့် ဖျက်လို့မရပါ။']);
            exit;
        }
        $check->close();

        $del = $mysqli->prepare("DELETE FROM users WHERE id = ?");
        $del->bind_param("i", $userId);
        $del->execute();

        echo json_encode(['status' => 'success', 'deletedId' => $userId, 'message' => 'အသုံးပြုသူကိုဖျက်ပြီးပါပြီ။']);
        exit;
    }

    // ─── 2. DELETE FOOD ─────────────────────────
    if (isset($payload['foodId']) && !empty($payload['foodId'])) {
        $foodId = (int)$payload['foodId'];

        $check = $mysqli->prepare("SELECT 1 FROM meal_foods WHERE food_id = ? LIMIT 1");
        $check->bind_param("i", $foodId);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'ဤအစားအစာသည် မီလ်များတွင်သုံးထားသောကြောင့် ဖျက်လို့မရပါ။']);
            exit;
        }

        $del = $mysqli->prepare("DELETE FROM foods WHERE id = ?");
        $del->bind_param("i", $foodId);
        $del->execute();

        echo json_encode(['status' => 'success', 'deletedId' => $foodId, 'message' => 'အစားအစာကိုဖျက်ပြီးပါပြီ။']);
        exit;
    }

    // ─── 3. DELETE MEAL ─────────────────────────
    if (isset($payload['mealId']) && !empty($payload['mealId'])) {
        $mealId = (int)$payload['mealId'];

        $check = $mysqli->prepare("SELECT 1 FROM meal_foods WHERE meal_id = ? UNION SELECT 1 FROM meal_plan_meals WHERE meal_id = ? LIMIT 1");
        $check->bind_param("ii", $mealId, $mealId);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'ဤမီလ်သည် မီလ်အစီအစဉ်များတွင်သုံးထားသောကြောင့် ဖျက်လို့မရပါ။']);
            exit;
        }

        $del = $mysqli->prepare("DELETE FROM meals WHERE id = ?");
        $del->bind_param("i", $mealId);
        $del->execute();

        echo json_encode(['status' => 'success', 'deletedId' => $mealId, 'message' => 'မီလ်ကိုဖျက်ပြီးပါပြီ။']);
        exit;
    }

    // ─── 4. DELETE MEAL PLAN ────────────────────
    if (isset($payload['mealPlanId']) && !empty($payload['mealPlanId'])) {
        $mealPlanId = (int)$payload['mealPlanId'];

        $check = $mysqli->prepare("SELECT 1 FROM meal_plan_meals WHERE meal_plan_id = ? UNION SELECT 1 FROM user_diet_recommendations WHERE meal_plan_id = ? LIMIT 1");
        $check->bind_param("ii", $mealPlanId, $mealPlanId);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'ဤမီလ်အစီအစဉ်သည် အသုံးပြုသူအကြံပြုချက်များတွင်ပါဝင်သောကြောင့် ဖျက်လို့မရပါ။']);
            exit;
        }

        $del = $mysqli->prepare("DELETE FROM meal_plans WHERE id = ?");
        $del->bind_param("i", $mealPlanId);
        $del->execute();

        echo json_encode(['status' => 'success', 'deletedId' => $mealPlanId, 'message' => 'မီလ်အစီအစဉ်ကိုဖျက်ပြီးပါပြီ။']);
        exit;
    }

    // ─── 5. DELETE DIET RECOMMENDATION ───────────
    if (isset($payload['user_id'], $payload['recommendation_id']) && !empty($payload['user_id']) && !empty($payload['recommendation_id'])) {
        $user_id = (int)$payload['user_id'];
        $recommendationId = (int)$payload['recommendation_id'];

        $sql = "SELECT id FROM user_diet_recommendations WHERE user_id = ? ORDER BY id DESC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $recommendationIds = [];
        while ($row = $result->fetch_assoc()) {
            $recommendationIds[] = $row['id'];
        }

        if (count($recommendationIds) <= 1) {
            http_response_code(409);
            echo json_encode([
                'status' => 'error',
                'message' => 'အသုံးပြုသူ၏နောက်ဆုံးအကြံပြုချက်ဖြစ်သောကြောင့် ဖျက်လို့မရပါ။'
            ]);
            exit;
        }

        if ($recommendationIds[0] == $recommendationId) {
            http_response_code(409);
            echo json_encode([
                'status' => 'error',
                'message' => 'နောက်ဆုံးအကြံပြုချက်ဖြစ်သောကြောင့် ဖျက်လို့မရပါ။'
            ]);
            exit;
        }

        $del = $mysqli->prepare("DELETE FROM user_diet_recommendations WHERE id = ?");
        $del->bind_param("i", $recommendationId);
        $del->execute();

        echo json_encode([
            'status' => 'success',
            'deletedId' => $recommendationId,
            'message' => 'အကြံပြုချက်ကိုဖျက်ပြီးပါပြီ။'
        ]);
        exit;
    }

    // ─── 6. DELETE MEAL FOOD ────────────────────
    if (isset($payload['mealFoodId']) && !empty($payload['mealFoodId'])) {
        $id = (int)$payload['mealFoodId'];

        $stmt = $mysqli->prepare("DELETE FROM meal_foods WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'deletedId' => $id, 'message' => 'အစားအစာကို မီလ်မှဖျက်ပြီးပါပြီ။']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'ဖျက်ရာတွင်ပြဿနာရှိပါသည်။']);
        }
        exit;
    }

    // ─── 7. DELETE MEAL PLAN MEAL ───────────────
    if (isset($payload['mealPlanMealId']) && !empty($payload['mealPlanMealId'])) {
        $id = (int)$payload['mealPlanMealId'];

        $stmt = $mysqli->prepare("DELETE FROM meal_plan_meals WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'deletedId' => $id, 'message' => 'မီလ်ကို မီလ်ပလန်မှဖျက်ပြီးပါပြီ။']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'ဖျက်ရာတွင်ပြဿနာရှိပါသည်။']);
        }
        exit;
    }

    // ─── Unknown ─────────────────────────────────
    echo json_encode(['status' => 'error', 'message' => 'မမှန်ကန်သောဖျက်ရန်တောင်းဆိုမှုဖြစ်ပါသည်။']);
    exit;
}
