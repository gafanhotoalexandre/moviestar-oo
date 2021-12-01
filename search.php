<?php
    require_once 'templates/header.php';
    require_once 'dao/MovieDaoMysql.php';

    // DAO de filmes
    $movieDao = new MovieDaoMysql($conn, BASE_URL);

    $q = filter_input(INPUT_GET, 'q');

    $movies = $movieDao->findByTitle($q);
?>

    <main id="main-container" class="container-fluid">
        <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
        <p class="section-description">Resultados de busca retornados com base na sua pesquisa.</p>
        
        <section class="movies-container">
            <?php foreach ($movies as $movie): ?>
                <?php require 'templates/movie_card.php'; ?>
            <?php endforeach; ?>

            <?php if (count($movies) === 0): ?>
                <p class="empty-list">Não há filmes correspondentes à busca, <a href="<?= BASE_URL ?>"
                    class="back-link">voltar</a>.</p>
            <?php endif; ?>
        </section>
    </main>

<?php
    require_once 'templates/footer.php';
?>