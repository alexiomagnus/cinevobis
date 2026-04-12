<?php $currentPage = basename($_SERVER['SCRIPT_NAME']); ?>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="/index.php" class="navbar-brand text-decoration-none">Cinevobis</a>

        <form method="POST" class="d-flex gap-2">
            <?php if (in_array($currentPage, ['index.php', 'search.php', 'film.php']) && isset($_SESSION['username'])): ?>
                <button class="btn btn-primary" name="profile">Profile</button>
            <?php endif; ?>

            <?php if (in_array($currentPage, ['sessions.php', 'add_film.php', 'users.php', 'edit_user.php', 'profile.php', 'admin_area.php'])): ?>
                <button class="btn btn-primary" name="profile">Profile</button>
            <?php endif; ?>

            <?php if (in_array($currentPage, ['index.php', 'search.php', 'film.php']) && !isset($_SESSION['username'])): ?>
                <button class="btn btn-secondary" name="login">Login</button>
                <button class="btn btn-primary" name="signup">Sign up</button>
            <?php endif; ?>
        </form>
    </div>
</nav>