<?php 
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$publicPages = ['login.php', 'signup.php'];
$isPublicPage = in_array($currentPage, $publicPages);
?>

<nav class="navbar navbar-expand-lg px-4 py-3 border-bottom mb-0" 
     style="position: relative; border-color: #000000 !important;">
    <div class="container-fluid">
        
        <a href="/" class="navbar-brand fw-bold text-dark" style="font-size: 20px; z-index: 2;">
            Cinevobis
        </a>

        <div class="d-none d-lg-flex align-items-center gap-4" 
             style="position: absolute; left: 50%; transform: translateX(-50%); z-index: 1;">
            <a href="/" class="nav-link text-secondary text-uppercase fw-semibold small" style="letter-spacing: 1px;">Recensioni</a>
            <a href="/" class="nav-link text-secondary text-uppercase fw-semibold small" style="letter-spacing: 1px;">Preferiti</a>
            <a href="/" class="nav-link text-secondary text-uppercase fw-semibold small" style="letter-spacing: 1px;">Watched</a>
            <a href="/" class="nav-link text-secondary text-uppercase fw-semibold small" style="letter-spacing: 1px;">Watchlist</a>
        </div>
        
        <div class="ms-auto d-flex align-items-center" style="z-index: 2;">
            <?php if (!$isPublicPage): ?>
                <?php if (!$isLogged): ?>
                    <div class="d-flex gap-2">
                        <button type="submit" name="login" class="btn btn-sm btn-brand px-3 shadow-none">Accedi</button>
                        <button type="submit" name="signup" class="btn btn-sm btn-dark px-3 shadow-none">Registrati</button>
                    </div>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn border-0 p-2 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><h6 class="dropdown-header text-uppercase small fw-bold">Profilo Utente</h6></li>
                            <li><a class="dropdown-item py-2 small" href="/actions/details.php">Dettagli</a></li>
                            <li><a class="dropdown-item py-2 small" href="/actions/change_password.php">Cambia password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 small text-danger" href="/actions/logout.php">Esci</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
</nav>