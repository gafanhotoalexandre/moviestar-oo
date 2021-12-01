<?php

    // auth_verify já carrega o UserDaoMysql
    require_once 'helpers/auth_verify.php';
    require_once 'templates/header.php';

    require_once 'dao/MovieDaoMysql.php';
    require_once 'models/User.php';

    $user = new User();
    $movieDao = new MovieDaoMysql($conn, BASE_URL);

    $id = filter_input(INPUT_GET, 'id');

    if (empty($id)) {

        if (!empty($userData)) {
            $id = $userData->id;
        } else {
            $message->setMessage(
                'Usuário não encontrado!',
                'error'
            );
        }
    } else {

        $userData = $userDao->findById($id);

        if (! $userData) {
            $message->setMessage(
                'Usuário não encontrado!',
                'error'
            );
        }
    }

    $fullName = $userData->getFullName($userData);

    if ($userData->image == '') {
        $userData->image = 'user.png';
    }

    // Filmes que o usuário adicionou
    $userMovies = $movieDao->getMoviesByUserId($userData->id);
?>

    <main id="main-container" class="container-fluid">
        <div class="row">

            <div class="col-md-8 offset-md-2">
                <div class="row profile-container">

                    <div class="col-md-12 about-container">
                        <h1 id="page-title"><?= $fullName ?></h1>
                        
                        <div id="profile-image-container" class="profile-image"
                            style="background-image: url('<?= BASE_URL ?>img/users/<?= $userData->image ?>');"></div>
                        
                        <h3 class="about-title">Sobre:</h3>

                        <?php if (!empty($userData->bio)): ?>
                            <p class="profile-description"><?= $userData->bio ?></p>
                        <?php else: ?>
                            <p class="profile-description">O usuário ainda não escreveu nada por aqui...</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-12 added-movies-container">
                        <h3>Filmes que enviou:</h3>

                        <div class="movies-container">
                            <?php foreach ($userMovies as $movie): ?>
                                <?php require './templates/movie_card.php'; ?>
                            <?php endforeach; ?>

                            <?php if (count($userMovies) === 0): ?>
                                <p class="empty-list">O usuário ainda não enviou filmes.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

<?php
    require_once './templates/footer.php';
?>