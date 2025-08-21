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
                <h3 class="fw-bold mb-0"><?= __('အစားအစာအစီအစဉ်အတွက် အစားအစာများ') ?></h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_plan_meals_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> <?= __('အစားအစာ ထည့်ရန်') ?>
                </button>
            </div>
            <?php if ($search): ?>
                <div class="alert alert-info">
                    "<?= htmlspecialchars($search) ?>" <?= __('အတွက် ရလဒ်') ?> <?= $total_rows ?> <?= __('ခု တွေ့ရှိခဲ့သည်။') ?>
                </div>
            <?php endif; ?>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th><?= __('နံပါတ်') ?></th>
                            <th><?= __('အစားအစာအစီအစဉ်အမည်') ?></th>
                            <th><?= __('အစားအစာအမည်') ?></th>
                            <th><?= __('လုပ်ဆောင်မှုများ') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="9" class="text-center"><?= __('မည်သည့်ရလဒ်များမရှိပါ') ?></td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['meal_plan_name']) ?></td>
                                    <td><?= htmlspecialchars($row['meal_name']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-delete-meal-plan-meal" onclick="deleteFun(<?= $row['id'] ?>, 'mealPlanMealId')" data-id="<?= $row['id'] ?>" title="<?= __('ဖျက်ရန်') ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($total_rows > 0): ?>
                    <?= generate_pagination($page, $total_pages, $search) ?>
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