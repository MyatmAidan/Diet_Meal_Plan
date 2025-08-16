<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search_sql = "";
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $search = '%' . $mysqli->real_escape_string($_GET['search']) . '%';
    $search_sql = " WHERE u.name LIKE ? OR us.age LIKE ? OR us.gender LIKE ? OR us.weight LIKE ? OR us.height LIKE ? OR us.activity_level LIKE ? OR us.goal LIKE ? OR us.bmr LIKE ? OR us.created_at LIKE ?";
    $params = array_fill(0, 9, $search);
    $types = str_repeat("s", 9);
}

// Count total for pagination
$count_query = "SELECT COUNT(*) as total FROM user_surveys us JOIN users u ON us.user_id = u.id $search_sql";
$count_stmt = $mysqli->prepare($count_query);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch paginated results
$query = "SELECT us.*, u.name AS user_name 
          FROM user_surveys us 
          JOIN users u ON us.user_id = u.id
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
<?php
// Step 1: Define $search variable to avoid "undefined variable" warning
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">အသုံးပြုသူ စစ်တမ်းများ</h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='user_surveys_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> စစ်တမ်း အသစ်ထည့်ရန်
                </button>
            </div>
           <?php if ($search): ?>
                <div class="alert alert-info">
                    "<?= htmlspecialchars($search) ?>" အတွက် ရလဒ် <?= $total_rows ?> ခု တွေ့ရှိခဲ့သည်။
                </div>
            <?php endif; ?>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th>စဉ်</th>
                            <th>အသုံးပြုသူ</th>
                            <th>အသက်</th>
                            <th>ကျား/မ</th>
                            <th>ကိုယ်အလေးချိန် (ကီလိုဂရမ်)</th>
                            <th>ကိုယ်ရေပမာဏ (စင်တီမီတာ)</th>
                            <th>လှုပ်ရှားမှုအဆင့်</th>
                            <th>ရည်ရွယ်ချက်</th>
                            <th>BMI</th>
                            <th>ဖန်တီးသည့်ရက်စွဲ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php
                        if ($result->num_rows === 0) {
                            echo '<tr><td colspan="10" class="text-center">မည်သည့်ရလဒ်များလည်းမရှိပါ</td></tr>';
                        }
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['user_name']) ?></td>
                                <td><?= htmlspecialchars($row['age']) ?></td>
                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                <td><?= htmlspecialchars($row['weight']) ?></td>
                                <td><?= htmlspecialchars($row['height']) ?></td>
                                <td><?= htmlspecialchars($row['activity_level']) ?></td>
                                <td><?= htmlspecialchars($row['goal']) ?></td>
                                <td><?= htmlspecialchars($row['bmr']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php
                if ($result->num_rows != 0) { ?>
                    <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-end align-items-center">
                        <ul class="pagination justify-content-center" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(12px); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.25); box-shadow: 0 6px 24px rgba(0, 0, 0, 0.5); padding: 12px 20px;">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>" style="background-color: rgba(255, 255, 255, 0.15); color: #0e0e0e; border: none; padding: 6px 14px; margin: 0 6px; border-radius: 10px; font-weight: 600; box-shadow: 0 0 6px rgba(255, 255, 255, 0.25); transition: 0.3s ease-in-out;">&laquo; နောက်တစ်ခါ</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($_GET['search'] ?? '') ?>" style="background-color: <?= ($p == $page) ? 'rgba(80, 77, 77, 0.49)' : 'rgba(255, 255, 255, 0.1)' ?>; color: #000; font-weight: 600; padding: 6px 14px; margin: 0 6px; border-radius: 10px; border: none; box-shadow: <?= ($p == $page) ? '0 0 10px rgba(255, 255, 255, 0.5)' : '0 0 5px rgba(255, 255, 255, 0.1)' ?>; transition: 0.3s ease-in-out;"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>" style="background-color: rgba(255, 255, 255, 0.15); color: #0e0e0e; border: none; padding: 6px 14px; margin: 0 6px; border-radius: 10px; font-weight: 600; box-shadow: 0 0 6px rgba(255, 255, 255, 0.25); transition: 0.3s ease-in-out;">ရှေ့သို့ &raquo;</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php }
                ?>
                <!-- Pagination (unchanged) -->

            </div>
        </div>
    </div>
</div>

<!-- Styles remain unchanged -->
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