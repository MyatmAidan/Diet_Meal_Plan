<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

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
                <h3 class="fw-bold mb-0">အသုံးပြုသူစာရင်း</h3>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    အသုံးပြုသူအချက်အလက်များ အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

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
                            <th>အမည်</th>
                            <th>အီးမေးလ်</th>
                            <th>အခန်းကဏ္ဍ</th>
                            <th>လုပ်ဆောင်မှု</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center">မည်သည့်ရလဒ်များမရှိပါ</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= $row['role'] == 1 ? "အက်ဒမင်" : "အသုံးပြုသူ" ?></td>
                                    <td>
                                        <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary me-2">
                                            ပြင်ဆင်မည်
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete" onclick="deleteFun(<?= $row['id'] ?>, 'userId')" data-id="<?= $row['id'] ?>">
                                            ဖျက်မည်
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($total_rows > 0): ?>
                    <nav aria-label="စာမျက်နှာသွားရန်" class="mt-4 d-flex justify-content-end align-items-center">
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

<?php require_once('../layout/footer.php') ?>