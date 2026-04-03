<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dungeon Cwarler</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body class="container-fluid bg-dark text-light min-vh-100 d-flex flex-column">
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-3 border-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Dungeon Crawler</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    
                    <?php if ($_SESSION['role'] === 'player'): ?>
                        <li class="nav-item">
                        <a class="nav-link" href="/game/dashboard">Dashboard</a>
                        </li>
                         <li class="nav-item">
                        <a class="nav-link" href="/game/start">Start Game</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/characters">Characters</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/monsters">Monsters</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/rooms">Rooms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">Users</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'player'): ?>
                        <li class="nav-item">
                        <form method="POST" action="/quit" class="d-inline">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                Quit Game
                            </button>
                        </form>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <form method="POST" action="/logout" class="d-inline">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                Logout
                            </button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

