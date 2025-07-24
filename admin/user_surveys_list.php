<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

$limit = 5; // Records per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Count total records
$count_result = $mysqli->query("SELECT COUNT(*) as total FROM user_surveys");
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch paginated records
$result = $mysqli->query("SELECT us.*, u.name AS user_name 
FROM user_surveys us JOIN users u ON us.user_id = u.id
 LIMIT $limit OFFSET $offset");
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">User Surveys</h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='user_surveys_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> Add Survey
                </button>
            </div>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Activity Level</th>
                            <th>Goal</th>
                            <th>BMR</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
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
                ">&laquo; Prev</a>
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
                ">Next &raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
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