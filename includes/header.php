<?php
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$publicPages = ['login.php', 'signup.php'];
$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isPublicPage = in_array($currentPage, $publicPages);
$isAdminPage = in_array($currentPage, $adminPages);
?>
<nav class="navbar border-bottom px-2 px-sm-3 px-lg-4">
    <!-- Rimosso justify-content-between: i componenti ora si allineano naturalmente da sinistra -->
    <div class="container-fluid d-flex align-items-center flex-nowrap gap-1 gap-sm-2">

        <!-- Brand: a sinistra -->
        <a href="<?= $isAdminPage ? '/pages/admin/dashboard.php' : '/' ?>" class="navbar-brand fw-bold me-2 me-sm-3 flex-shrink-0">
            Cinevobis
        </a>

        <!-- Search: attaccata a Cinevobis, cresce fino a un massimo di 360px -->
        <?php if (!$isAdminPage): ?>
            <form action="/pages/public/search.php" method="GET"
                  class="d-flex align-items-center flex-grow-1" 
                  style="min-width: 0; max-width: 360px;">
                <input type="text" name="search" placeholder="Cerca..."
                       class="form-control form-control-sm rounded-pill shadow-none"
                       required value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        <?php else: ?>
            <div class="flex-grow-1"></div>
        <?php endif; ?>

        <!-- Blocco Destra: ms-auto assorbe lo spazio a sinistra e spinge Dark Mode e Dropdown a destra -->
        <div class="d-flex align-items-center gap-1 gap-sm-2 flex-shrink-0 ms-auto">
            
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Cambia Tema">
                <i class="bi bi-moon-fill" id="theme-icon"></i>
            </button>

            <!-- Autenticazione / Dropdown unico -->
<?php if (!$isLogged): ?>
    <div class="d-flex gap-1 gap-sm-2 auth-container">
        <a href="/pages/public/login.php" 
           class="btn btn-outline-secondary btn-sm w-50 d-inline-flex align-items-center justify-content-center gap-1" 
           title="Accedi">
            <i class="bi bi-box-arrow-in-right"></i>
            <span class="d-none d-sm-inline">Accedi</span>
        </a>
        <a href="/pages/public/signup.php" 
           class="btn btn-dark btn-sm w-50 d-inline-flex align-items-center justify-content-center gap-1" 
           title="Registrati">
            <i class="bi bi-person-plus"></i>
            <span class="d-none d-sm-inline">Registrati</span>
        </a>
    </div>
<?php else: ?>
                <div class="dropdown">
                    <button class="btn border-0 p-1 p-sm-2 shadow-none" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                        
                        <!-- Liste integrate nel menu -->
                        <?php if ($isLogged && !$isAdminPage): ?>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/favorites.php"><i class="bi bi-heart-fill me-2 text-secondary"></i>Preferiti</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/watchlist.php"><i class="bi bi-bookmark me-2 text-secondary"></i>Watchlist</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/watched.php"><i class="bi bi-eye-fill me-2 text-secondary"></i>Visti</a></li>
                            <li><a class="dropdown-item py-2 small" href="/pages/user/reviews.php"><i class="bi bi-pencil-fill me-2 text-secondary"></i>Recensioni</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>

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

    </div>
</nav>
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