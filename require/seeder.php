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
    ('အက်မင်', 'admin@gmail.com', '" . password_hash('password1', PASSWORD_DEFAULT) . "', 1),
    ('John Doe', 'john@gmail.com', '" . password_hash('password1', PASSWORD_DEFAULT) . "', 0),
    ('Jane Smith', 'jane@gmail.com', '" . password_hash('password1', PASSWORD_DEFAULT) . "', 0),
    ('Mike Tyson', 'mike@gmail.com', '" . password_hash('password1', PASSWORD_DEFAULT) . "', 0),
    ('Lucy Heart', 'lucy@gmail.com', '" . password_hash('password1', PASSWORD_DEFAULT) . "', 0),
    ('Tom Finn', 'tom@gmail.com', '" . password_hash('password1', PASSWORD_DEFAULT) . "', 0)
", 'Seed users');

// Seed foods - Comprehensive but reasonable set
seed_query($mysqli, "
    INSERT INTO foods (name, calories, protein, carbs, fat, serving_size, unit) VALUES
    -- Proteins
    ('Chicken Breast', 165, 31, 0, 3.6, 100, 'g'),
    ('Turkey Breast', 157, 29, 0, 3.6, 100, 'g'),
    ('Lean Beef', 250, 26, 0, 15, 100, 'g'),
    ('Salmon', 208, 20, 0, 13, 100, 'g'),
    ('Tuna', 144, 30, 0, 1, 100, 'g'),
    ('Cod', 105, 23, 0, 0.9, 100, 'g'),
    ('Shrimp', 99, 24, 0.2, 0.3, 100, 'g'),
    ('Eggs', 155, 13, 1.1, 11, 100, 'g'),
    ('Tofu', 76, 8, 1.9, 4.8, 100, 'g'),
    
    -- Grains & Starches
    ('Brown Rice', 112, 2.3, 23, 0.8, 100, 'g'),
    ('Quinoa', 120, 4.4, 21.3, 1.9, 100, 'g'),
    ('Oats', 389, 16.9, 66, 6.9, 100, 'g'),
    ('Whole Wheat Bread', 247, 13, 41, 4.2, 100, 'g'),
    ('Pasta', 131, 5, 25, 1.1, 100, 'g'),
    ('Sweet Potato', 86, 1.6, 20, 0.1, 100, 'g'),
    
    -- Legumes
    ('Lentils', 116, 9, 20, 0.4, 100, 'g'),
    ('Chickpeas', 164, 8.9, 27, 2.6, 100, 'g'),
    ('Black Beans', 132, 8.9, 23, 0.5, 100, 'g'),
    
    -- Vegetables
    ('Broccoli', 34, 2.8, 7, 0.4, 100, 'g'),
    ('Spinach', 23, 2.9, 3.6, 0.4, 100, 'g'),
    ('Kale', 49, 4.3, 8.8, 0.9, 100, 'g'),
    ('Carrots', 41, 0.9, 10, 0.2, 100, 'g'),
    ('Bell Peppers', 31, 1, 7, 0.3, 100, 'g'),
    ('Tomatoes', 18, 0.9, 3.9, 0.2, 100, 'g'),
    ('Cucumber', 16, 0.7, 3.6, 0.1, 100, 'g'),
    ('Zucchini', 17, 1.2, 3.1, 0.3, 100, 'g'),
    ('Cauliflower', 25, 1.9, 5, 0.3, 100, 'g'),
    ('Asparagus', 20, 2.2, 3.9, 0.1, 100, 'g'),
    ('Mushrooms', 22, 3.1, 3.3, 0.3, 100, 'g'),
    ('Onions', 40, 1.1, 9.3, 0.1, 100, 'g'),
    ('Garlic', 149, 6.4, 33, 0.5, 100, 'g'),
    
    -- Fruits
    ('Banana', 89, 1.1, 23, 0.3, 100, 'g'),
    ('Apple', 52, 0.3, 14, 0.2, 100, 'g'),
    ('Orange', 47, 0.9, 12, 0.1, 100, 'g'),
    ('Strawberries', 32, 0.7, 8, 0.3, 100, 'g'),
    ('Blueberries', 57, 0.7, 14, 0.3, 100, 'g'),
    ('Grapes', 62, 0.6, 16, 0.2, 100, 'g'),
    ('Pineapple', 50, 0.5, 13, 0.1, 100, 'g'),
    ('Mango', 60, 0.8, 15, 0.4, 100, 'g'),
    ('Kiwi', 61, 1.1, 15, 0.5, 100, 'g'),
    ('Papaya', 43, 0.5, 11, 0.3, 100, 'g'),
    ('Watermelon', 30, 0.6, 8, 0.2, 100, 'g'),
    ('Peach', 39, 0.9, 10, 0.3, 100, 'g'),
    ('Pear', 57, 0.4, 15, 0.1, 100, 'g'),
    ('Cherries', 50, 1.1, 12, 0.3, 100, 'g'),
    ('Raspberries', 52, 1.2, 12, 0.7, 100, 'g'),
    
    -- Dairy & Alternatives
    ('Greek Yogurt', 59, 10, 3.6, 0.4, 100, 'g'),
    ('Cottage Cheese', 98, 11, 3.4, 4.3, 100, 'g'),
    ('Milk', 42, 3.4, 5, 1, 100, 'g'),
    ('Cheese', 402, 25, 1.3, 33, 100, 'g'),
    
    -- Nuts & Seeds
    ('Almonds', 579, 21, 22, 50, 100, 'g'),
    ('Walnuts', 654, 15, 14, 65, 100, 'g'),
    ('Peanuts', 567, 26, 16, 49, 100, 'g'),
    ('Chia Seeds', 486, 17, 42, 31, 100, 'g'),
    ('Flax Seeds', 534, 18, 29, 42, 100, 'g'),
    ('Sunflower Seeds', 584, 21, 20, 51, 100, 'g'),
    
    -- Healthy Fats
    ('Avocado', 160, 2, 9, 15, 100, 'g'),
    ('Olive Oil', 884, 0, 0, 100, 100, 'g'),
    ('Coconut Oil', 862, 0, 0, 100, 100, 'g'),
    
    -- Sweeteners & Treats
    ('Honey', 304, 0.3, 82, 0, 100, 'g'),
    ('Maple Syrup', 260, 0, 67, 0, 100, 'g'),
    ('Dark Chocolate', 546, 4.9, 61, 31, 100, 'g'),
    
    -- Dried Fruits
    ('Dates', 282, 2.5, 75, 0.4, 100, 'g'),
    ('Raisins', 299, 3.1, 79, 0.5, 100, 'g'),
    ('Cranberries', 46, 0.4, 12, 0.1, 100, 'g'),
    
    -- Superfoods
    ('Pomegranate', 83, 1.7, 19, 1.2, 100, 'g'),
    ('Goji Berries', 349, 14, 77, 0.4, 100, 'g'),
    ('Acai Berries', 70, 1, 4, 5, 100, 'g'),
    ('Seaweed', 45, 6, 8, 0.6, 100, 'g'),
    ('Spirulina', 290, 57, 24, 8, 100, 'g'),
    ('Moringa', 37, 2, 9, 0.2, 100, 'g'),
    
    -- Herbs & Spices
    ('Turmeric', 354, 8, 65, 10, 100, 'g'),
    ('Ginger', 80, 1.8, 18, 0.8, 100, 'g'),
    ('Cinnamon', 247, 4, 81, 1.2, 100, 'g'),
    ('Basil', 22, 3.2, 2.6, 0.6, 100, 'g'),
    ('Oregano', 265, 9, 69, 4.3, 100, 'g'),
    ('Thyme', 101, 5.6, 24, 1.7, 100, 'g'),
    ('Rosemary', 131, 3.3, 21, 5.9, 100, 'g'),
    ('Mint', 44, 3.8, 8, 0.7, 100, 'g'),
    ('Parsley', 36, 3, 6, 0.8, 100, 'g'),
    ('Cilantro', 23, 2.1, 3.7, 0.5, 100, 'g'),
    
    -- Beverages
    ('Coffee', 2, 0.3, 0, 0, 100, 'g'),
    ('Green Tea', 1, 0.2, 0.2, 0, 100, 'g'),
    ('Black Tea', 1, 0.1, 0.2, 0, 100, 'g'),
    ('Lemon', 29, 1.1, 9, 0.3, 100, 'g'),
    ('Lime', 30, 0.7, 10, 0.2, 100, 'g'),
    ('Grapefruit', 42, 0.8, 11, 0.1, 100, 'g')
", 'Seed foods');

// Seed meals - Expanded with more diverse combinations
seed_query($mysqli, "
    INSERT INTO meals (name, meal_type, description) VALUES
    -- Breakfast Meals
    ('Protein Power Breakfast', 'breakfast', 'Scrambled eggs with spinach and whole wheat toast'),
    ('Oatmeal Delight', 'breakfast', 'Steel-cut oats with berries and nuts'),
    ('Greek Yogurt Bowl', 'breakfast', 'Greek yogurt with honey, granola, and fresh fruits'),
    ('Avocado Toast', 'breakfast', 'Whole wheat toast with mashed avocado and poached eggs'),
    ('Smoothie Bowl', 'breakfast', 'Acai bowl with granola, banana, and berries'),
    ('Quinoa Breakfast Bowl', 'breakfast', 'Quinoa with almond milk, cinnamon, and dried fruits'),
    
    -- Lunch Meals
    ('Grilled Chicken Salad', 'lunch', 'Mixed greens with grilled chicken breast and vinaigrette'),
    ('Mediterranean Bowl', 'lunch', 'Quinoa with chickpeas, vegetables, and tahini dressing'),
    ('Turkey Wrap', 'lunch', 'Whole wheat wrap with turkey, avocado, and vegetables'),
    ('Salmon Quinoa Bowl', 'lunch', 'Grilled salmon with quinoa and roasted vegetables'),
    ('Vegetarian Buddha Bowl', 'lunch', 'Brown rice with tofu, vegetables, and peanut sauce'),
    ('Tuna Salad', 'lunch', 'Tuna with mixed greens, tomatoes, and olive oil'),
    
    -- Dinner Meals
    ('Baked Salmon Dinner', 'dinner', 'Baked salmon with sweet potato and asparagus'),
    ('Lean Beef Stir Fry', 'dinner', 'Lean beef with brown rice and mixed vegetables'),
    ('Chicken Quinoa Bowl', 'dinner', 'Grilled chicken with quinoa and roasted vegetables'),
    ('Vegetarian Pasta', 'dinner', 'Whole wheat pasta with tomato sauce and vegetables'),
    ('Shrimp Scampi', 'dinner', 'Shrimp with whole wheat pasta and garlic sauce'),
    ('Tofu Curry', 'dinner', 'Tofu curry with brown rice and vegetables'),
    
    -- Snacks
    ('Nut Mix', 'snack', 'Mixed nuts and dried fruits'),
    ('Greek Yogurt with Berries', 'snack', 'Greek yogurt topped with fresh berries'),
    ('Apple with Almond Butter', 'snack', 'Apple slices with almond butter'),
    ('Hummus with Vegetables', 'snack', 'Hummus with carrot and cucumber sticks'),
    ('Dark Chocolate and Nuts', 'snack', 'Dark chocolate with mixed nuts'),
    ('Smoothie', 'snack', 'Berry smoothie with protein powder')
", 'Seed meals');

// Seed meal_foods - Comprehensive combinations
seed_query($mysqli, "
    INSERT INTO meal_foods (meal_id, food_id, quantity) VALUES
    -- Protein Power Breakfast (meal_id: 1)
    (1, 8, 100), (1, 22, 50), (1, 19, 60),
    
    -- Oatmeal Delight (meal_id: 2)
    (2, 18, 80), (2, 33, 30), (2, 46, 20),
    
    -- Greek Yogurt Bowl (meal_id: 3)
    (3, 40, 150), (3, 47, 15), (3, 46, 25), (3, 33, 50),
    
    -- Avocado Toast (meal_id: 4)
    (4, 19, 60), (4, 6, 50), (4, 8, 100),
    
    -- Smoothie Bowl (meal_id: 5)
    (5, 58, 100), (5, 46, 20), (5, 33, 50), (5, 34, 30),
    
    -- Quinoa Breakfast Bowl (meal_id: 6)
    (6, 7, 80), (6, 47, 10), (6, 6, 20), (6, 50, 30),
    
    -- Grilled Chicken Salad (meal_id: 7)
    (7, 1, 120), (7, 22, 100), (7, 25, 50), (7, 26, 30),
    
    -- Mediterranean Bowl (meal_id: 8)
    (8, 7, 100), (8, 17, 80), (8, 25, 50), (8, 26, 30),
    
    -- Turkey Wrap (meal_id: 9)
    (9, 19, 60), (9, 2, 100), (9, 6, 30), (9, 25, 40),
    
    -- Salmon Quinoa Bowl (meal_id: 10)
    (10, 4, 150), (10, 7, 100), (10, 30, 50), (10, 31, 30),
    
    -- Vegetarian Buddha Bowl (meal_id: 11)
    (11, 2, 100), (11, 9, 120), (11, 25, 60), (11, 26, 40),
    
    -- Tuna Salad (meal_id: 12)
    (12, 5, 100), (12, 22, 80), (12, 25, 50), (12, 26, 30),
    
    -- Baked Salmon Dinner (meal_id: 13)
    (13, 4, 180), (13, 15, 150), (13, 30, 80),
    
    -- Lean Beef Stir Fry (meal_id: 14)
    (14, 3, 150), (14, 2, 100), (14, 25, 60), (14, 26, 40),
    
    -- Chicken Quinoa Bowl (meal_id: 15)
    (15, 1, 150), (15, 7, 100), (15, 30, 50), (15, 31, 30),
    
    -- Vegetarian Pasta (meal_id: 16)
    (16, 20, 120), (16, 25, 80), (16, 26, 50), (16, 27, 40),
    
    -- Shrimp Scampi (meal_id: 17)
    (17, 7, 150), (17, 20, 100), (17, 35, 20), (17, 36, 10),
    
    -- Tofu Curry (meal_id: 18)
    (18, 9, 150), (18, 2, 100), (18, 25, 60), (18, 26, 40),
    
    -- Nut Mix (meal_id: 19)
    (19, 46, 30), (19, 47, 20), (19, 48, 20), (19, 50, 15),
    
    -- Greek Yogurt with Berries (meal_id: 20)
    (20, 40, 150), (20, 33, 50), (20, 34, 30),
    
    -- Apple with Almond Butter (meal_id: 21)
    (21, 32, 100), (21, 46, 30),
    
    -- Hummus with Vegetables (meal_id: 22)
    (22, 17, 80), (22, 24, 60), (22, 27, 40),
    
    -- Dark Chocolate and Nuts (meal_id: 23)
    (23, 52, 30), (23, 46, 20), (23, 47, 15),
    
    -- Smoothie (meal_id: 24)
    (24, 33, 80), (24, 34, 50), (24, 40, 100), (24, 50, 20)
", 'Seed meal_foods');

// Seed meal plans
seed_query($mysqli, "
    INSERT INTO meal_plans (name, description, goal_type) VALUES
    ('Weight Loss Plan', 'Low calorie meal plan for weight loss', 'lose'),
    ('Maintenance Plan', 'Balanced meal plan for weight maintenance', 'maintain'),
    ('Weight Gain Plan', 'High calorie meal plan for weight gain', 'gain'),
    ('Muscle Builder Plan', 'High protein plan for muscle gain', 'gain'),
    ('Vegetarian Plan', 'Plant-based meal plan', 'maintain'),
    ('Keto Plan', 'Low carb high fat meal plan', 'lose'),
    ('Mediterranean Plan', 'Mediterranean diet style meal plan', 'maintain'),
    ('Athlete Plan', 'High energy meal plan for athletes', 'gain')
", 'Seed meal_plans');

// Link meals to meal plans
seed_query($mysqli, "
    INSERT INTO meal_plan_meals (meal_plan_id, meal_id) VALUES
    -- Weight Loss Plan
    (1, 1), (1, 7), (1, 13), (1, 20),
    -- Maintenance Plan
    (2, 3), (2, 8), (2, 15), (2, 21),
    -- Weight Gain Plan
    (3, 5), (3, 10), (3, 14), (3, 23),
    -- Muscle Builder Plan
    (4, 1), (4, 12), (4, 13), (4, 20),
    -- Vegetarian Plan
    (5, 6), (5, 11), (5, 16), (5, 22),
    -- Keto Plan
    (6, 4), (6, 7), (6, 13), (6, 19),
    -- Mediterranean Plan
    (7, 3), (7, 8), (7, 10), (7, 21),
    -- Athlete Plan
    (8, 2), (8, 9), (8, 14), (8, 24)
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
