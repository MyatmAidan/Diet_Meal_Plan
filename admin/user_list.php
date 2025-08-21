<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../require/i18n.php');
require_once('../layout/header.php');
require_once('../require/db.php');
require_once('../require/common.php');

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Get search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$like = "%{$search}%";

// Count total records
if ($search) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM users WHERE name LIKE ? OR email LIKE ?");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $count_result = $stmt->get_result();
} else {
    $count_result = $mysqli->query("SELECT COUNT(*) as total FROM users");
}
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch records
if ($search) {
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY role DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $mysqli->prepare("SELECT * FROM users ORDER BY role DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0"><?= __('အသုံးပြုသူစာရင်း') ?></h3>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= __('အသုံးပြုသူအချက်အလက်များ အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။') ?>
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
                            <th><?= __('နံပါတ်') ?></th>
                            <th><?= __('အမည်') ?></th>
                            <th><?= __('အီးမေးလ်') ?></th>
                            <th><?= __('အခန်းကဏ္ဍ') ?></th>
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
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= $row['role'] == 1 ? __("အက်ဒမင်") : __("အသုံးပြုသူ") ?></td>
                                    <td>
                                        <a href="user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info me-2" title="<?= __('ကြည့်မည်') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary me-2" title="<?= __('ပြင်ဆင်မည်') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete" onclick="deleteFun(<?= $row['id'] ?>, 'userId')" data-id="<?= $row['id'] ?>" title="<?= __('ဖျက်မည်') ?>">
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

<?php require_once('../layout/footer.php') ?>