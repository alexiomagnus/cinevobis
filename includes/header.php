<script>
    (function() {
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    })();
</script>
<?php
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$publicPages = ['login.php', 'signup.php'];
$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isPublicPage = in_array($currentPage, $publicPages);
$isAdminPage = in_array($currentPage, $adminPages);
?>

<nav class="navbar border-bottom px-3 px-lg-4">
    <div class="container-fluid gap-2">

        <!-- Brand -->
        <a href="<?= $isAdminPage ? '/pages/admin/dashboard.php' : '/' ?>" class="navbar-brand fw-bold me-3">
            Cinevobis
        </a>

        <!-- Search: sempre visibile, prende lo spazio disponibile -->
        <?php if (!$isAdminPage): ?>
            <form action="/pages/public/search.php" method="GET"
                  class="d-flex align-items-center flex-grow-1 me-2">
                <input type="text" name="search" placeholder="Cerca un film..."
                       class="form-control form-control-sm rounded-pill shadow-none"
                       style="max-width: 360px;"
                       required value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        <?php else: ?>
            <div class="flex-grow-1"></div>
        <?php endif; ?>

        <!-- Icone liste (loggato, pagine pubbliche) -->
        <?php if ($isLogged && !$isAdminPage): ?>
            <div class="d-flex align-items-center gap-1 me-1">
                <a href="/pages/user/favorites.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Preferiti">
                    <i class="bi bi-heart-fill"></i>
                </a>
                <a href="/pages/user/watchlist.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Watchlist">
                    <i class="bi bi-bookmark"></i>
                </a>
                <a href="/pages/user/watched.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Visti">
                    <i class="bi bi-eye-fill"></i>
                </a>
                <a href="/pages/user/reviews.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Recensioni">
                    <i class="bi bi-pencil-fill"></i>
                </a>
            </div>
        <?php endif; ?>

        <!-- Theme Toggle -->
        <button id="theme-toggle" class="btn btn-sm btn-outline-secondary border-0 px-2 me-2" title="Cambia Tema">
            <i class="bi bi-moon-fill" id="theme-icon"></i>
        </button>

        <!-- Non loggato: bottoni Accedi / Registrati -->
        <?php if (!$isLogged): ?>
            <div class="d-flex gap-2">
                <a href="/pages/public/login.php" class="btn btn-outline-secondary btn-sm px-4">Accedi</a>
                <a href="/pages/public/signup.php" class="btn btn-dark btn-sm px-4">Registrati</a>
            </div>
        <?php else: ?>
            <!-- Hamburger → dropdown (unico per tutte le dimensioni) -->
            <div class="dropdown">
                <button class="btn border-0 p-2 shadow-none" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">

                    <li><a class="dropdown-item py-2 small" href="/pages/user/profile.php">
                        <i class="bi bi-person me-2"></i>Profilo</a></li>

                    <?php if ($isAdminPage): ?>
                        <li><a class="dropdown-item py-2 small" href="/pages/admin/dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item py-2 small" href="/">
                            <i class="bi bi-house me-2"></i>Home</a></li>
                        <li><a class="dropdown-item py-2 small" href="/actions/contact.php">
                            <i class="bi bi-envelope me-2"></i>Contattaci</a></li>
                        <li><a class="dropdown-item py-2 small" href="/pages/public/notice_board.php">
                            <i class="bi bi-layout-text-sidebar-reverse me-2"></i>Bacheca</a></li>
                    <?php endif; ?>

                    <?php if (($_SESSION['id_profilo'] ?? null) == '1' && !$isAdminPage): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 small fw-bold" href="/pages/admin/dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                    <?php endif; ?>

                    <?php if ($isAdminPage): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 small fw-bold" href="/">
                            <i class="bi bi-box-arrow-left me-2"></i>Esci dall'admin</a></li>
                    <?php endif; ?>

                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 small fw-bold text-danger" href="/actions/logout.php">
                        <i class="bi bi-power me-2"></i>Logout</a></li>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</nav>