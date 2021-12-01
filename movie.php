<?php

    require_once 'templates/header.php';

    require_once 'models/Movie.php';
    require_once 'dao/MovieDaoMysql.php';
    require_once 'dao/ReviewDaoMysql.php';

    $id = filter_input(INPUT_GET, 'id');

    $movieDao = new MovieDaoMysql($conn, BASE_URL);

    $reviewDao = new ReviewDaoMysql($conn, BASE_URL);

    if (! $id) {
        $message->setMessage(
            'O filme não foi encontrado!',
            'error',
            'index.php'
        );
    }

    $movie = $movieDao->findById($id);

    // Verificando existência do filme
    if ($movie === false) {
        $message->setMessage(
            'O filme não foi encontrado!',
            'error',
            'index.php'
        );
    }

    // Verificando se o filme é do usuário
    $userOwnsMovie = false;
    if (!empty($userData)) {
        if ($userData->id === $movie->users_id) {
            $userOwnsMovie = true;
        }
    
        // Resgatar as reviews do filme
        $alreadyReviewed = $reviewDao->hasAlreadyReviewed($id, $userData->id);
    }

    // Resgatar as reviews do filme
    $movieReviews = $reviewDao->getMoviesReview($id);
?>

    <main id="main-container" class="container-fluid">
        <div class="row">
            <div class="offset-md-1 col-md-6 movie-container">
                <h1 id="page-title"><?= $movie->title ?></h1>

                <p class="movie-details">
                    <span>Duração: <?= $movie->length ?></span>
                    <span class="pipe"></span>

                    <span><?= $movie->category ?></span>
                    <span class="pipe"></span>

                    <span><i class="fas fa-star"></i> <?= $movie->rating ?></span>
                </p>

                <iframe src="<?= $movie->trailer ?>" width="560" height="315" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                <p><?= $movie->description ?></p>
            </div>

            <div class="col-md-4">
                <div class="movie-image-container" style="background-image: url('<?= BASE_URL ?>img/movies/<?= $movie->image ?>');"></div>
            </div>

            <!-- REVIEWS -->
            <div class="offset-md-1 col-md-10" id="reviews-container">
                <h3 id="reviews-title">Avaliações:</h3>
                <!-- Verificando a habilitação de review para o usuário -->
                <?php if (!empty($userData) && !$userOwnsMovie && !$alreadyReviewed): ?>
                    <div class="col-md-12" id="review-form-container">
                        <h4>Envie sua avaliação:</h4>
                        <p class="page-description">
                            Preencha o formulário com a nota e comentário sobre o filme.
                        </p>

                        <form action="<?= BASE_URL ?>review_process.php" id="review-form" method="post">
                            <input type="hidden" name="type" value="create">
                            <input type="hidden" name="movie_id" value="<?= $movie->id ?>">

                            <div class="form-group">
                                <label for="rating">Nota do filme:</label>
                                <select name="rating" id="rating" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="10">10</option>
                                    <option value="9">9</option>
                                    <option value="8">8</option>
                                    <option value="7">7</option>
                                    <option value="6">6</option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="review">Seu comentário:</label>
                                <textarea name="review" id="review" rows="3"
                                    class="form-control" placeholder="O que você achou o filme?"></textarea>
                            </div>

                            <input type="submit" value="Enviar comentário" class="card-btn">
                        </form>
                    </div>
                <?php endif; ?>
                <!-- Comentários -->
                <?php foreach ($movieReviews as $review): ?>
                    <?php require './templates/user_review.php'; ?>
                <?php endforeach; ?>

                <?php if (count($movieReviews) == 0): ?>
                    <p class="my-3 empty-list">Ainda não há comentários para este filme...</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php
    require_once 'templates/footer.php';
?>