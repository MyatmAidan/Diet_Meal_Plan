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

// Clear tables
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
    ('Jane Smith', 'jane@example.com', 'janepass', 0),
    ('Mike Tyson', 'mike@example.com', 'mikepass', 0),
    ('Lucy Heart', 'lucy@example.com', 'lucypass', 0),
    ('Tom Finn', 'tom@example.com', 'tompass', 0)
", 'Seed users');

// Seed foods
seed_query($mysqli, "
    INSERT INTO foods (name, calories, protein, carbs, fat, serving_size, unit) VALUES
    ('Chicken Breast', 165, 31, 0, 3.6, 100, 'g'),
    ('Brown Rice', 112, 2.3, 23, 0.8, 100, 'g'),
    ('Broccoli', 34, 2.8, 7, 0.4, 100, 'g'),
    ('Salmon', 208, 20, 0, 13, 100, 'g'),
    ('Sweet Potato', 86, 1.6, 20, 0.1, 100, 'g'),
    ('Avocado', 160, 2, 9, 15, 100, 'g'),
    ('Quinoa', 120, 4.4, 21.3, 1.9, 100, 'g'),
    ('Eggs', 155, 13, 1.1, 11, 100, 'g')
", 'Seed foods');

// Seed meals
seed_query($mysqli, "
    INSERT INTO meals (name, meal_type, description) VALUES
    ('Grilled Chicken Meal', 'lunch', 'Chicken breast with brown rice and broccoli'),
    ('Veggie Breakfast', 'breakfast', 'Broccoli and brown rice bowl'),
    ('Protein Dinner', 'dinner', 'Chicken breast with extra broccoli'),
    ('Salmon Delight', 'dinner', 'Grilled salmon with quinoa and avocado'),
    ('Sweet Start', 'breakfast', 'Sweet potato and scrambled eggs'),
    ('Power Lunch', 'lunch', 'Quinoa bowl with eggs and avocado')
", 'Seed meals');

// Seed meal_foods
seed_query($mysqli, "
    INSERT INTO meal_foods (meal_id, food_id, quantity) VALUES
    (1, 1, 150), (1, 2, 100), (1, 3, 80),
    (2, 2, 120), (2, 3, 100),
    (3, 1, 200), (3, 3, 120),
    (4, 4, 180), (4, 7, 100), (4, 6, 50),
    (5, 5, 150), (5, 8, 100),
    (6, 7, 120), (6, 8, 100), (6, 6, 50)
", 'Seed meal_foods');

// Seed meal plans
seed_query($mysqli, "
    INSERT INTO meal_plans (name, description, goal_type) VALUES
    ('Weight Loss Plan', 'Low calorie meal plan', 'lose'),
    ('Maintenance Plan', 'Balanced meal plan', 'maintain'),
    ('Weight Gain Plan', 'High calorie meal plan', 'gain'),
    ('Muscle Builder Plan', 'High protein plan for muscle gain', 'gain')
", 'Seed meal_plans');

// Link meals to meal plans
seed_query($mysqli, "
    INSERT INTO meal_plan_meals (meal_plan_id, meal_id) VALUES
    (1, 1), (1, 2),
    (2, 1), (2, 3), (2, 5),
    (3, 4), (3, 6),
    (4, 1), (4, 4), (4, 6)
", 'Seed meal_plan_meals');

// Seed user surveys
seed_query($mysqli, "
    INSERT INTO user_surveys (user_id, age, gender, weight, height, activity_level, goal, bmr) VALUES
    (2, 30, 'male', 80, 180, 'moderate', 'lose', 1700),
    (3, 28, 'female', 65, 165, 'light', 'maintain', 1400),
    (4, 35, 'male', 75, 175, 'active', 'gain', 2300),
    (5, 22, 'female', 55, 160, 'moderate', 'gain', 2000),
    (6, 40, 'male', 90, 185, 'high', 'maintain', 2500)
", 'Seed user_surveys');

// Seed user diet recommendations
seed_query($mysqli, "
    INSERT INTO user_diet_recommendations (user_id, survey_id, meal_plan_id) VALUES
    (2, 1, 1),
    (3, 2, 2),
    (4, 3, 4),
    (5, 4, 3),
    (6, 5, 2)
", 'Seed user_diet_recommendations');

// Seed user progress
seed_query($mysqli, "
    INSERT INTO user_progress (user_id, record_date, weight, notes, bmr, goal) VALUES
    (2, '2024-06-01', 80, 'Starting weight', 1700, 'lose'),
    (2, '2024-06-10', 78, 'Lost 2kg', 1700, 'lose'),
    (3, '2024-06-01', 65, 'Initial', 1400, 'maintain'),
    (4, '2024-06-01', 75, 'Targeting gains', 2300, 'gain'),
    (5, '2024-06-01', 55, 'Trying to bulk up', 2000, 'gain'),
    (6, '2024-06-01', 90, 'High activity desk job', 2500, 'maintain')
", 'Seed user_progress');

echo "\n🎉 Seeding complete! You're ready to build.\n";
