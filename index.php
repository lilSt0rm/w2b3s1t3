<?php
require_once 'config/database.php';
// Add session checks for protected pages
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOMPUB - Agence de Publicité Batna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="contact-info-top">
                <div class="contact-item-top">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>N31, Rue D.E, Batna, Algérie 05000</span>
                </div>
                <div class="contact-item-top">
                    <i class="fas fa-mobile-alt"></i>
                    <span>+213 560 33 63 25 / +213 698 99 86 42</span>
                </div>
                <div class="contact-item-top">
                    <i class="fas fa-envelope"></i>
                    <span>lompubatna@gmail.com</span>
                </div>
            </div>
            <div class="social-top">
                <a href="#" class="social-link-top">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-link-top">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-link-top">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    <header class="fixed-header">
        <div class="container header-container">
            <div class="logo">
                <a href="index.php" class="logo-container">
                    <img src="assets/logo.png" alt="LOMPUB Logo" class="logo-img">
                </a>
            </div>

            <nav class="desktop-nav">
                <ul class="desktop-nav-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="#services">Nos services</a></li>
                    <li><a href="#products">Produits</a></li>
                    <li><a href="#about">À propos</a></li>
                    <li><a href="#contact">Contactez-nous</a></li>
                </ul>

                <div class="auth-buttons">
                    <a href="login.php" class="btn-login">Espace Sous-traitant</a>
                </div>
            </nav>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="mobile-logo">
                <img src="assets/logo.png" alt="LOMPUB Logo" class="mobile-logo-img">
            </div>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-contact-info">
            <div class="mobile-contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div class="mobile-contact-text">
                    <span class="mobile-contact-value">N31, Rue D.E, Batna, Algérie 05000</span>
                </div>
            </div>
            <div class="mobile-contact-item">
                <i class="fas fa-phone-alt"></i>
                <div class="mobile-contact-text">
                    <span class="mobile-contact-value">+213 560 33 63 25</span>
                </div>
            </div>
            <div class="mobile-contact-item">
                <i class="fas fa-envelope"></i>
                <div class="mobile-contact-text">
                    <span class="mobile-contact-value">lompubatna@gmail.com</span>
                </div>
            </div>
            <div class="mobile-contact-item">
                <i class="fas fa-clock"></i>
                <div class="mobile-contact-text">
                    <span class="mobile-contact-value">Samedi - Jeudi: 08h - 18h</span>
                </div>
            </div>
            <div class="mobile-menu-section">
                <a href="index.php" class="mobile-menu-link">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>
                <a href="#services" class="mobile-menu-link">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Nos services</span>
                </a>
                <a href="products.php" class="mobile-menu-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Produits</span>
                </a>
                <a href="#about" class="mobile-menu-link">
                    <i class="fas fa-info-circle"></i>
                    <span>À propos</span>
                </a>
                <a href="login.php" class="mobile-menu-link">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Espace Sous-traitant</span>
                </a>
            </div>
        </div>
        <div class="mobile-copyright">
            <div class="mobile-contact-social">
                <a href="#" class="mobile-social-link">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="mobile-social-link">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="mobile-social-link">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="menu-overlay" id="menuOverlay"></div>

    <section class="hero" id="home">
        <div class="slider" id="slider">
            <div class="slides">
                <div class="slide"
                    style="background: linear-gradient(rgba(230, 57, 70, 0.85), rgba(29, 29, 29, 0.9)), url('assets/01.png') center/cover no-repeat;">
                    <div class="slide-content">
                        <h2>LOMPUB - Votre Partenaire en Communication</h2>
                        <p>Spécialistes en publicité, habillage, décoration et impression depuis 2008 à Batna</p>
                    </div>
                </div>
                <div class="slide"
                    style="background: linear-gradient(rgba(29, 29, 29, 0.8), rgba(29, 29, 29, 0.9)), url('assets/01.png') center/cover no-repeat;">
                    <div class="slide-content">
                        <h2>Excellence et Satisfaction Client</h2>
                        <p>Notre mission: atteindre l'excellence en termes de satisfaction client avec un rapport
                            qualité-prix imbattable</p>
                    </div>
                </div>
                <div class="slide"
                    style="background: linear-gradient(rgba(51, 51, 51, 0.8), rgba(51, 51, 51, 0.9)), url('assets/Design.png') center/cover no-repeat;">
                    <div class="slide-content">
                        <h2>Large Gamme de Services</h2>
                        <p>De l'habillage de véhicules à la sérigraphie, en passant par la programmation et les
                            enseignes</p>
                    </div>
                </div>
            </div>
            <button class="slider-btn prev" id="prevBtn">&#10094;</button>
            <button class="slider-btn next" id="nextBtn">&#10095;</button>
        </div>
        <div class="slider-dots" id="sliderDots"></div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <div class="section-title">
                <h2>Nos Services</h2>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <img src="assets/Design.png" alt="Décoration & Design">
                    <div class="service-info">
                        <h3>Décoration & Design</h3>
                    </div>
                </div>
                <div class="service-card">
                    <img src="assets/HABILLAGE.png" alt="Décoration & Design">
                    <div class="service-info">
                        <h3>HABILLAGE</h3>
                    </div>
                </div>
                <div class="service-card">
                    <img src="assets/HABILLAGE.png" alt="Décoration & Design">
                    <div class="service-info">
                        <h3>Impression & Sérigraphie</h3>
                    </div>
                </div>
                <div class="service-card">
                    <img src="assets/HABILLAGE.png" alt="Décoration & Design">
                    <div class="service-info">
                        <h3>Enseignes & Pub Bus</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="products" id="products">
        <div class="container">
            <div class="section-title">
                <h2>Nos Produits</h2>
            </div>

            <div class="products-grid">
                <div class="product-card">
                    <div class="product-img"
                        style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('assets/img1.png') center/cover no-repeat;">
                    </div>
                    <div class="product-info">
                        <h3>Cartes de visite</h3>
                        <button class="btn">Commander</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img"
                        style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('assets/img1.png') center/cover no-repeat;">
                    </div>
                    <div class="product-info">
                        <h3>Flyers</h3>
                        <button class="btn">Commander</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img"
                        style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('assets/img1.png') center/cover no-repeat;">
                    </div>
                    <div class="product-info">
                        <h3>Bannières</h3>
                        <button class="btn">Commander</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img"
                        style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('assets/img1.png') center/cover no-repeat;">
                    </div>
                    <div class="product-info">
                        <h3>Posters</h3>
                        <button class="btn">Commander</button>
                    </div>
                </div>
            </div>

            <div class="second-row-products">
                <div class="large-product-card">
                    <div class="large-product-img"
                        style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('assets/img1.png') center/cover no-repeat;">
                    </div>
                    <div class="large-product-info">
                        <h3>Impression Small Format</h3>
                        <a href="#" class="btn-format" id="smallFormatBtn">
                            <i class="fas fa-external-link-alt"></i> Affiche ici
                        </a>
                    </div>
                </div>
                <div class="large-product-card">
                    <div class="large-product-img"
                        style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('assets/img1.png') center/cover no-repeat;">
                    </div>
                    <div class="large-product-info">
                        <h3>Impression Grand Format</h3>
                        <a href="#" class="btn-format" id="grandFormatBtn">
                            <i class="fas fa-external-link-alt"></i> Affiche ici
                        </a>
                    </div>
                </div>
            </div>

            <div class="view-all-products">
                <a href="products.php" class="btn btn-secondary" id="viewAllProducts">
                    <i class="fas fa-eye"></i> Afficher tous les produits
                </a>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <div class="section-title">
                <h2>À propos de LOMPUB</h2>
            </div>
            <div class="about-content">
                <div class="about-text">
                    <p>L'agence LOMPUB Batna, fondée en 2008 par Mme FATIMA HAMIDI, est spécialisée dans le secteur de
                        la communication et la publicité.</p>
                    <p>Notre compétence et expérience dans le domaine nous permettent de vous offrir une large gamme de
                        services avec un excellent rapport qualité-prix.</p>
                    <p>Le but de l'agence est d'atteindre l'excellence en termes de satisfaction client, en proposant
                        des solutions innovantes et adaptées à chaque besoin.</p>

                    <a href="#contact" class="btn" style="margin-top: 20px;">Contactez-nous</a>
                </div>
                <div class="about-image">
                    <div
                        style="width: 100%; height: 300px; background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/01.png') center/cover no-repeat; display: flex; justify-content: center; align-items: center; color: var(--primary-white); font-size: 1.5rem;">
                        <i class="fas fa-award" style="font-size: 4rem; margin-right: 15px;"></i>
                        Excellence depuis 2008
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="advantages">
        <div class="container">
            <div class="section-title">
                <h2>Nos Avantages</h2>
            </div>
            <div class="advantages-single-line">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Livraison Rapide</h3>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Design Personnalisé</h3>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>Qualité Garantie</h3>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Support Client</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-payment" id="contact">
        <div class="container">
            <div class="contact-payment-container">
                <div class="contact-info">
                    <h3>Contactez LOMPUB</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <p><strong>Adresse:</strong> N31, Rue D.E, Batna, Algérie 05000</p>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <p><strong>Téléphone:</strong> +213 560 33 63 25 / +213 698 99 86 42</p>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <p><strong>Email:</strong> lompubatna@gmail.com</p>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <p><strong>Horaires:</strong> Samedi - Jeudi: 08h - 20h</p>
                        </div>
                    </div>
                    <div class="hours">
                        <h4>Besoin d'un devis?</h4>
                        <p>Contactez-nous pour un devis personnalisé gratuit</p>
                    </div>
                </div>

                <div class="payment-methods">
                    <h3>Méthodes de Paiement</h3>
                    <p>Nous acceptons les méthodes de paiement suivantes pour votre commodité:</p>
                    <div class="payment-icons">
                        <div class="payment-icon">
                            <i class="fab fa-cc-visa"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fab fa-cc-mastercard"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="payment-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                    </div>
                    <p style="margin-top: 20px; font-size: 0.9rem; color: var(--medium-gray);">Paiement à la livraison
                        disponible dans tout Batna et ses environs.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-about">
                    <div class="footer-logo">
                        <img src="assets/logo.png" alt="LOMPUB Logo" class="footer-logo-img">
                    </div>
                    <p>Agence de publicité et communication basée à Batna depuis 2008. Spécialistes en habillage,
                        décoration, design, impression, sérigraphie, enseignes et publicité sur bus.</p>
                </div>

                <div class="footer-links">
                    <h3>Services LOMPUB</h3>
                    <ul>
                        <li><a href="#services">Habillage (Vitrines, Façades, Véhicules)</a></li>
                        <li><a href="#services">Décoration & Design</a></li>
                        <li><a href="#services">Impression & Sérigraphie</a></li>
                        <li><a href="#services">Enseignes & Pub Bus</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h3>Contactez-nous</h3>
                    <p><i class="fas fa-map-marker-alt"></i> N31, Rue D.E, Batna, Algérie 05000</p>
                    <p><i class="fas fa-phone"></i> +213 560 33 63 25</p>
                    <p><i class="fas fa-phone"></i> +213 698 99 86 42</p>
                    <p><i class="fas fa-envelope"></i> lompubatna@gmail.com</p>
                    <p><i class="fas fa-clock"></i> Samedi - Jeudi: 08h - 18h</p>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2025 LOMPUB Batna - Agence de Publicité. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : 'auto';
        });

        menuOverlay.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        });

        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });

        const slides = document.querySelector('.slides');
        const slidesCount = document.querySelectorAll('.slide').length;
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const sliderDots = document.getElementById('sliderDots');

        let currentSlide = 0;

        for (let i = 0; i < slidesCount; i++) {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            sliderDots.appendChild(dot);
        }

        function goToSlide(n) {
            currentSlide = (n + slidesCount) % slidesCount;
            slides.style.transform = `translateX(-${currentSlide * 100}%)`;

            document.querySelectorAll('.dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        prevBtn.addEventListener('click', () => {
            goToSlide(currentSlide - 1);
        });

        nextBtn.addEventListener('click', () => {
            goToSlide(currentSlide + 1);
        });

        setInterval(() => {
            goToSlide(currentSlide + 1);
        }, 6000);

        const orderButtons = document.querySelectorAll('.product-card .btn');
        let itemCount = 0;

        orderButtons.forEach(button => {
            button.addEventListener('click', () => {
                itemCount++;

                alert('Service/Produit ajouté au panier!');
            });
        });

        const viewAllProductsBtn = document.getElementById('viewAllProducts');
        viewAllProductsBtn.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Page "Tous les produits" - En développement. Cette fonctionnalité affichera tous nos produits disponibles.');
        });

        const smallFormatBtn = document.getElementById('smallFormatBtn');
        smallFormatBtn.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Page "Impression Small Format" - En développement. Cette page affichera tous nos produits en petit format.');
        });

        const grandFormatBtn = document.getElementById('grandFormatBtn');
        grandFormatBtn.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Page "Impression Grand Format" - En développement. Cette page affichera tous nos produits en grand format.');
        });

        const loginButtons = document.querySelectorAll('.btn-login');
        loginButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Page de connexion - En développement');
            });
        });

        const signupButtons = document.querySelectorAll('.btn-signup');
        signupButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Page d\'inscription - En développement');
            });
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 120,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>