<?php
require_once('../require/common.php');
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Diet Corner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <button id="toggleSidebar" class="btn btn-light glass-panel">
        <i class="bi bi-list fs-4"></i>
    </button>
    <div id="wrapper">
        <!-- Sidebar -->
        <?php if ($is_admin) { ?>
            <aside id="sidebar" class="p-4">
                <div class="text-center mb-4">
                    <img src="../images/logo.png" alt="Logo" style="height:48px;">
                    <h5 class="mt-2 fw-bold">Admin</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="<?= $admin_url ?>index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>user_list.php"><i class="bi bi-people me-2"></i>Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>meal_list.php"><i class="bi bi-journal-text me-2"></i>Meal</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>meal_plan_list.php"><i class="bi bi-journal-text me-2"></i>Meal Plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>food_list.php"><i class="bi bi-egg-fried me-2"></i>Foods</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>meal_plan_meals_list.php"><i class="bi bi-link-45deg me-2"></i>Meal Plan Meals</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>meal_food_list.php"><i class="bi bi-bar-chart-line me-2"></i>Meal Food</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>user_surveys_list.php"><i class="bi bi-clipboard-data me-2"></i>User Surveys</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>user_diet_recommendations_list.php"><i class="bi bi-lightbulb me-2"></i>Diet Recommendations</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $admin_url ?>user_progress_list.php"><i class="bi bi-graph-up-arrow me-2"></i>User Progress</a></li>
                    <li class="nav-item mt-3"><a class="nav-link text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </aside>
        <?php } else { ?>
            <aside id="sidebar" class="p-4">
                <div class="text-center mb-4">
                    <img src="../images/logo.png" alt="Logo" style="height:48px;">
                    <h5 class="mt-2 fw-bold">User</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="<?= $user_url ?>index.php">
                            <i class="bi bi-house-door me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'planner.php' ? 'active' : '' ?>" href="<?= $user_url ?>planner.php">
                            <i class="bi bi-calendar-check me-2"></i>Meal Plan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'survey.php' ? 'active' : '' ?>" href="<?= $user_url ?>survey.php">
                            <i class="bi bi-clipboard-check me-2"></i>Health Survey
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </aside>
        <?php } ?>

        <!-- Main Content -->
        <div id="mainContent">
            <div class="container-fluid px-5 mt-3">
                <nav class="navbar glass-panel px-4 py-2 row sticky-top">
                    <!-- Left: Page title -->
                    <div class="fw-bold fs-5 col-md-3"><?= $is_admin ? 'Admin Dashboard' : 'User Dashboard' ?></div>

                    <!-- Middle: Glassmorphic Search Bar -->
                    <div class="col-md-7">
                        <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                            <div class="glass-panel d-flex align-items-center px-3 py-1 rounded-pill" style="width: 100%;">
                                <i class="bi bi-search me-2 text-dark"></i>
                                <input class="form-control border-0 bg-transparent shadow-none text-dark"
                                    type="search"
                                    placeholder="Search..."
                                    aria-label="Search"
                                    style="background: transparent !important;">
                            </div>
                        </form>
                    </div>

                    <!-- Right: User Profile -->
                    <div class="d-flex align-items-center justify-content-end gap-2 col-md-2">
                        <img src="https://i.pravatar.cc/40?img=3" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                        <span class="fw-semibold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                    </div>
                </nav>
            </div>