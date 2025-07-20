<?php require_once('../layout/header.php'); ?>
<?php require_once('../require/db.php'); ?>
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
                            <th>ID</th>
                            <th>User</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Activity Level</th>
                            <th>Goal</th>
                            <th>BMR</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT us.*, u.name AS user_name FROM user_surveys us JOIN users u ON us.user_id = u.id";
                        $result = $mysqli->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['user_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['age']) . '</td>';
                                echo '<td>' . htmlspecialchars(ucfirst($row['gender'])) . '</td>';
                                echo '<td>' . htmlspecialchars($row['weight']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['height']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['activity_level']) . '</td>';
                                echo '<td>' . htmlspecialchars(ucfirst($row['goal'])) . '</td>';
                                echo '<td>' . htmlspecialchars($row['bmr']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                                echo '<td>';
                                echo '<a href="user_surveys_edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary me-2">Edit</a>';
                                echo '<a href="user_surveys_delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this survey?\')">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="11" class="text-center">No user surveys found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
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
    border: 1px solid rgba(255,255,255,0.3);
}
.glass-table th, .glass-table td {
    background: rgba(255,255,255,0.10) !important;
    border: 1px solid rgba(255,255,255,0.18) !important;
}
</style>
<?php require_once('../layout/footer.php'); ?> 