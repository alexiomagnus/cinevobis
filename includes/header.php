<?php 
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$publicPages = ['index.php', 'search.php', 'film.php'];
$privatePages = ['sessions.php', 'add_film.php', 'users.php', 'edit_user.php', 'settings.php', 'admin_area.php'];

$isLogged = isset($_SESSION['username']);
?>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="/index.php" class="navbar-brand text-decoration-none">Cinevobis</a>

        <form method="POST" class="d-flex gap-3 align-items-center mb-0">

            <?php if (in_array($currentPage, $publicPages)): ?>

                <?php if ($isLogged): ?>
                    <button class="bg-transparent border-0 text-light fs-2 p-0 lh-1" name="profile" title="Profile">
                        <i class="bi bi-person-circle"></i>
                    </button>
                <?php else: ?>
                    <button class="btn btn-light" name="login">Login</button>
                    <button class="btn btn-warning" name="signup">Sign up</button>
                <?php endif; ?>

            <?php elseif (in_array($currentPage, $privatePages)): ?>

                <?php if ($isLogged): ?>
                    <button class="bg-transparent border-0 text-light fs-2 p-0 lh-1" name="profile" title="Profile">
                        <i class="bi bi-person-circle"></i>
                    </button>
                <?php else: ?>
                    <button class="btn btn-light" name="login">Login</button>
                <?php endif; ?>

            <?php endif; ?>

        </form>
    </div>
</nav>