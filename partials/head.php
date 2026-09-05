<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}
$siteName = getSetting($pdo, 'site_name', 'ShopWithAustin - Fashion & Lifestyle Store');
?>
<head>

    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <!-- SEO -->
    <meta name="description" content="The best store to buy from. Shop quality products at great prices with a simple and convenient shopping experience.">
    <meta name="keywords" content="online store, shopping, products, best store, buy online">
    <meta name="author" content="<?= sanitize($siteName) ?>">

    <!-- Robots -->
    <meta name="robots" content="index, follow">

    <!-- Page Title -->
    <title>
        <?= isset($pageTitle) ? sanitize($pageTitle) . ' - ' : '' ?><?= sanitize($siteName) ?>
    </title>

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= sanitize($siteUrl ?? '') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/logo.jpg">
    <link rel="shortcut icon" type="image/png" href="images/logo.jpg">
    <link rel="apple-touch-icon" href="images/logo.jpg">

    <!-- Website Logo / Brand Image -->
    <!-- Replace images/logo.jpg with your actual logo file -->
    <link rel="preload" as="image" href="images/logo.jpg">

    <!-- Open Graph - Facebook / WhatsApp / LinkedIn -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= sanitize($siteName) ?>">
    <meta property="og:title" content="<?= isset($pageTitle) ? sanitize($pageTitle) . ' - ' : '' ?><?= sanitize($siteName) ?>">
    <meta property="og:description" content="The best store to buy from. Shop quality products at great prices with a simple and convenient shopping experience.">
    <meta property="og:url" content="<?= sanitize($siteUrl ?? '') ?>">
    <meta property="og:image" content="<?= sanitize(rtrim($siteUrl ?? '', '/') . '/images/logo.jpg') ?>">
    <meta property="og:image:alt" content="<?= sanitize($siteName) ?>">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= isset($pageTitle) ? sanitize($pageTitle) . ' - ' : '' ?><?= sanitize($siteName) ?>">
    <meta name="twitter:description" content="The best store to buy from. Shop quality products at great prices with a simple and convenient shopping experience.">
    <meta name="twitter:image" content="<?= sanitize(rtrim($siteUrl ?? '', '/') . '/images/logo.jpg') ?>">
    <meta name="twitter:image:alt" content="<?= sanitize($siteName) ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Spline+Sans+Mono:wght@400;500;700&family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Space+Mono:wght@400;700&family=Crimson+Pro:ital,wght@0,400;0,500;0,600;1,400&family=Bayon&family=Pacifico&family=Tangerine:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css/fonts.css" rel="stylesheet" media="screen">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    <!-- SlickNav -->
    <link href="css/slicknav.min.css" rel="stylesheet">

    <!-- Swiper -->
    <link rel="stylesheet" href="css/swiper-bundle.min.css">

    <!-- Font Awesome -->
    <link href="css/all.min.css" rel="stylesheet" media="screen">

    <!-- Animated -->
    <link href="css/animate.css" rel="stylesheet">

    <!-- Magnific Popup -->
    <link rel="stylesheet" href="css/magnific-popup.css">

    <!-- Mouse Cursor -->
    <link rel="stylesheet" href="css/mousecursor.css">

    <!-- Main Custom CSS -->
    <link href="css/custom.css" rel="stylesheet" media="screen">
</head>