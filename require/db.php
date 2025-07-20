<?php
$host = 'localhost';
$username = 'root';
$password = '';
$mysqli = new mysqli($host, $username, $password);
if ($mysqli->connect_errno) {
    echo "Failed to connect MySQL:" . $mysqli->connect_error;
    exit();
}

create_database($mysqli);
function create_database($mysqli)
{
    $sql = "CREATE DATABASE IF NOT EXISTS `healthy_meal_plan`
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function select_db($mysqli)
{
    if ($mysqli->select_db("healthy_meal_plan")) {
        return true;
    }
    return false;
}

select_db($mysqli);
create_tables($mysqli);

function create_tables($mysqli)
{
    $mysqli->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create foods table
    $mysqli->query("CREATE TABLE IF NOT EXISTS foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    calories FLOAT,
    protein FLOAT,
    carbs FLOAT,
    fat FLOAT,
    serving_size FLOAT,
    unit VARCHAR(20)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create meals table
    $mysqli->query("CREATE TABLE IF NOT EXISTS meals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    meal_type VARCHAR(50),
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create meal_plans table
    $mysqli->query("CREATE TABLE IF NOT EXISTS meal_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT,
    goal_type ENUM('lose','maintain','gain')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create meal_foods pivot table
    $mysqli->query("CREATE TABLE IF NOT EXISTS meal_foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meal_id INT,
    food_id INT,
    quantity FLOAT,
    FOREIGN KEY (meal_id) REFERENCES meals(id),
    FOREIGN KEY (food_id) REFERENCES foods(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create meal_plan_meals pivot table
    $mysqli->query("CREATE TABLE IF NOT EXISTS meal_plan_meals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meal_plan_id INT,
    meal_id INT,
    FOREIGN KEY (meal_plan_id) REFERENCES meal_plans(id),
    FOREIGN KEY (meal_id) REFERENCES meals(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create user_surveys table
    $mysqli->query("CREATE TABLE IF NOT EXISTS user_surveys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    age INT,
    gender ENUM('male','female'),
    weight FLOAT,
    height FLOAT,
    activity_level VARCHAR(50),
    goal ENUM('lose','maintain','gain'),
    bmr FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create user_diet_recommendations table
    $mysqli->query("CREATE TABLE IF NOT EXISTS user_diet_recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    survey_id INT,
    meal_plan_id INT,
    recommended_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (survey_id) REFERENCES user_surveys(id),
    FOREIGN KEY (meal_plan_id) REFERENCES meal_plans(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Create user_progress table
    $mysqli->query("CREATE TABLE IF NOT EXISTS user_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    record_date DATE,
    weight FLOAT,
    notes TEXT,
    bmr FLOAT,
    goal ENUM('lose','maintain','gain'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}
