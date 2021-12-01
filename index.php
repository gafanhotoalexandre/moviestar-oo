<?php
    require_once 'templates/header.php';
    require_once 'dao/MovieDaoMysql.php';

    // DAO de filmes
    $movieDao = new MovieDaoMysql($conn, BASE_URL);

    $latesMovies = $movieDao->getLatesMovies();

    $actionMovies = $movieDao->getMoviesByCategory('Ação');

    $comedyMovies = $movieDao->getMoviesByCategory('Comédia');
?>

    <main id="main-container" class="container-fluid">
        <h2 class="section-title">Filmes Novos</h2>
        <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</p>
        
        <section class="movies-container">
            <?php foreach ($latesMovies as $movie): ?>
                <?php require 'templates/movie_card.php'; ?>
            <?php endforeach; ?>

            <?php if (count($latesMovies) === 0): ?>
                <p class="empty-list">Ainda não há filmes cadastrados!</p>
            <?php endif; ?>
        </section>

        <h2 class="section-title">Ação</h2>
        <p class="section-description">Veja os melhores filmes de ação</p>
        <section class="movies-container">
            <?php foreach ($actionMovies as $movie): ?>
                <?php require 'templates/movie_card.php'; ?>
            <?php endforeach; ?>

            <?php if (count($actionMovies) === 0): ?>
                <p class="empty-list">Ainda não há filmes de ação cadastrados!</p>
            <?php endif; ?>
        </section>

        <h2 class="section-title">Comédia</h2>
        <p class="section-description">Veja os melhores filmes de comédia</p>
        <section class="movies-container">
            <?php foreach ($comedyMovies as $movie): ?>
                <?php require 'templates/movie_card.php'; ?>
            <?php endforeach; ?>

            <?php if (count($comedyMovies) === 0): ?>
                <p class="empty-list">Ainda não há filmes de comédia cadastrados!</p>
            <?php endif; ?>
        </section>
    </main>

<?php
    require_once 'templates/footer.php';
?>