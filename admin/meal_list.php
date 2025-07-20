<?php require_once('../layout/header.php'); ?>
<?php require_once('../require/db.php'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">Meals</h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> Create Meal
                </button>
            </div>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Meal Type</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $mysqli->query("SELECT * FROM meals");
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['meal_type']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                echo '<td>';
                                echo '<a href="meal_edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary me-2">Edit</a>';
                                echo '<a href="meal_delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this meal?\')">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No meals found.</td></tr>';
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