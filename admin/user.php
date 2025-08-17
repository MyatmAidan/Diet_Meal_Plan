<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

// Get user ID from URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) {
    header("Location: user_list.php");
    exit();
}

// Fetch user data
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $mysqli->query($sql);

if ($result->num_rows === 0) {
    header("Location: user_list.php");
    exit();
}

$user = $result->fetch_assoc();

// Fetch latest survey data
$survey_sql = "SELECT * FROM user_surveys WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$survey_result = $mysqli->query($survey_sql);
$survey = $survey_result->fetch_assoc();

// Fetch latest progress
$progress_sql = "SELECT * FROM user_progress WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
$progress_result = $mysqli->query($progress_sql);
$progress = $progress_result->fetch_assoc();

// Fetch user's survey count
$survey_count_sql = "SELECT COUNT(*) as count FROM user_surveys WHERE user_id = $user_id";
$survey_count_result = $mysqli->query($survey_count_sql);
$survey_count = $survey_count_result->fetch_assoc()['count'];

// Fetch user's progress count
$progress_count_sql = "SELECT COUNT(*) as count FROM user_progress WHERE user_id = $user_id";
$progress_count_result = $mysqli->query($progress_count_sql);
$progress_count = $progress_count_result->fetch_assoc()['count'];
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-panel p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">အသုံးပြုသူအချက်အလက်</h3>
                    <div>
                        <a href="user_edit.php?id=<?= $user_id ?>" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> ပြင်ဆင်မည်
                        </a>
                        <a href="user_list.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> ပြန်သွားမည်
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- User Profile Card -->
                    <div class="col-md-4">
                        <div class="glass-panel p-4 mb-4" style="background: rgba(255, 255, 255, 0.1);">
                            <div class="text-center mb-3">
                                <?php if ($user['image']): ?>
                                    <img src="../uploads/users/<?= htmlspecialchars($user['image']) ?>"
                                        alt="Profile Image" class="profile-image-large mb-3">
                                <?php else: ?>
                                    <div class="profile-avatar-large mx-auto mb-3">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                <?php endif; ?>
                                <h5 class="mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                                <p class="mb-2 text-muted"><?= htmlspecialchars($user['email']) ?></p>
                                <span class="badge <?= $user['role'] == 1 ? 'bg-primary' : 'bg-success' ?>">
                                    <?= $user['role'] == 1 ? 'အက်ဒမင်' : 'အသုံးပြုသူ' ?>
                                </span>
                            </div>

                            <!-- Quick Stats -->
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h6 class="mb-1"><?= $survey_count ?></h6>
                                        <small class="text-muted">စစ်တမ်း</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h6 class="mb-1"><?= $progress_count ?></h6>
                                        <small class="text-muted">တိုးတက်မှု</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="col-md-8">
                        <div class="glass-panel p-4">
                            <h5 class="fw-bold mb-3">အသုံးပြုသူအသေးစိတ်</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <strong>အမည်:</strong> <?= htmlspecialchars($user['name']) ?>
                                    </div>
                                    <div class="detail-item">
                                        <strong>အီးမေးလ်:</strong> <?= htmlspecialchars($user['email']) ?>
                                    </div>
                                    <div class="detail-item">
                                        <strong>အခန်းကဏ္ဍ:</strong>
                                        <?= $user['role'] == 1 ? 'အက်ဒမင်' : 'အသုံးပြုသူ' ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <strong>အီးမေးလ်ပြင်ဆင်ချိန်:</strong>
                                        <?= $user['image'] ? 'ပြင်ဆင်ပြီး' : 'မပြင်ဆင်ရသေး' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health Information -->
                <?php if ($survey): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="glass-panel p-4">
                                <h5 class="fw-bold mb-3">ကျန်းမာရေးအချက်အလက်</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-primary"><?= $survey['age'] ?> နှစ်</h6>
                                            <small class="text-muted">အသက်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-success"><?= $survey['weight'] ?> kg</h6>
                                            <small class="text-muted">အလေးချိန်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-info"><?= $survey['height'] ?> cm</h6>
                                            <small class="text-muted">အမြင့်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="health-stat">
                                            <h6 class="text-warning"><?= round($survey['bmr']) ?> kcal</h6>
                                            <small class="text-muted">BMR</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <strong>ရည်ရွယ်ချက်:</strong> <?= ucfirst($survey['goal']) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>လှုပ်ရှားမှုအဆင့်:</strong> <?= ucfirst($survey['activity_level']) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>ကျား/မ:</strong> <?= ucfirst($survey['gender']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Progress Information -->
                <?php if ($progress): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="glass-panel p-4">
                                <h5 class="fw-bold mb-3">လက်ရှိတိုးတက်မှု</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="progress-stat">
                                            <h6 class="text-primary"><?= $progress['record_date'] ?></h6>
                                            <small class="text-muted">ရက်စွဲ</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="progress-stat">
                                            <h6 class="text-success"><?= $progress['weight'] ?> kg</h6>
                                            <small class="text-muted">လက်ရှိအလေးချိန်</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="progress-stat">
                                            <h6 class="text-info"><?= ucfirst($progress['goal']) ?></h6>
                                            <small class="text-muted">ရည်ရွယ်ချက်</small>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($progress['notes'])): ?>
                                    <div class="mt-3">
                                        <strong>မှတ်ချက်:</strong> <?= htmlspecialchars($progress['notes']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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

    .profile-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .profile-avatar-large i {
        font-size: 40px;
        color: #fff;
    }

    .profile-image-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .stat-item,
    .health-stat,
    .progress-stat {
        padding: 10px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        margin-bottom: 10px;
    }

    .stat-item h6,
    .health-stat h6,
    .progress-stat h6 {
        margin: 0;
        font-weight: 600;
    }

    .detail-item {
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .detail-item:last-child {
        border-bottom: none;
    }
</style>

<?php require_once('../layout/footer.php'); ?>