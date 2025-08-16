<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Get search input
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = '';
$params = [];

if (!empty($search)) {
    $search_sql = "WHERE m.name LIKE ? OR f.name LIKE ? OR mf.quantity LIKE ?";
    $search_term = "%" . $search . "%";
    $params = [$search_term, $search_term, $search_term];
}

// Count total filtered records
$count_stmt = $mysqli->prepare("SELECT COUNT(*) as total
    FROM meal_foods mf
    JOIN meals m ON mf.meal_id = m.id
    JOIN foods f ON mf.food_id = f.id
    $search_sql
");
if (!empty($params)) {
    $count_stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch filtered paginated records
$query = "SELECT mf.id, m.name AS meal_name, f.name AS food_name, mf.quantity
          FROM meal_foods mf
          JOIN meals m ON mf.meal_id = m.id
          JOIN foods f ON mf.food_id = f.id
          $search_sql
          LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($query);

if (!empty($params)) {
    // Add limit and offset to params
    $params[] = $limit;
    $params[] = $offset;
    $types = str_repeat('s', count($params) - 2) . "ii";
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
                <h3 class="fw-bold mb-0">အစားအစာများ</h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_food_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> အသစ်ထည့်ရန်
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
                            <th>အစားအစာအမည်</th>
                            <th>ပါဝင်အစားအစာ</th>
                            <th>ပမာဏ</th>
                            <th>လုပ်ဆောင်မှု</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="9" class="text-center">မည်သည့်ရလဒ်များမရှိပါ</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['meal_name']) ?></td>
                                    <td><?= htmlspecialchars($row['food_name']) ?></td>
                                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-delete-meal-food" onclick="deleteFun(<?= $row['id'] ?>, 'mealFoodId')" data-id="<?= $row['id'] ?>">
                                            ဖျက်မည်
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($total_rows > 0): ?>
                    <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-end align-items-center">
                        <ul class="pagination justify-content-center" style="
                        background: rgba(255, 255, 255, 0.08);
                        backdrop-filter: blur(12px);
                        border-radius: 16px;
                        border: 1px solid rgba(255, 255, 255, 0.25);
                        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.5);
                        padding: 12px 20px;
                    ">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>" style="
                                    background-color: rgba(255, 255, 255, 0.15);
                                    color: #0e0e0e;
                                    border: none;
                                    padding: 6px 14px;
                                    margin: 0 6px;
                                    border-radius: 10px;
                                    font-weight: 600;
                                    box-shadow: 0 0 6px rgba(255, 255, 255, 0.25);
                                    transition: 0.3s ease-in-out;
                                ">&laquo; နောက်သို့</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $p ?>" style="
                                    background-color: <?= ($p == $page) ? 'rgba(80, 77, 77, 0.49)' : 'rgba(255, 255, 255, 0.1)' ?>;
                                    color:  #000;
                                    font-weight: 600;
                                    padding: 6px 14px;
                                    margin: 0 6px;
                                    border-radius: 10px;
                                    border: none;
                                    box-shadow: <?= ($p == $page) ? '0 0 10px rgba(255, 255, 255, 0.5)' : '0 0 5px rgba(255, 255, 255, 0.1)' ?>;
                                    transition: 0.3s ease-in-out;
                                "><?= $p ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>" style="
                                    background-color: rgba(255, 255, 255, 0.15);
                                    color: #0e0e0e;
                                    border: none;
                                    padding: 6px 14px;
                                    margin: 0 6px;
                                    border-radius: 10px;
                                    font-weight: 600;
                                    box-shadow: 0 0 6px rgba(255, 255, 255, 0.25);
                                    transition: 0.3s ease-in-out;
                                ">ရှေ့သို့ &raquo;</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
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