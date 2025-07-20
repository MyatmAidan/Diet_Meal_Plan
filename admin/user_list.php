<?php require_once('../layout/header.php'); ?>
<?php require_once('../require/db.php'); ?>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $mysqli->query("SELECT * FROM users");
                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                $role = $row['role'] == 1 ? 'Admin' : 'User';
                                echo '<tr>';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                                echo '<td>' . $role . '</td>';
                                echo '<td>';
                                echo '<a href="edit_user.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary me-2">Edit</a>';
                                echo '<a href="delete_user.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No users found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once('../layout/footer.php') ?>