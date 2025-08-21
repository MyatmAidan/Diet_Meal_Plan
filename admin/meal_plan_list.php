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

// Count total
if ($search_active) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM meal_plans WHERE name LIKE ? OR description LIKE ? OR goal_type LIKE ?");
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $count_result = $stmt->get_result();
} else {
    $count_result = $mysqli->query("SELECT COUNT(*) as total FROM meal_plans");
}
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Get paginated data
if ($search_active) {
    $stmt = $mysqli->prepare("SELECT * FROM meal_plans WHERE name LIKE ? OR description LIKE ? OR goal_type LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $mysqli->prepare("SELECT * FROM meal_plans ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0"><?= __('အစားအစာအစီအစဉ်များ') ?></h3>
                <!-- <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_plan_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> အစားအစာအစီအစဉ်အသစ် ထည့်မည်
                </button> -->
            </div>
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= __('အစားအစာအစီအစဉ်အချက်အလက်များ အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($search): ?>
                <div class="alert alert-info">
                    "<?= htmlspecialchars($search) ?>" <?= __('အတွက် ရလဒ်') ?> <?= $total_rows ?> <?= __('ခု တွေ့ရှိခဲ့သည်။') ?>
                </div>
            <?php endif; ?>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th><?= __('စဉ်') ?></th>
                            <th><?= __('အမည်') ?></th>
                            <th><?= __('ဖော်ပြချက်') ?></th>
                            <th><?= __('ရည်ရွယ်ချက်') ?></th>
                            <th><?= __('လုပ်ဆောင်မှု') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center"><?= __('မည်သည့်ရလဒ်များမရှိပါ') ?></td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= htmlspecialchars($row['goal_type']) ?></td>
                                    <td>
                                        <a href="meal_plan_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary me-2" title="<?= __('ပြင်ဆင်မည်') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete-meal-plan" onclick="deleteFun(<?= $row['id'] ?>, 'mealPlanId')" data-id="<?= $row['id'] ?>" title="<?= __('ဖျက်ရန်') ?>">
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