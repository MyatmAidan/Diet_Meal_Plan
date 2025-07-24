<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');


$limit = 5; // Records per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Count total records
$count_result = $mysqli->query("SELECT COUNT(*) as total FROM meal_plans");
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch paginated records
$result = $mysqli->query("SELECT * FROM meal_plans LIMIT $limit OFFSET $offset");
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">Meal Plans</h3>
                <!-- <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_plan_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> Create Meal Plan
                </button> -->
            </div>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Goal Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['goal_type']) ?></td>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete-meal-plan').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
</script>