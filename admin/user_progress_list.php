<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../require/i18n.php');
require_once('../layout/header.php');
require_once('../require/db.php');
require_once('../require/common.php');

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search_sql = "";
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $search = '%' . $mysqli->real_escape_string($_GET['search']) . '%';
    $search_sql = "WHERE u.name LIKE ? OR up.record_date LIKE ? OR up.weight LIKE ? OR up.notes LIKE ? OR up.bmr LIKE ? OR up.goal LIKE ? OR up.created_at LIKE ?";
    $params = [$search, $search, $search, $search, $search, $search, $search];
    $types = "sssssss";
}

// Count total
$count_query = "SELECT COUNT(*) as total 
                FROM user_progress up
                JOIN users u ON up.user_id = u.id
                $search_sql";

$count_stmt = $mysqli->prepare($count_query);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch records
$query = "SELECT up.*, u.name AS user_name 
          FROM user_progress up
          JOIN users u ON up.user_id = u.id
          $search_sql
          LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($query);
if (!empty($params)) {
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0"><?= __('အသုံးပြုသူ တိုးတက်မှု') ?></h3>
            </div>

            <?php if (!empty($_GET['search'])): ?>
                <div class="alert alert-info">
                    "<?= htmlspecialchars($_GET['search']) ?>" <?= __('အတွက် ရလဒ်') ?> <?= $total_rows ?> <?= __('ခု တွေ့ရှိခဲ့သည်။') ?>
                </div>
            <?php endif; ?>

            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th><?= __('စဉ်') ?></th>
                            <th><?= __('အသုံးပြုသူ') ?></th>
                            <th><?= __('ရက်စွဲ') ?></th>
                            <th><?= __('ကိုယ်အလေးချိန်') ?></th>
                            <th><?= __('မှတ်စု') ?></th>
                            <th><?= __('BMI') ?></th>
                            <th><?= __('ရည်ရွယ်ချက်') ?></th>
                            <th><?= __('ဖန်တီးသည့်ရက်စွဲ') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center"><?= __('မည်သည့်ရလဒ်များမရှိပါ') ?></td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                                    <td><?= htmlspecialchars($row['record_date']) ?></td>
                                    <td><?= htmlspecialchars($row['weight']) ?></td>
                                    <td><?= htmlspecialchars($row['notes']) ?></td>
                                    <td><?= htmlspecialchars($row['bmr']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($row['goal'])) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($total_rows > 0): ?>
                    <?= generate_pagination($page, $total_pages, $_GET['search'] ?? '') ?>
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