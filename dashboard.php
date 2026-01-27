<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
requireLogin();

$user = getUserById($_SESSION['user_id']);
$conn = getConnection();

// Get user stats
$stmt = $conn->prepare("
    SELECT 
        COUNT(CASE WHEN status = 'processing' THEN 1 END) as active_orders,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_orders,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders
    FROM orders 
    WHERE user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get recent orders
$stmt = $conn->prepare("
    SELECT * FROM orders 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent activities
$stmt = $conn->prepare("
    SELECT * FROM activities 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Sous-traitant - LOMPUB Batna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Dashboard Specific Styles */
        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #111 0%, #333 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-section h1 {
            color: white;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .welcome-section p {
            color: #ddd;
            font-size: 16px;
            opacity: 0.9;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #111;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 32px;
            color: #111;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #111;
            margin-bottom: 5px;
        }

        .stat-card .trend {
            font-size: 14px;
            color: #4CAF50;
        }

        .stat-card .trend.down {
            color: #f44336;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .dashboard-card h2 {
            color: #111;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            font-size: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-card h2 a {
            font-size: 14px;
            color: #111;
            text-decoration: none;
            font-weight: 500;
        }

        .dashboard-card h2 a:hover {
            text-decoration: underline;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .action-btn {
            background: #f8f9fa;
            border: 2px dashed #ddd;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .action-btn:hover {
            border-color: #111;
            background: #f0f0f0;
            transform: translateY(-3px);
        }

        .action-btn i {
            font-size: 24px;
            color: #111;
            margin-bottom: 10px;
        }

        .action-btn span {
            display: block;
            font-weight: 600;
            color: #333;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .orders-table th {
            text-align: left;
            padding: 15px;
            background: #f8f9fa;
            color: #666;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #eee;
        }

        .orders-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .orders-table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-actions {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view {
            background: #e9ecef;
            color: #495057;
        }

        .btn-track {
            background: #111;
            color: white;
        }

        /* Recent Activity */
        .activity-list {
            list-style: none;
            padding: 0;
        }

        .activity-item {
            padding: 20px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111;
            font-size: 16px;
        }

        .activity-content h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #333;
        }

        .activity-content p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }

        .activity-time {
            font-size: 12px;
            color: #999;
        }

        /* Profile Section */
        .profile-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #111 0%, #333 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 600;
        }

        .profile-info h3 {
            margin: 0 0 5px 0;
            font-size: 20px;
            color: #111;
        }

        .profile-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .detail-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .detail-item label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item span {
            font-weight: 600;
            color: #111;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .orders-table {
                display: block;
                overflow-x: auto;
            }

            .dashboard-card {
                padding: 20px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .order-actions {
                flex-direction: column;
            }

            .btn-small {
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
                    <li><a href="dashboard-soustraitant.php" class="active">Tableau de Bord</a></li>
                    <li><a href="logout.php" id="logoutBtn" style="color: #e74c3c;"><i class="fas fa-sign-out-alt"></i>
                            Déconnexion</a></li>
                </ul>
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
            <a href="dashboard-soustraitant.php" class="mobile-menu-link active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de Bord</span>
            </a>
            <a href="order.php" class="mobile-menu-link">
                <i class="fas fa-shopping-cart"></i>
                <span>Nouvelle Commande</span>
            </a>
        </div>
        <div class="mobile-cta-buttons">
            <a href="#" class="mobile-menu-link" id="mobileLogoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
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
            <span>Tableau de Bord Sous-traitant</span>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="welcome-section">
                <h1>Bienvenue, <span id="userName"><?php echo htmlspecialchars($user['full_name']); ?></span></h1>
                <p>Votre espace sous-traitant pour gérer vos commandes d'impression</p>
            </div>
            <div class="quick-actions">
                <a href="order.php" class="action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nouvelle Commande</span>
                </a>
                <a href="#mes-commandes" class="action-btn">
                    <i class="fas fa-history"></i>
                    <span>Voir l'Historique</span>
                </a>
                <a href="#fichiers" class="action-btn">
                    <i class="fas fa-folder-open"></i>
                    <span>Fichiers</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <i class="fas fa-shopping-cart"></i>
                <h3>Commandes Actives</h3>
                <div class="number"><?php echo $stats['active_orders'] ?? 0; ?></div>
                <div class="trend">+2 cette semaine</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>Commandes Terminées</h3>
                <div class="number"><?php echo $stats['completed_orders'] ?? 0; ?></div>
                <div class="trend">+5 ce mois</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>En Attente</h3>
                <div class="number"><?php echo $stats['pending_orders'] ?? 0; ?></div>
                <div class="trend">-1 cette semaine</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-money-bill-wave"></i>
                <h3>Solde Dû</h3>
                <div class="number">45,800 DA</div>
                <div class="trend down">Échéance: 15 jours</div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-header">
                <div class="profile-avatar">MA</div>
                <div class="profile-info">
                    <h3>Mohamed Ali</h3>
                    <p>Sous-traitant depuis Janvier 2023</p>
                </div>
            </div>
            <div class="profile-details">
                <div class="detail-item">
                    <label>Société</label>
                    <span>Print Solutions SARL</span>
                </div>
                <div class="detail-item">
                    <label>Email</label>
                    <span>contact@printsolutions.dz</span>
                </div>
                <div class="detail-item">
                    <label>Téléphone</label>
                    <span>+213 550 12 34 56</span>
                </div>
                <div class="detail-item">
                    <label>Adresse</label>
                    <span>123 Rue des Entrepreneurs, Batna</span>
                </div>
                <div class="detail-item">
                    <label>Type de Sous-traitance</label>
                    <span>Impression Grand Format</span>
                </div>
                <div class="detail-item">
                    <label>Note</label>
                    <span>★★★★☆ (4.2/5)</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Main Content - Recent Orders -->
            <div class="dashboard-card" id="mes-commandes">
                <h2>
                    Mes Commandes Récentes
                    <a href="#voir-tout">Voir tout <i class="fas fa-arrow-right"></i></a>
                </h2>

                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_reference']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_type']); ?></td>
                            <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                            <td><span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php 
                                $status_texts = [
                                    'pending' => 'En attente',
                                    'processing' => 'En cours',
                                    'completed' => 'Terminée',
                                    'cancelled' => 'Annulée'
                                ];
                                echo $status_texts[$order['status']] ?? $order['status'];
                                ?>
                            </span></td>
                            <td class="order-actions">
                                <a href="#" class="btn-small btn-view"><i class="fas fa-eye"></i> Détails</a>
                                <a href="#" class="btn-small btn-track"><i class="fas fa-truck"></i> Suivre</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Sidebar - Recent Activity -->
            <div class="dashboard-card">
                <h2>Activité Récente</h2>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Fichiers téléchargés</h4>
                            <p>Vous avez uploadé 3 fichiers pour CMD-2024-015</p>
                            <div class="activity-time">Il y a 2 heures</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Commande terminée</h4>
                            <p>CMD-2024-014 a été livrée</p>
                            <div class="activity-time">Il y a 1 jour</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Nouveau message</h4>
                            <p>LOMPUB a envoyé un message concernant CMD-2024-013</p>
                            <div class="activity-time">Il y a 2 jours</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Nouvelle commande</h4>
                            <p>Vous avez passé la commande CMD-2024-015</p>
                            <div class="activity-time">Il y a 3 jours</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Paiement reçu</h4>
                            <p>Paiement de 12,500 DA pour CMD-2024-010</p>
                            <div class="activity-time">Il y a 5 jours</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="dashboard-card">
            <h2>Ressources Utiles</h2>
            <div class="quick-actions">
                <a href="products.php" class="action-btn">
                    <i class="fas fa-book"></i>
                    <span>Catalogue Produits</span>
                </a>
                <a href="assets/guides.php" class="action-btn">
                    <i class="fas fa-question-circle"></i>
                    <span>Guides d'Impression</span>
                </a>
                <a href="assets/tarifs.pdf" class="action-btn">
                    <i class="fas fa-file-invoice"></i>
                    <span>Tarifs 2024</span>
                </a>
                <a href="index.php#contact" class="action-btn">
                    <i class="fas fa-headset"></i>
                    <span>Support Technique</span>
                </a>
            </div>
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
                    <p>Agence de publicité et communication basée à Batna depuis 2008. Partenaire de sous-traitants
                        qualifiés pour l'impression et la production publicitaire.</p>
                </div>

                <div class="footer-links">
                    <h3>Espace Sous-traitant</h3>
                    <ul>
                        <li><a href="dashboard-soustraitant.php">Tableau de Bord</a></li>
                        <li><a href="order.php">Passer une Commande</a></li>
                        <li><a href="#mes-commandes">Mes Commandes</a></li>
                        <li><a href="#factures">Factures & Paiements</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h3>Support Sous-traitants</h3>
                    <p><i class="fas fa-user-headset"></i> Support dédié: +213 560 33 63 25</p>
                    <p><i class="fas fa-envelope"></i> soustraitants@lompubatna.com</p>
                    <p><i class="fas fa-clock"></i> Lundi - Samedi: 08h - 17h</p>
                    <p><i class="fas fa-map-marker-alt"></i> N31, Rue D.E, Batna</p>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; 2024 LOMPUB Batna - Espace Sous-traitant. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Functionality
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

        // Logout functionality
        const logoutBtn = document.getElementById('logoutBtn');
        const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');

        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Simulate logout
                localStorage.removeItem('soustraitant_token');
                alert('Déconnexion réussie');
                window.location.href = 'index.php';
            }
        }

        if (logoutBtn) logoutBtn.addEventListener('click', logout);
        if (mobileLogoutBtn) mobileLogoutBtn.addEventListener('click', logout);

        // Simulate user authentication check
        document.addEventListener('DOMContentLoaded', function () {
            const token = localStorage.getItem('soustraitant_token');
            if (!token) {
                // Redirect to login if not authenticated
                window.location.href = 'login.php?redirect=dashboard-soustraitant.php';
            }

            // Load user data from localStorage
            const userData = JSON.parse(localStorage.getItem('soustraitant_data'));
            if (userData && userData.name) {
                document.getElementById('userName').textContent = userData.name;
            }

            // Mark current page in mobile menu
            const currentPage = window.location.pathname.split('/').pop();
            const mobileLinks = document.querySelectorAll('.mobile-menu-link');
            mobileLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });

        // Quick order functionality
        const quickOrderBtns = document.querySelectorAll('.action-btn[href="order.php"]');
        quickOrderBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                window.location.href = 'order.php';
            });
        });
    </script>
</body>

</html>