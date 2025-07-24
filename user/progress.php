<?php
require_once('../require/check_auth.php');
check_auth(0);
require_once('../layout/header.php');
require_once('../require/db.php');

$user_id = $_SESSION['user_id'];
$weight = $notes = '';
$weight_err = '';
$msg = '';

// Get latest BMR & goal from survey (for storing in progress)
$survey_sql = "SELECT bmr, goal FROM user_surveys WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$survey_result = $mysqli->query($survey_sql);
$survey = $survey_result->fetch_assoc();
$bmr = $survey['bmr'] ?? null;
$goal = $survey['goal'] ?? null;

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $weight = trim($_POST['weight']);
    $notes = trim($_POST['notes']);

    if ($weight === '' || $weight <= 0) {
        $weight_err = "Please enter a valid weight";
    }

    if (!$weight_err && $bmr && $goal) {
        $date = date('Y-m-d');
        $stmt = $mysqli->prepare("INSERT INTO user_progress (user_id, record_date, weight, notes, bmr, goal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $user_id, $date, $weight, $notes, $bmr, $goal);
        if ($stmt->execute()) {
            header("Location: index.php?msg=progress_updated");
            exit;
        } else {
            $msg = '<div class="alert alert-danger">Failed to save progress.</div>';
        }
    } else {
        $msg = '<div class="alert alert-warning">Please correct the error below.</div>';
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h4 class="fw-bold mb-3">Update Progress</h4>
                <?= $msg ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" class="form-control glass-input" name="weight" id="weight" value="<?= htmlspecialchars($weight) ?>">
                        <?php if ($weight_err): ?>
                            <div class="text-danger small"><?= htmlspecialchars($weight_err) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (optional)</label>
                        <textarea class="form-control glass-input" name="notes" id="notes" rows="3"><?= htmlspecialchars($notes) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success fw-bold">Save Progress</button>
                    </div>
                </form>
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

    .glass-input {
        background: rgba(255, 255, 255, 0.25) !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
        color: #222;
    }
</style>

<?php require_once('../layout/footer.php'); ?>