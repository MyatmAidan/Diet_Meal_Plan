<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_active = $search !== '';
$like = "%$search%";

// Count total records
if ($search_active) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as total 
        FROM meal_plan_meals mpm 
        JOIN meal_plans mp ON mpm.meal_plan_id = mp.id 
        JOIN meals m ON mpm.meal_id = m.id 
        WHERE mp.name LIKE ? OR m.name LIKE ?");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $count_result = $stmt->get_result();
} else {
    $count_result = $mysqli->query("SELECT COUNT(*) as total FROM meal_plan_meals");
}
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch paginated records
if ($search_active) {
    $stmt = $mysqli->prepare("SELECT mpm.id, mp.name AS meal_plan_name, m.name AS meal_name
        FROM meal_plan_meals mpm
        JOIN meal_plans mp ON mpm.meal_plan_id = mp.id
        JOIN meals m ON mpm.meal_id = m.id
        WHERE mp.name LIKE ? OR m.name LIKE ?
        ORDER BY mpm.id DESC
        LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $mysqli->prepare("SELECT mpm.id, mp.name AS meal_plan_name, m.name AS meal_name
        FROM meal_plan_meals mpm
        JOIN meal_plans mp ON mpm.meal_plan_id = mp.id
        JOIN meals m ON mpm.meal_id = m.id
        ORDER BY mpm.id DESC
        LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">အစားအစာအစီအစဉ်အတွက် အစားအစာများ</h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_plan_meals_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> အစားအစာ ထည့်ရန်
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
                            <th>နံပါတ်</th>
                            <th>အစားအစာအစီအစဉ်အမည်</th>
                            <th>အစားအစာအမည်</th>
                            <th>လုပ်ဆောင်မှုများ</th>
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
                                    <td><?= htmlspecialchars($row['meal_plan_name']) ?></td>
                                    <td><?= htmlspecialchars($row['meal_name']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-delete-meal-plan-meal" onclick="deleteFun(<?= $row['id'] ?>, 'mealPlanMealId')" data-id="<?= $row['id'] ?>">
                                            ဖျက်ရန်
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
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" style="
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
                                    <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>" style="
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
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" style="
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