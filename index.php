<?php
session_start();
require_once './require/i18n.php';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(html_lang()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Corner - <?= __('သင့်စိတ်ကူးယဉ်ခန္ဓာကိုယ်အတွက်အာဟာရအစီအစဉ်') ?></title>
    <script>
        (function() {
            try {
                var stored = localStorage.getItem('theme');
                var theme = stored === 'dark' || stored === 'light' ? stored : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.colorScheme = theme;
            } catch (e) {}
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm mt-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/logo.png" alt="Diet Corner Logo" style="height:40px;" class="me-2 theme-invert">
                <span class="fw-bold fs-4">Diet Corner</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
                <div class="mx-end navbar-nav">
                    <button id="themeToggle" class="btn btn-sm btn-outline-secondary theme-toggle me-2" title="Toggle theme">
                        <i data-theme-toggle-icon class="bi bi-moon"></i>
                    </button>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <a href="./<?= $_SESSION['user_role'] == 1 ? 'admin' : 'user' ?>/index.php" class="btn btn-warning rounded-pill px-4 ms-lg-3 nav-item"><?= __('ဒက်ရှ်ဘုတ်') ?></a>
                    <?php } else { ?>
                        <a href="./login.php" class="btn btn-warning rounded-pill px-4 ms-lg-3 nav-item"><?= __('လော့ဂ်အင်') ?></a>
                        <a href="./register.php" class="btn btn-warning rounded-pill px-4 ms-lg-3 nav-item"><?= __('အကောင့်ဖွင့်ရန်') ?></a>
                    <?php } ?>
                    <div class="dropdown d-inline-block ms-2">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <?= strtoupper($_SESSION['lang'] ?? 'my') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="require/set_lang.php?lang=my">MY - Burmese</a></li>
                            <li><a class="dropdown-item" href="require/set_lang.php?lang=en">EN - English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section py-5 mt-3 mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <p class="text-uppercase text-warning fw-semibold mb-2" style="letter-spacing:2px;"><?= __('ဖြောင့်တောက်စွာစားပြီး ကျန်းမာရေးရှိပါစေ') ?></p>
                    <h1 class="display-5 fw-bold mb-3"><?= __('ကျွန်ုပ်တို့၏ အာဟာရအစီအစဉ် ဖြင့် သင့်စိတ်ကူးယဉ်ခန္ဓာကိုယ်ကို ပိုင်ဆိုင်လိုက်ပါ') ?></h1>
                    <p class="mb-4 text-secondary"><?= __('မိတ်ဆွေအတွက်သီးသန့်ချမှတ်ထားသောအစားအစာအစီအစဉ်များ၊ ကျွမ်းကျင်သူများ၏အကြံပြုချက်များနှင့် တကယ်အကျိုးရှိသောဖော်ပြချက်များ') ?></p>
                    <a href="#" class="btn btn-warning btn-lg rounded-pill px-5"><?= __('ယနေ့တင် စတင်လိုက်ပါ') ?></a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="./images/background-removebg-preview.png" alt="Healthy Food" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Guide -->
    <section class="how-it-works py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4"><?= __('ဘယ်လို အလုပ်လုပ်လဲ') ?></h2>
            <p class="text-secondary mb-5"><?= __('အဆင့် ၃ ဆင့်ဖြင့် စတင်နိုင်ပါတယ်။') ?></p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-person-lines-fill fs-1 text-warning mb-3"></i>
                        <h5 class="fw-bold"><?= __('၁။ ကိုယ်ရေးအချက်အလက်ဖြည့်ပါ') ?></h5>
                        <p class="text-secondary"><?= __('သင့်ရည်မှန်းချက်များ၊ ခန္ဓာကိုယ်အမျိုးအစားနှင့် အသက်မွေးမှုပုံစံကိုပြောပြပါ။') ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-clipboard-check fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold"><?= __('၂။ သင့်အတွက်အစားအစာအစီအစဉ်ရယူပါ') ?></h5>
                        <p class="text-secondary"><?= __('အာဟာရပညာရှင်များကရေးဆွဲပေးတဲ့အစီအစဉ်ကိုရယူပါ။') ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-emoji-smile fs-1 text-success mb-3"></i>
                        <h5 class="fw-bold"><?= __('၃။ ရလဒ်မြင်တွေ့ပါ') ?></h5>
                        <p class="text-secondary"><?= __('ရလဒ်ကိုကြည့်ပြီး အခြေအနေအပေါ် မူတည်၍ ပြင်ဆင်နိုင်ပါတယ်။') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose-us py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold"><?= __('ဘာကြောင့် ကျွန်ုပ်တို့ကိုရွေးချယ်သင့်သလဲ') ?></h2>
                <p class="text-secondary"><?= __('သိပ္ပံအထောက်အထားရှိတဲ့ အာဟာရဖြေရှင်းချက်များကိုပေးဆောင်ပါတယ်။') ?></p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-3">
                    <div class="feature-card glass p-4 text-center h-100">
                        <div class="mb-3"><i class="bi bi-shield-check fs-1 text-warning"></i></div>
                        <h5 class="fw-bold mb-2"><?= __('လုံခြုံမှုရှိပြီး မှန်ကန်သော') ?></h5>
                        <p class="text-secondary small"><?= __('သင်အတွက်သီးသန့်အပ်နှံရေးဆွဲထားသော စံသတ်မှတ်ထားသောအစီအစဉ်များ။') ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card glass p-4 text-center h-100">
                        <div class="mb-3"><i class="bi bi-heart-pulse fs-1 text-danger"></i></div>
                        <h5 class="fw-bold mb-2"><?= __('ကျန်းမာရေး ဦးစားပေး') ?></h5>
                        <p class="text-secondary small"><?= __('သင်၏ရည်မှန်းချက်နှင့်ကျန်းမာရေးအခြေအနေအပေါ်အခြေခံထားသည်။') ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card glass p-4 text-center h-100">
                        <div class="mb-3"><i class="bi bi-clock fs-1 text-info"></i></div>
                        <h5 class="fw-bold mb-2"><?= __('အမြန်တုံ့ပြန်မှု') ?></h5>
                        <p class="text-secondary small"><?= __('လိုအပ်တဲ့အခါမှာ အကူအညီများရယူနိုင်ပါတယ်။') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Meal Plans -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?= __('အထူး အစားအစာအစီအစဉ်များ') ?></h2>
            <p class="text-secondary"><?= __('သင့်ရဲ့ဘဝနဲ့ရည်မှန်းချက်ကိုကိုက်ညီအောင်လုပ်ပေးထားပါတယ်။') ?></p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="product-card glass p-3 text-center h-100">
                    <img src="./images/meal1.avif" alt="Weight Loss Plan" class="img-fluid mb-3 rounded">
                    <div class="fw-bold"><?= __('အလေးချိန်လျှော့အစီအစဉ်') ?></div>
                    <p class="text-secondary small"><?= __('ကယ်လိုရီနည်းတဲ့အစားအစာများဖြင့် ကိုယ်အလေးချိန်လျှော့ချနိုင်သည်။') ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-card glass p-3 text-center h-100">
                    <img src="./images/meal4.avif" alt="Muscle Gain Plan" class="img-fluid mb-3 rounded">
                    <div class="fw-bold"><?= __('ကြွက်သားတိုးမြှင့်အစီအစဉ်') ?></div>
                    <p class="text-secondary small"><?= __('ပရိုတိန်းများစွာပါတဲ့ အစားအစာများဖြင့် ကြွက်သားတိုးတက်မှုအတွက်။') ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-card glass p-3 text-center h-100">
                    <img src="./images/meal3.avif" alt="Vegan Plan" class="img-fluid mb-3 rounded">
                    <div class="fw-bold"><?= __('သက်သတ်လွတ်အစီအစဉ်') ?></div>
                    <p class="text-secondary small"><?= __('အာဟာရမပျက်အောင် သက်သတ်လွတ်အစားအစာများဖြင့်စီစဉ်ထားသည်။') ?></p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold"><?= __('ပျော်ရွှင်သော ဖောက်သည်များ ထပ်မံကြည့်ရန်') ?></h2>
                <p class="text-secondary"><?= __('မှန်ကန်သောအတွေ့အကြုံများ၊ ရလဒ်များမှန်ကန်သည်။ ကျွန်ုပ်တို့၏အသုံးပြုသူများအချို့က ပြောခဲ့သည်မှာ—') ?></p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <p>"<?= __('PCOS မျိုးစုံအခက်အခဲများရှိတဲ့ကျွန်မအတွက် သူတို့ရဲ့ ကိုယ်ပိုင်အစီအစဉ်က အလွန်ထိရောက်ခဲ့ပါတယ်။') ?>"</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>-​ဒေစီ.</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <p>"<?= __('အထောက်အပံ့ကောင်းလှပြီး အစားအသောက်ခြေရာခံမှုစနစ်ကလည်းအံ့သြဖွယ်ဖြစ်ပါတယ်။ အားကောင်းလာတာကိုအပတ်စဉ်ခံစားနေရပါတယ်။') ?>"</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- ဂျိမ်း .</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <p>"<?= __('ဗီဂန်အစားအစာရွေးချယ်မှုက အရမ်းကောင်းပါတယ်။ အစီအစဉ်နဲ့အတူလိုက်နာရတာလွယ်ကူပါတယ်။') ?>"</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- လီနာ .</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 bg-light-blue">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold"><?= __('ဆက်သွယ်ရန် ကျွန်ုပ်တို့ထံ') ?></h2>
                <p class="text-secondary"><?= __('မေးခွန်းများရှိပါက သို့မဟုတ် သင့်အစီအစဉ်ကိုစိတ်ကြိုက်ပြင်ဆင်လိုပါက အောက်တွင် ဆက်သွယ်ပါ။') ?></p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="glass p-4 h-100">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label"><?= __('သင့်အမည်') ?></label>
                                <input type="text" class="form-control" id="name" placeholder="<?= __('သင့်အမည်ထည့်ပါ') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= __('သင့်အီးမေးလ်') ?></label>
                                <input type="email" class="form-control" id="email" placeholder="<?= __('သင့်အီးမေးလ်ထည့်ပါ') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label"><?= __('မက်ဆေ့ချ်') ?></label>
                                <textarea class="form-control" id="message" rows="4" placeholder="<?= __('သင့်မက်ဆေ့ခ်ျရေးပါ') ?>"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning px-4 rounded-pill"><?= __('ပေးပို့ပါ') ?></button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass h-100">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3700.3681834817835!2d96.0865280749442!3d21.958827979933567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30cb6d4000000011%3A0xf4128067a4b710ea!2sStrategy%20First%20College%20-%20Mandalay!5e0!3m2!1sen!2smm!4v1755755029708!5m2!1sen!2smm"
                            width="100%" height="350" style="border:0; border-radius:16px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Final CTA Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4"><?= __('သင့်ကျန်းမာရေးကို ပြောင်းလဲဖို့ အသင့်ဖြစ်ပြီလား?') ?></h2>
            <p class="text-secondary mb-5"><?= __('Diet Corner ကို ယုံကြည်အားထားပြီး ကျန်းမာရေးစတင်ပြောင်းလဲနေကြသူများ ထောင်ပေါင်းများစွာထဲက တစ်ဦးဖြစ်လိုက်ပါ။') ?></p>
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-graph-up-arrow fs-1 text-success mb-3"></i>
                        <h5 class="fw-bold"><?= __('အောင်မြင်မှု တိတိကျကျ') ?></h5>
                        <p class="text-secondary small"><?= __('ရလဒ်များကို အလွယ်တကူကြည့်ရှုနိုင်သော ကိရိယာများဖြင့် စနစ်တကျစစ်ဆေးနိုင်ပါသည်။') ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-bag-heart fs-1 text-danger mb-3"></i>
                        <h5 class="fw-bold"><?= __('ကိုယ်ပိုင်အစီအစဉ်များ') ?></h5>
                        <p class="text-secondary small"><?= __('သင့်ရဲ့ခန္ဓာကိုယ်အမျိုးအစား၊ ရည်မှန်းချက်နဲ့ စိတ်ကြိုက်ချက်အပေါ် မူတည်၍ ပြင်ဆင်ပေးထားသည်။') ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-emoji-smile fs-1 text-warning mb-3"></i>
                        <h5 class="fw-bold"><?= __('ယုံကြည်မှု တိုးတက်လာမယ်') ?></h5>
                        <p class="text-secondary small"><?= __('စွမ်းအင်၊ ယုံကြည်မှုနဲ့ စိတ်ကျေနပ်မှု တိုးလာတာကို နေ့စဉ်ခံစားနိုင်မယ်။') ?></p>
                    </div>
                </div>
            </div>
            <a href="./register.php" class="btn btn-warning btn-lg rounded-pill px-5"><?= __('အခုမှ စတင်လိုက်ပါ') ?></a>
        </div>
    </section>
    <!-- Footer -->
    <footer class="mt-5 text-center text-muted py-4 bg-white">
        &copy; <?php echo date('Y'); ?> Diet Corner. <?= __('မူပိုင်ခွင့်ရှိပါသည်။') ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>