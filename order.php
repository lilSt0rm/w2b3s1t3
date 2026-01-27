<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in if (!isset($_SESSION['user_id'])) { header("Location: login.php");exit();}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate order reference
    $order_reference = 'CMD-' . date('Ymd') . '-' . substr(uniqid(), -6);
    $user_id = $_SESSION['user_id'];
    
    // Collect form data
    $product_type = $_POST['product_type'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;
    $format = $_POST['format'] ?? '';
    $paper_type = $_POST['paper_type'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $color_mode = $_POST['color_mode'] ?? '';
    $sides = $_POST['sides'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $estimated_budget = $_POST['estimated_budget'] ?? NULL;
    
    // Handle finishings (array to string)
    $finishings = '';
    if (isset($_POST['finishings']) && is_array($_POST['finishings'])) {
        $finishings = implode(', ', $_POST['finishings']);
    }
    
    // Handle file uploads
    $uploaded_files = [];
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        $upload_dir = 'uploads/orders/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
            if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = time() . '_' . basename($_FILES['files']['name'][$i]);
                $file_path = $upload_dir . $file_name;
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $file_path)) {
                    $uploaded_files[] = $file_path;
                }
            }
        }
    }
    
    $files = implode('|', $uploaded_files);
    
    // Insert order into database
    try {
        $stmt = $pdo->prepare("
            INSERT INTO orders 
            (order_reference, user_id, product_type, quantity, format, paper_type, deadline, 
             color_mode, sides, finishings, files, notes, estimated_budget, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        
        $stmt->execute([
            $order_reference,
            $user_id,
            $product_type,
            $quantity,
            $format,
            $paper_type,
            $deadline,
            $color_mode,
            $sides,
            $finishings,
            $files,
            $notes,
            $estimated_budget
        ]);
        
        $order_id = $pdo->lastInsertId();
        
        // Redirect to success page or show success message
        $_SESSION['success_message'] = "Commande #$order_reference créée avec succès!";
        header("Location: order_success.php?ref=" . $order_reference);
        exit();
        
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la création de la commande: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer Commande - LOMPUB Batna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Main content */
        .main-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            color: #111;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.5;
        }

        .form-box {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 50px;
        }

        h2 {
            color: #222;
            margin-top: 30px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 250px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .required {
            color: #e74c3c;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 14px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 15px;
            font-family: 'Montserrat', sans-serif;
            transition: border 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #111;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* File upload styling */
        .file-upload {
            border: 2px dashed #ccc;
            padding: 40px 20px;
            text-align: center;
            border-radius: 8px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload:hover {
            border-color: #111;
            background: #f8f8f8;
        }

        .file-upload i {
            font-size: 48px;
            color: #777;
            margin-bottom: 15px;
        }

        .file-upload p {
            margin: 5px 0;
            color: #555;
        }

        .file-upload-subtext {
            font-size: 14px;
            color: #888;
        }

        .file-upload input {
            display: none;
        }

        .file-list {
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .file-list p {
            font-size: 14px;
            margin: 8px 0;
            padding: 8px 12px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #111;
        }

        /* Error/Success messages */
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Submit button */
        .submit-btn {
            background: #111;
            color: #fff;
            border: none;
            padding: 16px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 30px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s, transform 0.2s;
        }

        .submit-btn:hover {
            background: #000;
            transform: translateY(-2px);
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 30px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                margin: 20px auto;
                padding: 0 15px;
            }

            .form-box {
                padding: 25px;
            }

            h1 {
                font-size: 26px;
            }

            .form-row {
                flex-direction: column;
                gap: 15px;
            }

            .form-row .form-group {
                min-width: 100%;
            }

            .contact-info-top {
                justify-content: center;
                text-align: center;
                margin-bottom: 10px;
            }

            .top-bar-content {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .form-box {
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            h2 {
                font-size: 18px;
            }

            .submit-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
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

    <!-- Header -->
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
                    <li><a href="index.php#services">Nos services</a></li>
                    <li><a href="products.php">Produits</a></li>
                    <li><a href="index.php#about">À propos</a></li>
                    <li><a href="index.php#contact">Contactez-nous</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="my_orders.php">Mes Commandes</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="user-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="margin-right: 15px; color: #111; font-weight: 500;">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?>
                    </span>
                    <a href="logout.php" id="logoutBtn" style="color: #e74c3c;">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php else: ?>
                    <a href="login.php" style="margin-right: 15px; color: #111;">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="register.php" style="color: #111;">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                <?php endif; ?>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Menu -->
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
                <a href="index.php#services" class="mobile-menu-link">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Nos services</span>
                </a>
                <a href="products.php" class="mobile-menu-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Produits</span>
                </a>
                <a href="index.php#about" class="mobile-menu-link">
                    <i class="fas fa-info-circle"></i>
                    <span>À propos</span>
                </a>
                <a href="order.php" class="mobile-menu-link active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commander</span>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="my_orders.php" class="mobile-menu-link">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Mes Commandes</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="mobile-cta-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="mobile-user-info" style="padding: 15px; background: #f8f8f8; margin-bottom: 10px;">
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?></span>
                </div>
                <a href="logout.php" class="mobile-menu-link" style="color: #e74c3c;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            <?php else: ?>
                <a href="login.php" class="mobile-menu-link">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Se connecter</span>
                </a>
                <a href="register.php" class="mobile-menu-link">
                    <i class="fas fa-user-plus"></i>
                    <span>Créer un compte</span>
                </a>
            <?php endif; ?>
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container breadcrumb-content">
            <a href="index.php">Accueil</a>
            <i class="fas fa-chevron-right"></i>
            <a href="products.php">Nos Produits</a>
            <i class="fas fa-chevron-right"></i>
            <span>Passer Commande</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <h1>Commande d'Impression</h1>
        <p class="subtitle">
            Cet espace est réservé aux sous-traitants pour le dépôt de commandes d'impression.
            Remplissez le formulaire ci-dessous pour soumettre votre demande de production.
        </p>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success_message']); 
                unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <div class="form-box">
            <form method="POST" action="" enctype="multipart/form-data">
                <!-- Order details -->
                <h2>Détails d'Impression</h2>

                <div class="form-group">
                    <label>Type de Produit <span class="required">*</span></label>
                    <select name="product_type" required>
                        <option value="">— Sélectionner —</option>
                        <option value="Cartes de visite">Cartes de visite</option>
                        <option value="Flyers / Tracts">Flyers / Tracts</option>
                        <option value="Affiches (Posters)">Affiches (Posters)</option>
                        <option value="Dépliants">Dépliants</option>
                        <option value="Brochures / Catalogues">Brochures / Catalogues</option>
                        <option value="Bâche publicitaire">Bâche publicitaire</option>
                        <option value="Autocollant / Sticker">Autocollant / Sticker</option>
                        <option value="One Way Vision">One Way Vision</option>
                        <option value="Enseigne / Signalétique">Enseigne / Signalétique</option>
                        <option value="Habillage véhicule">Habillage véhicule</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Quantité <span class="required">*</span></label>
                        <input type="number" name="quantity" min="1" placeholder="ex: 500, 1000, 5000" required>
                    </div>

                    <div class="form-group">
                        <label>Format <span class="required">*</span></label>
                        <input type="text" name="format" placeholder="ex: 85x55mm, A4, A3, personnalisé" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Type de Papier / Support</label>
                        <select name="paper_type">
                            <option value="">— Sélectionner si applicable —</option>
                            <option value="300g/m² Brillant">300g/m² Brillant</option>
                            <option value="300g/m² Mat">300g/m² Mat</option>
                            <option value="350g/m² Premium">350g/m² Premium</option>
                            <option value="135g/m² Couché brillant">135g/m² Couché brillant</option>
                            <option value="150g/m² Couché">150g/m² Couché</option>
                            <option value="250g/m² Cartonné">250g/m² Cartonné</option>
                            <option value="PVC 0.76mm">PVC 0.76mm</option>
                            <option value="Bâche 440g">Bâche 440g</option>
                            <option value="Autocollant vinyle">Autocollant vinyle</option>
                            <option value="One Way Vision">One Way Vision</option>
                            <option value="Papier photo">Papier photo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Délai souhaité <span class="required">*</span></label>
                        <select name="deadline" required>
                            <option value="">— Sélectionner —</option>
                            <option value="24h (Urgent)">24h (Urgent)</option>
                            <option value="48h (Standard)">48h (Standard)</option>
                            <option value="72h">72h</option>
                            <option value="5 jours ouvrables">5 jours ouvrables</option>
                            <option value="7 jours ouvrables">7 jours ouvrables</option>
                            <option value="À discuter">À discuter</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Mode Couleur</label>
                        <select name="color_mode">
                            <option value="CMJN (Quadrichromie)">CMJN (Quadrichromie)</option>
                            <option value="Pantone (Couleur spot)">Pantone (Couleur spot)</option>
                            <option value="Noir & Blanc">Noir & Blanc</option>
                            <option value="CMJN + Pantone">CMJN + Pantone</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Impression</label>
                        <select name="sides">
                            <option value="Recto uniquement">Recto uniquement</option>
                            <option value="Recto-Verso">Recto-Verso</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Finitions spéciales (si besoin)</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="finishings[]" value="vernis">
                            <span>Vernis sélectif</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="finishings[]" value="pelliculage">
                            <span>Pelliculage</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="finishings[]" value="decoupe">
                            <span>Découpe à la forme</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="finishings[]" value="perforation">
                            <span>Perforation</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="finishings[]" value="pliage">
                            <span>Pliage</span>
                        </label>
                    </div>
                </div>

                <hr>

                <!-- File upload -->
                <h2>Fichiers à Imprimer</h2>

                <div class="form-group">
                    <label>Fichiers d'impression <span class="required">*</span></label>
                    <p style="color: #666; font-size: 14px; margin-bottom: 15px;">
                        Téléchargez vos fichiers prêts à imprimer. Assurez-vous d'inclure les fonds perdus si
                        nécessaires.
                    </p>

                    <div class="file-upload" id="fileUploadArea">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Cliquez pour sélectionner les fichiers</p>
                        <p class="file-upload-subtext">Formats acceptés: PDF, AI, EPS, PSD, TIFF, JPG, PNG (Max. 50MB
                            par fichier)</p>
                        <input type="file" id="fileInput" name="files[]" multiple required
                            accept=".pdf,.ai,.eps,.psd,.tiff,.jpg,.jpeg,.png">
                    </div>

                    <div class="file-list" id="fileList"></div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label>Instructions / Notes additionnelles</label>
                    <textarea name="notes"
                        placeholder="Ex: fond perdu 3mm, exigences colorimétriques spécifiques, format de découpe, urgence particulière, etc."></textarea>
                </div>

                <!-- Budget (optional) -->
                <div class="form-group">
                    <label>Budget estimé (DA) - Optionnel</label>
                    <input type="number" name="estimated_budget" placeholder="Montant approximatif en dinars algériens">
                </div>

                <button class="submit-btn" type="submit">
                    <i class="fas fa-paper-plane"></i> Envoyer la commande
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
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
                        <li><a href="index.php#services">Habillage (Vitrines, Façades, Véhicules)</a></li>
                        <li><a href="index.php#services">Décoration & Design</a></li>
                        <li><a href="index.php#services">Impression & Sérigraphie</a></li>
                        <li><a href="index.php#services">Enseignes & Pub Bus</a></li>
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
        // Mobile Menu Functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : 'auto';
        });

        mobileMenuClose.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
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

        // File Upload Functionality
        const fileInput = document.getElementById('fileInput');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileList = document.getElementById('fileList');

        fileUploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = '#111';
            fileUploadArea.style.background = '#f0f0f0';
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.style.borderColor = '#ccc';
            fileUploadArea.style.background = '#fafafa';
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = '#ccc';
            fileUploadArea.style.background = '#fafafa';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                updateFileList();
            }
        });

        fileInput.addEventListener('change', updateFileList);

        function updateFileList() {
            fileList.innerHTML = '';
            const files = fileInput.files;
            
            if (files.length === 0) {
                fileList.innerHTML = '<p style="color: #888; text-align: center;">Aucun fichier sélectionné</p>';
                return;
            }
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileElement = document.createElement('p');
                fileElement.innerHTML = `
                    <i class="fas fa-file" style="margin-right: 8px;"></i>
                    ${file.name} (${formatFileSize(file.size)})
                `;
                fileList.appendChild(fileElement);
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Initialize file list
        updateFileList();
    </script>
</body>
</html>
