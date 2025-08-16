<?php
require_once('../require/check_auth.php');
check_auth(1);
ob_start();
require_once('../layout/header.php');
require_once('../require/db.php');
$name = $meal_type = $description = '';
$create_msg = '';
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $meal_type = trim($_POST['meal_type']);
    $description = trim($_POST['description']);
    if ($name && $meal_type && $description) {
        $sql = "INSERT INTO `meals` (`name`, `meal_type`, `description`) VALUES ('$name', '$meal_type', '$description')";
        $result = $mysqli->query($sql);
        if ($result) {
            header("Location: $admin_url" . "meal_list.php?msg=Create Successfully!");
            exit();
        } else {
            $create_msg = '<div class=\"alert alert-danger mb-3\">Error: </div>';
        }
    } else {
        $create_msg = '<div class=\"alert alert-warning mb-3\">All fields are required.</div>';
    }
}
ob_end_flush();
?>
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-panel p-4">
                <h3 class="fw-bold mb-3">အစားအစာအသစ်ဖန်တီးပါ</h3>
                <?php echo $create_msg; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" class="form-control glass-input" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="meal_type" class="form-label">အစားအစာအမျိုးအစား</label>
                        <div class="meal-type-selector">
                            <div class="meal-type-option" data-value="breakfast">
                                <i class="fas fa-sun"></i>
                                <span>နံနက်စာ</span>
                            </div>
                            <div class="meal-type-option" data-value="lunch">
                                <i class="fas fa-cloud-sun"></i>
                                <span>နေ့လည်စာ</span>
                            </div>
                            <div class="meal-type-option" data-value="dinner">
                                <i class="fas fa-moon"></i>
                                <span>ညစာ</span>
                            </div>
                            <div class="meal-type-option" data-value="snack">
                                <i class="fas fa-cookie-bite"></i>
                                <span>Snack</span>
                            </div>
                        </div>
                        <input type="hidden" name="meal_type" id="meal_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">ပါဝင်ပစ္စည်းများ</label>
                        <textarea class="form-control glass-input" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="meal_list.php" class="btn btn-secondary">ပယ်ဖျက်ရန်</a>
                        <button type="submit" name="submit" class="btn btn-success fw-bold">အသစ်ထည့်ရန်</button>
                    </div>
                </form>
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

    .glass-input {
        background: rgba(255, 255, 255, 0.25) !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
        color: #222;
    }

    .meal-type-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 8px;
    }

    .meal-type-option {
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        backdrop-filter: blur(8px);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .meal-type-option:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .meal-type-option.active {
        background: rgba(13, 110, 253, 0.2);
        border-color: rgba(13, 110, 253, 0.5);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
    }

    .meal-type-option i {
        font-size: 20px;
        color: #333;
    }

    .meal-type-option.active i {
        color: #0d6efd;
    }

    .meal-type-option span {
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .meal-type-option.active span {
        color: #0d6efd;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mealTypeOptions = document.querySelectorAll('.meal-type-option');
    const mealTypeInput = document.getElementById('meal_type');

    mealTypeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            mealTypeOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Update hidden input value
            mealTypeInput.value = this.getAttribute('data-value');
        });
    });
});
</script>
<?php require_once('../layout/footer.php'); ?>