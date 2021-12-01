<?php
    require_once 'globals.php';
    require_once 'connection.php';
    require_once 'models/Message.php';
    require_once 'dao/UserDaoMysql.php';

    $message = new Message(BASE_URL);
    $userDao = new UserDaoMysql($conn, BASE_URL);

    $flashMessage = $message->getMessage();
    
    if (!empty($flashMessage['msg'])) {
        $message->clearMessage();
    }

    $userData = $userDao->verifyToken();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStar</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>img/moviestar.ico" type="image/x-icon">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div id="main-navbar" class="navbar navbar-expand-lg">
            <!-- Brand -->
            <a href="<?= BASE_URL ?>" class="navbar-brand">
                <img src="<?= BASE_URL ?>img/logo.svg" id="logo" alt="MovieStar - Logo">
                <span id="moviestar-title">MovieStar</span>
            </a>

            <!-- Mobile Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbar" aria-controls="navbar" aria-expanded="false"
                    aria-label="toggle-navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search Form -->
            <form action="<?= BASE_URL ?>search.php" method="GET" id="search-form" class="form-inline my-2 my-lg-0">
                <input type="search" class="form-control mr-sm-2"
                    name="q" id="q" placeholder="Buscar filmes" aria-label="Search"
                        value="<?= $_GET['q'] ?? '' ?>">
                <button class="btn my-2 my-sm-0" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <!-- SignIn / SignUp -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ml-auto">
                    <?php if ($userData): ?><!-- Menu de Usuário Logado -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>newmovie.php" class="nav-link">
                                <i class="far fa-plus-square"></i> Incluir Filme
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>dashboard.php" class="nav-link">Meus Filmes</a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>editprofile.php" class="nav-link">
                                <?= $userData->name ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>logout.php" class="nav-link">Sair</a>
                        </li>
                    <?php else: ?><!-- Menu de Usuário Logado -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>auth.php" class="nav-link">Entar / Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>
    <?php if (!empty($flashMessage['msg'])): ?>
        <div class="msg-container">
            <p class="msg <?= $flashMessage['type'] ?>">
                <?= $flashMessage['msg'] ?>
            </p>
        </div>
    <?php endif; ?>
