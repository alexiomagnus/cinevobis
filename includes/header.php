<?php 
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$publicPages = ['login.php', 'signup.php'];
$isPublicPage = in_array($currentPage, $publicPages);
?>

<nav class="navbar px-4">
    <a href="/index.php" class="navbar-brand">Cinevobis</a>

    <form method="POST" class="d-flex gap-2 align-items-center mb-0">
        <?php if (!$isPublicPage): ?>
            
            <?php if (!$isLogged): ?>
                <button class="btn btn-sm btn-outline-dark" name="login">Accedi</button>
                <button type="submit" class="btn btn-sm btn-brand" name="signup">Registrati</button>
            <?php else: ?>
                <button class="btn btn-sm btn-outline-dark" name="profile">Il mio profilo</button>
            <?php endif; ?>

        <?php endif; ?>
    </form>
</nav>