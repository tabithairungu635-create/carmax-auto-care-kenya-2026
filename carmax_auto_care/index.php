<?php
$phone = '254741637219';
$phone2 = '254722113046';

$services = [
    ['01', 'Engine Repair & Diagnostics', 'Expert attention for engine performance and vehicle faults.', 'fa-engine'],
    ['02', 'General Servicing', 'Routine vehicle maintenance and oil service.', 'fa-oil-can'],
    ['03', 'Brake Services', 'Brake inspection, maintenance and replacement.', 'fa-car-burst'],
    ['04', 'Suspension & Steering', 'Care for handling, stability and driving comfort.', 'fa-gears'],
    ['05', 'Battery & Electrical', 'Electrical system and battery checks.', 'fa-car-battery'],
    ['06', 'Tyres & Wheels', 'Tyre and wheel care for safer journeys.', 'fa-circle-dot']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CARMAX AUTO CARE in Kenol, next to Summit Grill. Contact us for automotive repair and vehicle maintenance enquiries.">
    <meta name="theme-color" content="#090909">
    <title>CARMAX AUTO CARE | Professional Automotive Care in Kenol</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800;900&family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="topbar">
    <div class="container topbar-inner">
        <div><i class="fa-solid fa-location-dot"></i> Kenol Town, next to Summit Grill</div>
        <div class="top-contact">
            <a href="tel:0741637219"><i class="fa-solid fa-phone"></i> 0741 637 219</a>
            <a href="tel:0722113046">0722 113 046</a>
        </div>
        <div class="top-social">
            <a href="https://wa.me/<?= $phone ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
    </div>
</div>

<header class="header">
    <div class="container nav-container">
        <a href="index.php" class="brand" aria-label="Carmax Auto Care home">
            <span class="brand-icon"><i class="fa-solid fa-gear"></i><i class="fa-solid fa-wrench"></i></span>
            <span class="brand-name">CAR<span>MAX</span><small>AUTO CARE</small></span>
        </a>

        <button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false">
            <i class="fa-solid fa-bars"></i>
        </button>

        <nav class="navigation" id="navigation">
            <a class="active" href="#home">Home</a>
            <a href="#about">About Us</a>
            <a href="#services">Services</a>
            <a href="#why-us">Why Carmax</a>
            <a href="#contact">Contact</a>
            <a class="nav-book" href="https://wa.me/<?= $phone ?>?text=Hello%20CARMAX%20AUTO%20CARE%2C%20I%20would%20like%20to%20make%20an%20appointment." target="_blank" rel="noopener">Book a Service <i class="fa-solid fa-arrow-right"></i></a>
        </nav>
    </div>
</header>

<main>
    <section class="hero" id="home">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="hero-copy">
                <span class="eyebrow"><span></span> PROFESSIONAL AUTOMOTIVE CARE</span>
                <h1>YOUR CAR.<br><span>OUR PRIORITY.</span></h1>
                <p>Reliable automotive care for the journeys that matter. Discover professional vehicle repair and maintenance in Kenol.</p>
                <div class="hero-actions">
                    <a href="#contact" class="btn btn-gold"><i class="fa-regular fa-calendar-check"></i> Get in Touch <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="#services" class="btn btn-outline">Explore Services</a>
                </div>
                <div class="hero-note"><i class="fa-solid fa-location-dot"></i> Proudly located in Kenol Town</div>
            </div>
            <div class="hero-stamp">
                <span>CARMAX</span>
                <strong>AUTO CARE</strong>
                <small>DRIVE BETTER. LIVE SAFER.</small>
            </div>
        </div>
        <div class="hero-bottom-line"></div>
    </section>

    <section class="service-strip" id="services">
        <div class="container">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">WHAT WE DO</span>
                    <h2>OUR <span>SERVICES</span></h2>
                </div>
                <p>Automotive care under one roof.</p>
            </div>

            <div class="service-grid">
                <?php foreach ($services as $service): ?>
                    <article class="service-card">
                        <div class="service-number"><?= htmlspecialchars($service[0]) ?></div>
                        <div class="service-icon"><i class="fa-solid <?= htmlspecialchars($service[3]) ?>"></i></div>
                        <h3><?= htmlspecialchars($service[1]) ?></h3>
                        <p><?= htmlspecialchars($service[2]) ?></p>
                        <a href="#contact" aria-label="Enquire about <?= htmlspecialchars($service[1]) ?>">Enquire <i class="fa-solid fa-arrow-right"></i></a>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="provisional-note">Service categories shown are provisional and will be confirmed with the garage.</p>
        </div>
    </section>

    <section class="about-section" id="about">
        <div class="container about-grid">
            <div class="about-visual">
                <div class="about-image">
                    <div class="image-placeholder">
                        <i class="fa-solid fa-car-side"></i>
                        <span>CARMAX AUTO CARE</span>
                        <small>Garage photograph to be added</small>
                    </div>
                </div>
                <div class="experience-tag"><i class="fa-solid fa-shield-halved"></i><span>CARE FOR<br><strong>EVERY JOURNEY</strong></span></div>
            </div>
            <div class="about-copy">
                <span class="eyebrow">GET TO KNOW US</span>
                <h2>ABOUT <span>CARMAX</span><br>AUTO CARE</h2>
                <p>Welcome to CARMAX AUTO CARE, located in Kenol Town next to Summit Grill. We are building a customer-focused automotive care experience centred on helping motorists find the vehicle services they need.</p>
                <p>Our team and service information will be updated with verified details from the garage.</p>
                <a href="#contact" class="text-link">Talk to our team <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="why-section" id="why-us">
        <div class="container">
            <div class="center-heading">
                <span class="eyebrow">THE CARMAX DIFFERENCE</span>
                <h2>YOUR JOURNEY <span>MATTERS</span></h2>
                <p>We want every customer to feel informed, welcomed and confident when seeking vehicle care.</p>
            </div>
            <div class="why-grid">
                <article><i class="fa-solid fa-location-dot"></i><h3>Convenient Location</h3><p>Find us in Kenol Town, next to Summit Grill.</p></article>
                <article><i class="fa-solid fa-comments"></i><h3>Direct Communication</h3><p>Reach the garage directly by phone or WhatsApp.</p></article>
                <article><i class="fa-solid fa-calendar-check"></i><h3>Easy Enquiries</h3><p>Contact us to discuss your vehicle service needs.</p></article>
            </div>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <div class="container contact-grid">
            <div class="contact-copy">
                <span class="eyebrow">LET'S TALK ABOUT YOUR CAR</span>
                <h2>NEED VEHICLE <span>CARE?</span></h2>
                <p>Contact CARMAX AUTO CARE to enquire about services or arrange your visit.</p>
                <div class="contact-detail"><i class="fa-solid fa-phone"></i><div><small>CALL US</small><a href="tel:0741637219">0741 637 219</a><a href="tel:0722113046">0722 113 046</a></div></div>
                <div class="contact-detail"><i class="fa-solid fa-location-dot"></i><div><small>VISIT US</small><span>Kenol Town, next to Summit Grill</span></div></div>
                <a class="btn btn-gold" href="https://wa.me/<?= $phone ?>?text=Hello%20CARMAX%20AUTO%20CARE%2C%20I%20would%20like%20to%20make%20an%20enquiry." target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>

            <div class="enquiry-card">
                <span class="eyebrow">QUICK ENQUIRY</span>
                <h3>How can we help?</h3>
                <p>Fill in your details and continue to WhatsApp to send your enquiry.</p>
                <form id="enquiryForm">
                    <label for="customerName">Your name</label>
                    <input id="customerName" name="name" type="text" placeholder="Enter your name" required maxlength="80">
                    <label for="customerPhone">Phone number</label>
                    <input id="customerPhone" name="phone" type="tel" placeholder="Enter your phone number" required maxlength="20">
                    <label for="customerMessage">How can we help?</label>
                    <textarea id="customerMessage" name="message" rows="4" placeholder="Tell us about your vehicle or enquiry" required maxlength="1000"></textarea>
                    <button class="btn btn-gold full-btn" type="submit">Continue to WhatsApp <i class="fa-brands fa-whatsapp"></i></button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container footer-inner">
        <a href="#home" class="brand footer-brand">
            <span class="brand-icon"><i class="fa-solid fa-gear"></i><i class="fa-solid fa-wrench"></i></span>
            <span class="brand-name">CAR<span>MAX</span><small>AUTO CARE</small></span>
        </a>
        <p>Drive Better. Live Safer.</p>
        <div class="footer-links">
            <a href="#home">Home</a><a href="#about">About</a><a href="#services">Services</a><a href="#why-us">Why Carmax</a><a href="#contact">Contact</a>
        </div>
        <small>&copy; <?= date('Y') ?> CARMAX AUTO CARE. All rights reserved.</small>
        <small class="disclaimer">Website preview content is subject to confirmation by the business.</small>
    </div>
</footer>

<a class="floating-whatsapp" href="https://wa.me/<?= $phone ?>?text=Hello%20CARMAX%20AUTO%20CARE" target="_blank" rel="noopener" aria-label="Chat with Carmax on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>

<script src="assets/js/main.js"></script>
</body>
</html>
