<?php
// 404 Error Page
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include 'partials/head.php'; ?>

<body>

    <?php include 'partials/topbar.php'; ?>

    <?php include 'partials/header.php'; ?>

    <!-- Page Header Start -->
    <div class="page-header dark-section parallaxie">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Page not found</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">404 Error page</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- error Page start -->
    <div class="error-page light-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-page-image wow fadeInUp">
                        <img src="images/404-error-image.png" alt="">
                    </div>
                    <div class="error-page-content">
                        <div class="section-title">
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Oops! Page not found</h2>
                        </div>
                        <div class="error-page-content-body">
                            <p class="wow fadeInUp" data-wow-delay="0.2s">The page you are looking for does not exist.</p>
                            <a class="btn-default wow fadeInUp" data-wow-delay="0.4s" href="index.php">Back to Homepage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- error Page end -->

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/scripts.php'; ?>
</body>
</html>
