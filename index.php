<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Corner - Achieve Your Dream Body</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm mt-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/logo.png" alt="Diet Corner Logo" style="height:40px;" class="me-2">
                <span class="fw-bold fs-4">Diet Corner</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNav">
                <!-- <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                </ul> -->
                <div class="mx-end">
                    <a href="./login.php" class="btn btn-warning rounded-pill px-4 ms-lg-3">Login</a>
                    <a href="./register.php" class="btn btn-warning rounded-pill px-4 ms-lg-3">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section py-5 mt-3 mb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <p class="text-uppercase text-warning fw-semibold mb-2" style="letter-spacing:2px;">Eat Smart, Live Well</p>
                    <h1 class="display-5 fw-bold mb-3">Achieve Your Dream Body with Our <span class="text-primary">Diet</span></h1>
                    <p class="mb-4 text-secondary">Personalized meal plans, expert tips, and real progress. Start your journey to health today.</p>
                    <a href="#" class="btn btn-warning btn-lg rounded-pill px-5">Join Now</a>
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
            <h2 class="fw-bold mb-4">How It <span class="text-warning">Work</span></h2>
            <p class="text-secondary mb-5">Start your health journey in 3 easy steps.</p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-person-lines-fill fs-1 text-warning mb-3"></i>
                        <h5 class="fw-bold">1. Create Your Profile</h5>
                        <p class="text-secondary">Tell us about your goals, body type, and lifestyle.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-clipboard-check fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold">2. Get Your Meal Plan</h5>
                        <p class="text-secondary">Receive a customized plan crafted by nutritionists.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-emoji-smile fs-1 text-success mb-3"></i>
                        <h5 class="fw-bold">3. See Results</h5>
                        <p class="text-secondary">Track progress and adjust as your needs evolve.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose-us py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Choose <span class="text-warning">Us</span></h2>
                <p class="text-secondary">We provide science-backed, expert-crafted nutrition solutions.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-3">
                    <div class="feature-card glass p-4 text-center h-100">
                        <div class="mb-3"><i class="bi bi-shield-check fs-1 text-warning"></i></div>
                        <h5 class="fw-bold mb-2">Safe & Certified</h5>
                        <p class="text-secondary small">Professionally approved plans customized just for you.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card glass p-4 text-center h-100">
                        <div class="mb-3"><i class="bi bi-heart-pulse fs-1 text-danger"></i></div>
                        <h5 class="fw-bold mb-2">Health-First Approach</h5>
                        <p class="text-secondary small">Designed with your health and goals in mind.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card glass p-4 text-center h-100">
                        <div class="mb-3"><i class="bi bi-clock fs-1 text-info"></i></div>
                        <h5 class="fw-bold mb-2">Fast Support</h5>
                        <p class="text-secondary small">Get help and coaching from our experts anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Meal Plans -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured <span class="text-warning">Meal Plans</span></h2>
            <p class="text-secondary">Tailored diet strategies to fit your lifestyle and goals.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="product-card glass p-3 text-center h-100">
                    <img src="./images/meal1.avif" alt="Weight Loss Plan" class="img-fluid mb-3 rounded">
                    <div class="fw-bold">Weight Loss Plan</div>
                    <p class="text-secondary small">Low-calorie meals to support your fat-burning goals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-card glass p-3 text-center h-100">
                    <img src="./images/meal4.avif" alt="Muscle Gain Plan" class="img-fluid mb-3 rounded">
                    <div class="fw-bold">Muscle Gain Plan</div>
                    <p class="text-secondary small">Protein-packed meals for muscle building and recovery.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-card glass p-3 text-center h-100">
                    <img src="./images/meal3.avif" alt="Vegan Plan" class="img-fluid mb-3 rounded">
                    <div class="fw-bold">Vegan Plan</div>
                    <p class="text-secondary small">Plant-based options without sacrificing nutrition.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-light-blue">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">What Our <span class="text-warning">Users Say</span></h2>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="glass p-4">
                        <p>"I lost 10kg in 3 months! The meal plans are super effective and easy to follow."</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- Sarah W.</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4">
                        <p>"As a vegan, I finally found a plan that fits me and tastes amazing."</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- David L.</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4">
                        <p>"The design is beautiful and the content is backed by real science."</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- Jamie R.</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- More Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">More <span class="text-warning">Happy Clients</span></h2>
                <p class="text-secondary">Real stories, real results. Here’s what more of our users had to say.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <p>"As someone who struggles with PCOS, their personalized plan helped me manage weight better than ever!"</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- Amanda C.</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <p>"Incredible support and meal tracking system. I feel stronger and healthier each week."</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- James K.</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <p>"The vegan options are top-notch. I love how easy it is to stick to the plan."</p>
                        <div class="text-warning">★★★★★</div>
                        <strong>- Leena M.</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 bg-light-blue">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Get in <span class="text-warning">Touch</span></h2>
                <p class="text-secondary">Have questions or want to customize your plan? Reach out below.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="glass p-4 h-100">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4" placeholder="Write your message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning px-4 rounded-pill">Send</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass h-100">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387190.2799147942!2d-74.25986768767196!3d40.69767006472171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c250b41f2788f9%3A0x804e4e5f6bbbc6d4!2sNew%20York%2C%20USA!5e0!3m2!1sen!2s!4v1678273870934!5m2!1sen!2s"
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
            <h2 class="fw-bold mb-4">Ready to Transform Your <span class="text-warning">Health?</span></h2>
            <p class="text-secondary mb-5">Join thousands who trust Diet Corner to guide their wellness journey.</p>
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-graph-up-arrow fs-1 text-success mb-3"></i>
                        <h5 class="fw-bold">Trackable Progress</h5>
                        <p class="text-secondary small">Monitor your results with smart tools and visual insights.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-bag-heart fs-1 text-danger mb-3"></i>
                        <h5 class="fw-bold">Personalized Plans</h5>
                        <p class="text-secondary small">Tailored to your body type, goals, and preferences.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass p-4 h-100">
                        <i class="bi bi-emoji-smile fs-1 text-warning mb-3"></i>
                        <h5 class="fw-bold">Feel Confident</h5>
                        <p class="text-secondary small">Experience higher energy, confidence, and wellbeing daily.</p>
                    </div>
                </div>
            </div>
            <a href="./register.php" class="btn btn-warning btn-lg rounded-pill px-5">Get Started Now</a>
        </div>
    </section>
    <!-- Footer -->
    <footer class="mt-5 text-center text-muted py-4 bg-white">
        &copy; <?php echo date('Y'); ?> Diet Corner. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>