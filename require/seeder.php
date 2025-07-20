<?php
// Seeder for healthy_meal_plan database
require_once('db.php');

// Helper function to run and display query results
function seed_query($mysqli, $sql, $desc)
{
    if ($mysqli->query($sql)) {
        echo "$desc: ✅ Success\n";
    } else {
        echo "$desc: ❌ Failed - " . $mysqli->error . "\n";
    }
}

// Clear tables in correct order
$mysqli->query('SET FOREIGN_KEY_CHECKS=0');
$tables = [
    'user_diet_recommendations',
    'user_progress',
    'user_surveys',
    'meal_plan_meals',
    'meal_foods',
    'meal_plans',
    'meals',
    'foods',
    'users'
];
foreach ($tables as $table) {
    $mysqli->query("TRUNCATE TABLE $table");
}
$mysqli->query('SET FOREIGN_KEY_CHECKS=1');

// Seed users
seed_query($mysqli, "
    INSERT INTO users (name, email, password, role) VALUES
    ('Admin User', 'admin@example.com', 'adminpass', 1),
    ('John Doe', 'john@example.com', 'johnpass', 0),
    ('Jane Smith', 'jane@example.com', 'janepass', 0)
", 'Seed users');

// Seed foods
seed_query($mysqli, "
    INSERT INTO foods (name, calories, protein, carbs, fat, serving_size, unit) VALUES
    ('Chicken Breast', 165, 31, 0, 3.6, 100, 'g'),
    ('Brown Rice', 112, 2.3, 23, 0.8, 100, 'g'),
    ('Broccoli', 34, 2.8, 7, 0.4, 100, 'g')
", 'Seed foods');

// Seed meals
seed_query($mysqli, "
    INSERT INTO meals (name, meal_type, description) VALUES
    ('Grilled Chicken Meal', 'lunch', 'Chicken breast with brown rice and broccoli'),
    ('Veggie Breakfast', 'breakfast', 'Broccoli and brown rice bowl'),
    ('Protein Dinner', 'dinner', 'Chicken breast with extra broccoli')
", 'Seed meals');

// Seed meal_foods (map foods to meals with quantity)
seed_query($mysqli, "
    INSERT INTO meal_foods (meal_id, food_id, quantity) VALUES
    (1, 1, 150), -- Grilled Chicken Meal: Chicken Breast
    (1, 2, 100), -- Grilled Chicken Meal: Brown Rice
    (1, 3, 80),  -- Grilled Chicken Meal: Broccoli

    (2, 2, 120), -- Veggie Breakfast: Brown Rice
    (2, 3, 100), -- Veggie Breakfast: Broccoli

    (3, 1, 200), -- Protein Dinner: Chicken Breast
    (3, 3, 120)  -- Protein Dinner: Broccoli
", 'Seed meal_foods');

// Seed meal plans
seed_query($mysqli, "
    INSERT INTO meal_plans (name, description, goal_type) VALUES
    ('Weight Loss Plan', 'Low calorie meal plan', 'lose'),
    ('Maintenance Plan', 'Balanced meal plan', 'maintain'),
    ('Weight Gain Plan', 'High calorie meal plan', 'gain')
", 'Seed meal_plans');

// Link meals to meal plans
seed_query($mysqli, "
    INSERT INTO meal_plan_meals (meal_plan_id, meal_id) VALUES
    (1, 1), -- Weight Loss → Grilled Chicken
    (1, 2), -- Weight Loss → Veggie Breakfast

    (2, 1), -- Maintenance → Grilled Chicken
    (2, 3), -- Maintenance → Protein Dinner

    (3, 3)  -- Weight Gain → Protein Dinner
", 'Seed meal_plan_meals');

// Seed user survey (BMR + goals)
seed_query($mysqli, "
    INSERT INTO user_surveys (user_id, age, gender, weight, height, activity_level, goal, bmr) VALUES
    (2, 30, 'male', 80, 180, 'moderate', 'lose', 1700),
    (3, 28, 'female', 65, 165, 'light', 'maintain', 1400)
", 'Seed user_surveys');

// Link survey to recommended plan
seed_query($mysqli, "
    INSERT INTO user_diet_recommendations (user_id, survey_id, meal_plan_id) VALUES
    (2, 1, 1),
    (3, 2, 2)
", 'Seed user_diet_recommendations');

// Seed user progress tracking
seed_query($mysqli, "
    INSERT INTO user_progress (user_id, record_date, weight, notes, bmr, goal) VALUES
    (2, '2024-06-01', 80, 'Starting weight', 1700, 'lose'),
    (2, '2024-06-10', 78, 'Lost 2kg', 1700, 'lose'),
    (3, '2024-06-01', 65, 'Initial', 1400, 'maintain')
", 'Seed user_progress');

echo "\n🎉 Seeding complete! You're ready to build.\n";
