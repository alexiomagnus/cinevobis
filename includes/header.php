<?php 
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$publicPages = ['login.php', 'signup.php'];
$isPublicPage = in_array($currentPage, $publicPages);
?>

<nav class="navbar px-4">
    <a href="/index.php" class="navbar-brand">Cinevobis</a>

    <div class="d-flex gap-2 align-items-center mb-0">
        <?php if (!$isPublicPage): ?>
            
            <?php if (!$isLogged): ?>
                <form method="POST" class="d-flex gap-2 mb-0">
                    <button class="btn btn-sm btn-outline-dark" name="login">Accedi</button>
                    <button type="submit" class="btn btn-sm btn-brand" name="signup">Registrati</button>
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
</nav>