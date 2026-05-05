<?php
// On démarre la session pour vérifier si l'utilisateur est connecté
session_start();

// Si l'utilisateur n'est pas connecté, on le redirige vers la page de login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - ShopESA</title>
    <!-- Police Barlow Condensed pour les titres bold -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons Font Awesome pour les icônes (livraison, panier, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ============================================================
           VARIABLES & RESET
           ============================================================ */
        :root {
            --noir:       #0d0d0d;
            --noir-nav:   #111111;
            --jaune:      #f0a500;
            --jaune-hover:#d4920a;
            --blanc:      #ffffff;
            --gris-clair: #f5f5f5;
            --gris-texte: #555555;
            --font-titre: 'Barlow Condensed', sans-serif;
            --font-corps: 'Barlow', sans-serif;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-corps);
            color: var(--noir);
            background: var(--blanc);
        }

        a { text-decoration: none; color: inherit; }

        /* ============================================================
           HEADER / NAVBAR
           ============================================================ */
        header {
            background: var(--noir-nav);
            position: sticky; /* Reste en haut au scroll */
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .navbar {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 30px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo ShopESA */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Icône panier du logo */
        .logo-icon {
            width: 42px;
            height: 42px;
            background: var(--jaune);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--noir);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        /* "Shop" en blanc, "ESA" en jaune */
        .logo-text span:first-child {
            font-family: var(--font-titre);
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 1px;
            color: var(--blanc);
        }

        .logo-text span:first-child em {
            color: var(--jaune);
            font-style: normal;
        }

        .logo-text small {
            font-size: 0.6rem;
            color: #aaa;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Navigation centrale */
        nav {
            display: flex;
            align-items: center;
            gap: 35px;
        }

        nav a {
            color: #cccccc;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: color 0.2s;
            position: relative;
        }

        /* Lien actif : couleur jaune + soulignement */
        nav a.active {
            color: var(--jaune);
        }

        nav a.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--jaune);
        }

        nav a:hover { color: var(--blanc); }

        /* Dropdown Catégories */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none; /* Caché par défaut, affiché au hover en JS */
            position: absolute;
            top: calc(100% + 15px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--blanc);
            border-top: 3px solid var(--jaune);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            min-width: 180px;
            z-index: 200;
        }

        .dropdown:hover .dropdown-menu { display: block; }

        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: var(--noir);
            font-size: 0.875rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover {
            background: var(--gris-clair);
            color: var(--jaune);
        }

        /* Icônes à droite (recherche, profil, panier) */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-icons a {
            color: #cccccc;
            font-size: 1.1rem;
            transition: color 0.2s;
            position: relative;
        }

        .nav-icons a:hover { color: var(--jaune); }

        /* Badge rouge sur le panier */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--jaune);
            color: var(--noir);
            font-size: 0.6rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Bouton déconnexion discret */
        .btn-logout {
            background: transparent;
            border: 1px solid #444;
            color: #aaa;
            padding: 6px 14px;
            font-family: var(--font-corps);
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            border-color: var(--jaune);
            color: var(--jaune);
        }

        /* ============================================================
           HERO SECTION
           ============================================================ */
        .hero {
            background: var(--noir);
            min-height: 520px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Image de fond du hero (panier + laptop) */
        .hero-bg {
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1400&q=80') center/cover no-repeat;
            opacity: 0.35; /* Assombrie pour laisser le texte lisible */
        }

        /* Dégradé sur la gauche pour que le texte ressorte */
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0,0,0,0.85) 40%, transparent 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 1300px;
            margin: 0 auto;
            padding: 80px 30px;
            max-width: 650px;
            padding-left: 80px;
        }

        /* "BIENVENUE SUR SHOPESA" en jaune petit */
        .hero-label {
            font-family: var(--font-corps);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--jaune);
            margin-bottom: 16px;
        }

        /* Titre principal en deux lignes */
        .hero-title {
            font-family: var(--font-titre);
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1;
            text-transform: uppercase;
            color: var(--blanc);
            margin-bottom: 5px;
        }

        /* Deuxième ligne en jaune */
        .hero-title-yellow {
            font-family: var(--font-titre);
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1;
            text-transform: uppercase;
            color: var(--jaune);
            margin-bottom: 24px;
        }

        /* Description sous le titre */
        .hero-desc {
            color: #cccccc;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 36px;
            max-width: 450px;
        }

        /* Boutons du hero */
        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Bouton jaune primaire */
        .btn-hero-primary {
            background: var(--jaune);
            color: var(--noir);
            padding: 16px 32px;
            font-family: var(--font-titre);
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-hero-primary:hover {
            background: var(--jaune-hover);
            transform: translateY(-2px);
        }

        /* Bouton blanc outline secondaire */
        .btn-hero-secondary {
            background: transparent;
            color: var(--blanc);
            padding: 16px 32px;
            font-family: var(--font-titre);
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 2px solid var(--blanc);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-hero-secondary:hover {
            background: var(--blanc);
            color: var(--noir);
        }

        /* ============================================================
           BARRE DE FEATURES (livraison, paiement, support, retours)
           ============================================================ */
        .features {
            background: var(--blanc);
            border-bottom: 1px solid #eeeeee;
            padding: 30px 0;
        }

        .features-grid {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 30px;
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 colonnes égales */
            gap: 20px;
        }

        /* Chaque feature */
        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 20px;
            border-right: 1px solid #eeeeee; /* Séparateur vertical */
        }

        .feature-item:last-child { border-right: none; }

        /* Icône jaune */
        .feature-icon {
            font-size: 1.8rem;
            color: var(--jaune);
            flex-shrink: 0;
        }

        .feature-text strong {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--noir);
            margin-bottom: 3px;
        }

        .feature-text span {
            font-size: 0.8rem;
            color: var(--gris-texte);
        }

        /* ============================================================
           SECTION MEILLEURES VENTES
           ============================================================ */
        .best-sellers {
            max-width: 1300px;
            margin: 60px auto;
            padding: 0 30px;
        }

        /* Titre de section centré */
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title {
            font-family: var(--font-titre);
            font-size: 2.2rem;
            font-weight: 900;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--noir);
            margin-bottom: 10px;
        }

        /* Trait jaune centré sous le titre */
        .section-line {
            width: 50px;
            height: 3px;
            background: var(--jaune);
            margin: 0 auto;
        }

        /* Grille de produits : 5 colonnes */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        /* Carte produit */
        .product-card {
            background: var(--blanc);
            border: 1px solid #eeeeee;
            position: relative;
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* Badge de réduction (ex: -20%) */
        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--noir);
            color: var(--blanc);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 8px;
            letter-spacing: 0.5px;
            z-index: 1;
        }

        /* Conteneur image avec fond gris clair */
        .product-img {
            background: var(--gris-clair);
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* L'image couvre tout le conteneur */
            transition: transform 0.3s;
        }

        .product-card:hover .product-img img {
            transform: scale(1.05); /* Zoom léger au hover */
        }

        /* Bouton "Ajouter au panier" qui apparaît au hover */
        .product-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--jaune);
            color: var(--noir);
            text-align: center;
            padding: 10px;
            font-family: var(--font-titre);
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            transform: translateY(100%); /* Caché en bas */
            transition: transform 0.25s;
        }

        .product-card:hover .product-overlay {
            transform: translateY(0); /* Apparaît au hover */
        }

        /* Infos produit sous l'image */
        .product-info {
            padding: 14px;
        }

        .product-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--noir);
            margin-bottom: 8px;
            white-space: nowrap;    /* Pas de retour à la ligne */
            overflow: hidden;
            text-overflow: ellipsis; /* "..." si le texte est trop long */
        }

        .product-prices {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Prix actuel en jaune/or */
        .price-current {
            font-family: var(--font-titre);
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--jaune);
        }

        /* Prix barré en gris */
        .price-old {
            font-size: 0.8rem;
            color: #aaa;
            text-decoration: line-through;
        }

        /* ============================================================
           FOOTER SIMPLE
           ============================================================ */
        footer {
            background: var(--noir);
            color: #aaaaaa;
            text-align: center;
            padding: 24px;
            font-size: 0.8rem;
            letter-spacing: 1px;
            margin-top: 60px;
        }

        footer span { color: var(--jaune); }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 1024px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            nav { display: none; } /* On cache la nav sur mobile */
            .hero-title, .hero-title-yellow { font-size: 3rem; }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-content { padding-left: 30px; }
        }

        @media (max-width: 480px) {
            .products-grid { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ============================================================
     HEADER
     ============================================================ -->
<header>
    <div class="navbar">

        <!-- Logo -->
        <a href="home.php" class="logo">
            <div class="logo-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="logo-text">
                <span>Shop<em>ESA</em></span>
                <small>Le shopping facile</small>
            </div>
        </a>

        <!-- Navigation centrale -->
        <nav>
            <a href="home.php" class="active">Accueil</a>
            <a href="boutique.php">Boutique</a>

            <!-- Dropdown Catégories -->
            <div class="dropdown">
                <a href="#" class="dropdown-toggle">
                    Catégories <i class="fas fa-chevron-down" style="font-size:0.7rem"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="#">Mode & Vêtements</a>
                    <a href="#">Électronique</a>
                    <a href="#">Maison & Décor</a>
                    <a href="#">Sport & Loisirs</a>
                    <a href="#">Beauté & Santé</a>
                </div>
            </div>

            <a href="about.php">À propos</a>
            <a href="contact.php">Contact</a>
        </nav>

        <!-- Icônes droite -->
        <div class="nav-icons">
            <a href="#"><i class="fas fa-search"></i></a>
            <a href="#">
                <!-- On affiche le nom de l'utilisateur connecté depuis la session -->
                <i class="fas fa-user"></i>
            </a>
            <a href="#" style="position:relative">
                <i class="fas fa-shopping-cart"></i>
                <!-- Badge panier -->
                <span class="cart-badge">0</span>
            </a>
            <!-- Bouton déconnexion -->
            <form action="../controllers/AuthController.php" method="POST" style="display:inline">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Quitter
                </button>
            </form>
        </div>

    </div>
</header>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="hero">
    <!-- Fond image assombri -->
    <div class="hero-bg"></div>

    <div class="hero-content">
        <!-- Label jaune -->
        <p class="hero-label">Bienvenue sur ShopESA</p>

        <!-- Titre en deux lignes (blanc + jaune) -->
        <h1 class="hero-title">Trouvez tout</h1>
        <h1 class="hero-title-yellow">Ce dont vous avez besoin</h1>

        <!-- Description -->
        <p class="hero-desc">
            Découvrez des produits de qualité aux meilleurs prix.<br>
            Shoppez maintenant et faites-vous plaisir !
        </p>

        <!-- Boutons -->
        <div class="hero-buttons">
            <a href="boutique.php" class="btn-hero-primary">
                Découvrir la boutique <i class="fas fa-arrow-right"></i>
            </a>
            <a href="#offres" class="btn-hero-secondary">
                Voir les offres
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     FEATURES (livraison, paiement, support, retours)
     ============================================================ -->
<section class="features">
    <div class="features-grid">

        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-truck"></i></div>
            <div class="feature-text">
                <strong>Livraison rapide</strong>
                <span>Partout au Togo</span>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="feature-text">
                <strong>Paiement sécurisé</strong>
                <span>100% sécurisé</span>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-headset"></i></div>
            <div class="feature-text">
                <strong>Support 24/7</strong>
                <span>Nous sommes là</span>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-undo-alt"></i></div>
            <div class="feature-text">
                <strong>Retours faciles</strong>
                <span>30 jours pour changer d'avis</span>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     MEILLEURES VENTES
     ============================================================ -->
<section class="best-sellers" id="offres">

    <div class="section-header">
        <h2 class="section-title">Nos meilleures ventes</h2>
        <div class="section-line"></div>
    </div>

    <!-- Grille de 5 produits (données statiques pour l'instant, à remplacer par PHP/BD) -->
    <div class="products-grid">

        <!-- Produit 1 -->
        <div class="product-card">
            <span class="product-badge">-20%</span>
            <div class="product-img">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80" alt="Sneakers">
                <div class="product-overlay"><i class="fas fa-cart-plus"></i> Ajouter</div>
            </div>
            <div class="product-info">
                <p class="product-name">Sneakers Blanc Classic</p>
                <div class="product-prices">
                    <span class="price-current">12 000 FCFA</span>
                    <span class="price-old">15 000 FCFA</span>
                </div>
            </div>
        </div>

        <!-- Produit 2 -->
        <div class="product-card">
            <div class="product-img">
                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80" alt="Montre">
                <div class="product-overlay"><i class="fas fa-cart-plus"></i> Ajouter</div>
            </div>
            <div class="product-info">
                <p class="product-name">Montre Noir Premium</p>
                <div class="product-prices">
                    <span class="price-current">35 000 FCFA</span>
                </div>
            </div>
        </div>

        <!-- Produit 3 -->
        <div class="product-card">
            <div class="product-img">
                <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&q=80" alt="Sac">
                <div class="product-overlay"><i class="fas fa-cart-plus"></i> Ajouter</div>
            </div>
            <div class="product-info">
                <p class="product-name">Sac à Main Cuir Noir</p>
                <div class="product-prices">
                    <span class="price-current">28 000 FCFA</span>
                </div>
            </div>
        </div>

        <!-- Produit 4 -->
        <div class="product-card">
            <span class="product-badge">-15%</span>
            <div class="product-img">
                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&q=80" alt="Laptop">
                <div class="product-overlay"><i class="fas fa-cart-plus"></i> Ajouter</div>
            </div>
            <div class="product-info">
                <p class="product-name">Laptop Pro 15"</p>
                <div class="product-prices">
                    <span class="price-current">450 000 FCFA</span>
                    <span class="price-old">530 000 FCFA</span>
                </div>
            </div>
        </div>

        <!-- Produit 5 -->
        <div class="product-card">
            <div class="product-img">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80" alt="Casque">
                <div class="product-overlay"><i class="fas fa-cart-plus"></i> Ajouter</div>
            </div>
            <div class="product-info">
                <p class="product-name">Casque Audio Sans Fil</p>
                <div class="product-prices">
                    <span class="price-current">22 000 FCFA</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer>
    <p>© 2026 <span>ShopESA</span> — Le shopping facile. Tous droits réservés.</p>
</footer>

</body>
</html>