<?php
require_once('../require/check_auth.php');
check_auth(1);
require_once('../layout/header.php');
require_once('../require/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Check user existence
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        http_response_code(404);
        echo "User not found.";
        exit;
    }

    // Check for foreign key usage (e.g., user_progress, user_survey, etc.)
    $used = $mysqli->query("SELECT * FROM user_progress WHERE user_id = $user_id LIMIT 1")->num_rows
        + $mysqli->query("SELECT * FROM user_surveys WHERE user_id = $user_id LIMIT 1")->num_rows;

    if ($used > 0) {
        http_response_code(409);
        echo "User has related data and cannot be deleted.";
        exit;
    }

    // Proceed to delete
    $delete = $mysqli->prepare("DELETE FROM users WHERE id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();

    echo "success";
}

$limit = 5; // Records per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Count total records
$count_result = $mysqli->query("SELECT COUNT(*) as total FROM users");
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch paginated records
$result = $mysqli->query("SELECT * FROM users LIMIT $limit OFFSET $offset");
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">User List</h3>
                <!-- <a class="btn btn-success fw-bold px-4" href="<?= $admin_url ?>user_create.php">
                    <i class="bi bi-person-plus me-2"></i> Create User
                </a> -->
            </div>
            <?php echo $create_msg; ?>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $offset + 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= $row['role'] == 1 ? "admin" : "user" ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" data-id="<?= $row['id'] ?>">
                                        Delete
                                    </button>
                                </td>
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
<?php require_once('../layout/footer.php') ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-danger[data-id]').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the user!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#999',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('user_list.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'user_id=' + userId
                            })
                            .then(response => {
                                console.log(response);
                            })
                        // .then(data => {
                        //     if (data === 'success') {
                        //         Swal.fire('Deleted!', 'User has been deleted.', 'success')
                        //             .then(() => location.reload());
                        //     } else {
                        //         // Swal.fire('Error', data, 'error');
                        //     }
                        // });
                    }
                });
            });
        });
    });
</script>