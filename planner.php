<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Free Meal Planner</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <div class="container my-5">
    <div class="glass p-4">
      <h2 class="mb-4">Get Your Personalized Meal Plan</h2>
      <?php
      $show_result = false;
      $bmr = 0;
      $plan = '';
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $gender = $_POST['gender'] ?? '';
        $age = (int)($_POST['age'] ?? 0);
        $weight = (float)($_POST['weight'] ?? 0);
        $height = (float)($_POST['height'] ?? 0);
        $activity = $_POST['activity'] ?? '';
        // Calculate BMR
        if ($gender === 'male') {
          $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        } elseif ($gender === 'female') {
          $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
        }
        // Adjust for activity level
        $activity_factors = [
          'sedentary' => 1.2,
          'light' => 1.375,
          'moderate' => 1.55,
          'active' => 1.725,
          'very_active' => 1.9
        ];
        $bmr = $bmr * ($activity_factors[$activity] ?? 1);
        // Suggest plan (placeholder logic)
        if ($bmr < 1800) {
          $plan = 'Weight Loss Plan: Low-calorie, high-nutrient meals.';
        } elseif ($bmr < 2300) {
          $plan = 'Balanced Plan: Maintain your weight with balanced meals.';
        } else {
          $plan = 'Muscle Gain Plan: High-protein, energy-rich meals.';
        }
        $show_result = true;
      }
      ?>
      <?php if (!$show_result): ?>
        <form method="post">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select class="form-select" name="gender" required>
                <option value="">Select...</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Age</label>
              <input type="number" class="form-control" name="age" min="10" max="100" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Weight (kg)</label>
              <input type="number" class="form-control" name="weight" min="30" max="200" step="0.1" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Height (cm)</label>
              <input type="number" class="form-control" name="height" min="120" max="250" step="0.1" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Activity Level</label>
              <select class="form-select" name="activity" required>
                <option value="">Select...</option>
                <option value="sedentary">Sedentary (little or no exercise)</option>
                <option value="light">Lightly active (light exercise/sports 1-3 days/week)</option>
                <option value="moderate">Moderately active (moderate exercise/sports 3-5 days/week)</option>
                <option value="active">Active (hard exercise/sports 6-7 days/week)</option>
                <option value="very_active">Very active (very hard exercise & physical job)</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mt-4 w-100">See My Plan</button>
        </form>
      <?php else: ?>
        <div class="alert alert-success mt-4">
          <h4 class="alert-heading">Your Results</h4>
          <p><strong>Estimated Daily Calories (BMR):</strong> <?php echo round($bmr); ?> kcal</p>
          <p><strong>Recommended Plan:</strong> <?php echo $plan; ?></p>
        </div>
        <a href="planner.php" class="btn btn-outline-primary mt-3">Try Again</a>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>