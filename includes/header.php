<?php 
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$publicPages = ['login.php', 'signup.php'];
$isPublicPage = in_array($currentPage, $publicPages);
?>

<nav class="navbar navbar-expand-lg px-4 py-3">
    <div class="container-fluid">
        <a href="/index.php" class="navbar-brand d-flex align-items-center fw-bold" style="font-size: 20px;">
            Cinevobis
        </a>
        
        <div class="ms-auto d-flex align-items-center gap-2">
            <?php if (!$isPublicPage): ?>
                <?php if (!$isLogged): ?>
                    <form method="POST" class="m-0 d-flex gap-2">
                        <button type="submit" name="login" class="btn btn-sm btn-dark px-3">Accedi</button>
                        <button type="submit" name="signup" class="btn btn-sm btn-brand px-3">Registrati</button>
                    </form>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Il mio profilo
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/actions/details.php">Dettagli</a></li>
                            <li><a class="dropdown-item" href="/actions/change_password.php">Cambia password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/actions/logout.php">Esci</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</nav>