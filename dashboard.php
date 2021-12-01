<?php

    // Verificando autenticação
    require_once './helpers/auth_verify.php';

    require_once './templates/header.php';
    require_once './models/User.php';
    require_once './dao/MovieDaoMysql.php';

    $user = new User();
    $movieDao = new MovieDaoMysql($conn, BASE_URL);

    $userMovies = $movieDao->getMoviesByUserId($userData->id);
?>

    <main id="main-container" class="container-fluid">
        <h2 class="section-title">Dashboard</h2>
        <p class="section-description">Adicione ou atualize as informações dos filmes que você enviou</p>

        <div class="row">
            <div class="col-md-12 mb-3" id="add-movie-container">
                <a href="<?= BASE_URL ?>newmovie.php" class="card-btn">
                    <i class="fas fa-plus"></i> Adicionar Filme
                </a>
            </div>

            <div class="col-md-12" id="movies-dashboard">
                <div class="table-responsive">
                    <table class="table">
                        
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Título</th>
                                <th scope="col">Nota</th>
                                <th scope="col" class="actions-column">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($userMovies as $userMovie): ?>
                                <tr>
                                    <td scope="row"><?= $userMovie->id ?></td>

                                    <td><a href="<?= BASE_URL ?>movie.php?id=<?= $userMovie->id ?>"
                                        class="table-movie-title"><?= $userMovie->title ?></a></td>

                                    <td><i class="fas fa-star"></i> <?= $userMovie->rating ?></td>

                                    <!-- ACTION BUTTONS -->
                                    <td class="actions-column">
                                        <a href="<?= BASE_URL ?>editmovie.php?id=<?= $userMovie->id ?>" class="edit-btn">
                                            <i class="far fa-edit"></i> Editar
                                        </a>

                                        <form action="<?= BASE_URL ?>movie_process.php" method="post">
                                            <input type="hidden" name="type" value="delete">
                                            <input type="hidden" name="id" value="<?= $userMovie->id ?>">

                                            <button type="submit" class="delete-btn"
                                                onclick="return confirm('Tem certeza que deseja deletar?')">
                                                <i class="fas fa-times"></i> Deletar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </main>

<?php
    require_once './templates/footer.php';
?>
