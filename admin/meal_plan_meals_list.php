<?php require_once('../layout/header.php'); ?>
<?php require_once('../require/db.php'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">Meal Plan Meals</h3>
                <button class="btn btn-success fw-bold px-4" onclick="window.location.href='meal_plan_meals_create.php'">
                    <i class="bi bi-plus-lg me-2"></i> Add Meal to Plan
                </button>
            </div>
            <div class="glass-panel p-0">
                <table class="table table-hover table-bordered align-middle mb-0 glass-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Meal Plan Name</th>
                            <th>Meal Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT mpm.id, mp.name AS meal_plan_name, m.name AS meal_name
                                FROM meal_plan_meals mpm
                                JOIN meal_plans mp ON mpm.meal_plan_id = mp.id
                                JOIN meals m ON mpm.meal_id = m.id";
                        $result = $mysqli->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['meal_plan_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['meal_name']) . '</td>';
                                echo '<td>';
                                echo '<a href="meal_plan_meals_edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-primary me-2">Edit</a>';
                                echo '<a href="meal_plan_meals_delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this entry?\')">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">No meal plan meals found.</td></tr>';
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