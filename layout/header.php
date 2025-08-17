<?php
$admin_url = "http://localhost/healthy_meal_plan/admin/";
$user_url = "http://localhost/healthy_meal_plan/user/";
$base_url = "http://localhost/healthy_meal_plan/";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .profile-avatar i {
            font-size: 20px;
            color: #fff;
        }

        .profile-avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background: rgba(13, 110, 253, 0.1);
        }
    </style>
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
                    <h5 class="mt-2 fw-bold">အက်မင်</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="http://localhost/healthy_meal_plan/index.php"><i class="bi bi-speedometer2 me-2"></i>မူလစာမျက်နှာ</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href=" <?= $admin_url ?>index.php"><i class="bi bi-speedometer2 me-2"></i>ဒက်ရှ်ဘုတ်</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>user_list.php"><i class="bi bi-people me-2"></i>အသုံးပြုသူများ</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'meal_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>meal_list.php"><i class="bi bi-journal-text me-2"></i>အစားအစာ</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'meal_plan_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>meal_plan_list.php"><i class="bi bi-journal-text me-2"></i>အစားအစာအစီအစဉ်များ</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'food_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>food_list.php"><i class="bi bi-egg-fried me-2"></i>အစားအသောက်</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'meal_plan_meals_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>meal_plan_meals_list.php"><i class="bi bi-link-45deg me-2"></i>အစားအစာအစီအစဉ် အချက်အလက်</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'meal_food_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>meal_food_list.php"><i class="bi bi-bar-chart-line me-2"></i>အစားအစာပါဝင်မှု</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_surveys_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>user_surveys_list.php"><i class="bi bi-clipboard-data me-2"></i>အသုံးပြုသူ စစ်တမ်းများ</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_diet_recommendations_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>user_diet_recommendations_list.php"><i class="bi bi-lightbulb me-2"></i>အစားအသောက် အကြံပြုချက်များ</a></li>
                    <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_progress_list.php' ? 'active' : '' ?>" href="<?= $admin_url ?>user_progress_list.php"><i class="bi bi-graph-up-arrow me-2"></i>တိုးတက်မှု မှတ်တမ်း</a></li>
                    <li class="nav-item mt-3"><a class="nav-link text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>ထွက်ရန်</a></li>
                </ul>
            </aside>
        <?php } else { ?>
            <aside id="sidebar" class="p-4">
                <div class="text-center mb-4">
                    <img src="../images/logo.png" alt="Logo" style="height:48px;">
                    <h5 class="mt-2 fw-bold">အသုံးပြုသူ</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="http://localhost/healthy_meal_plan/index.php"><i class="bi bi-speedometer2 me-2"></i>မူလစာမျက်နှာ</a></li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="<?= $user_url ?>index.php">
                            <i class="bi bi-house-door me-2"></i>ဒက်ရှ်ဘုတ်
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'planner.php' ? 'active' : '' ?>" href="<?= $user_url ?>planner.php">
                            <i class="bi bi-calendar-check me-2"></i>အစားအသောက်အစီအစဉ်
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'survey.php' ? 'active' : '' ?>" href="<?= $user_url ?>survey.php">
                            <i class="bi bi-clipboard-check me-2"></i>ကျန်းမာရေး စစ်တမ်း
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-danger" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>ထွက်ရန်
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
                    <div class="fw-bold fs-5 col-md-3"><?= $is_admin ? 'အက်မင် ဒက်ရှ်ဘုတ်' : 'အသုံးပြုသူ ဒက်ရှ်ဘုတ်' ?></div>

                    <!-- Middle: Glassmorphic Search Bar -->
                    <div class="col-md-7">
                        <?php if (str_ends_with(basename($_SERVER['PHP_SELF']), '_list.php')): ?>
                            <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                                <div class="glass-panel d-flex align-items-center px-3 py-1 rounded-pill" style="width: 100%;">
                                    <i class="bi bi-search me-2 text-dark"></i>
                                    <input class="form-control border-0 bg-transparent shadow-none text-dark"
                                        name="search"
                                        type="search"
                                        placeholder="ရှာဖွေရန်..."
                                        aria-label="Search"
                                        style="background: transparent !important;">
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Right: User Profile -->
                    <div class="d-flex align-items-center justify-content-end gap-2 col-md-2">
                        <div class="dropdown">
                            <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" style="cursor: pointer;">
                                <?php
                                // Get user image from database
                                $user_image = $_SESSION['user_img'];
                                ?>
                                <?php if (isset($user_image) && !empty($user_image)): ?>
                                    <img src="images/<?= htmlspecialchars($user_data['image']) ?>"
                                        alt="Profile" class="profile-avatar-img">
                                <?php else: ?>
                                    <div class="profile-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="fw-semibold"><?= $_SESSION['user_name'] ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= $is_admin ? $admin_url . 'profile.php' : $user_url . 'profile.php' ?>">
                                        <i class="fas fa-user me-2"></i>ပရိုဖိုင်
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="../logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>ထွက်ရန်
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>