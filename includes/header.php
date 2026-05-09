<?php
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$publicPages = ['login.php', 'signup.php'];
$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isPublicPage = in_array($currentPage, $publicPages);
$isAdminPage = in_array($currentPage, $adminPages);
?>

<nav class="navbar navbar-expand-lg px-4 py-3 border-bottom mb-0" style="position: relative;">
    <div class="container-fluid">

        <?php if ($isAdminPage): ?>
            <a href="/pages/admin/dashboard.php" class="navbar-brand fw-bold text-dark" style="font-size: 20px; z-index: 2;">
                Cinevobis
            </a>
        <?php else: ?>
            <a href="/" class="navbar-brand fw-bold text-dark" style="font-size: 20px; z-index: 2;">
                Cinevobis
            </a>
        <?php endif; ?>

        <div class="ms-auto d-flex align-items-center gap-3" style="z-index: 2;">

            <?php if (!$isAdminPage): ?>
                <form action="/pages/public/search.php" method="GET" class="d-flex align-items-center m-0">
                    <input type="text" name="search" placeholder="Cerca un film..." class="form-control form-control-sm shadow-none rounded-3 me-2" style="min-width: 220px;" required value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </form>
            <?php endif; ?>

            <?php if ($isLogged && !$isAdminPage): ?>
                <div class="d-flex align-items-center gap-1">
                    <a href="/pages/user/favorites.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Preferiti">
                        <i class="bi bi-heart-fill"></i>
                    </a>
                    <a href="/pages/user/watchlist.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Watchlist">
                        <i class="bi bi-bookmark"></i>
                    </a>
                    <a href="/pages/user/watched.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Watched">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <a href="/pages/user/reviews.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Recensioni">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (!$isLogged): ?>
                <div class="d-flex gap-2 align-items-center">
                    <a href="/pages/public/login.php" class="btn btn-outline-secondary btn-sm px-4">Accedi</a>
                    <a href="/pages/public/signup.php" class="btn btn-dark btn-sm px-4">Registrati</a>
                </div>
            <?php else: ?>
                <div class="dropdown">
                    <button class="btn border-0 p-2 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">

                        <li><a class="dropdown-item py-2 small" href="/pages/user/profile.php"><i class="bi bi-person me-2"></i>Profilo</a></li>

                        <?php if ($isAdminPage): ?>
                            <li><a class="dropdown-item py-2 small" href="/pages/admin/dashboard.php"><i class="bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item py-2 small" href="/"><i class="bi-house me-2"></i>Home</a></li>
                        <?php endif; ?>

                        <?php if (!$isAdminPage): ?>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/notice_board.php"><i class="bi bi-layout-text-sidebar-reverse me-2"></i>Bacheca</a></li>

                            <?php if ($_SESSION['id_profilo'] == 2): ?>
                                <li><a class="dropdown-item py-2 small" href="/actions/contact.php"><i class="bi bi-envelope me-2"></i>Contattaci</a></li>
                            <?php endif; ?>
                        <?php endif; ?>


                        <?php if ($_SESSION['id_profilo'] == '1' && !$isAdminPage): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 small fw-bold" href="/pages/admin/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>

                        <?php if ($isAdminPage): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 small fw-bold" href="/">Esci</a></li>
                        <?php endif; ?>

                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 small fw-bold text-danger" href="/actions/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>