<?php
    require_once './helpers/auth_verify.php';
    require_once './templates/header.php';
?>

    <main id="main-container" class="container-fluid">
        <div class="row">
            <div class="offset-md-4 col-md-4 new-movie-container">
                <h1 id="page-title">Adicionar Filme</h1>
                <p class="page-description">Adicione sua crítica e compartilhe com o mundo</p>

                <form action="<?= BASE_URL ?>movie_process.php" method="POST" enctype="multipart/form-data" id="add-movie-form">
                    <input type="hidden" name="type" value="create">

                    <div class="form-group">
                        <label for="title">Título*:</label>
                        <input type="text" name="title" id="title" class="form-control"
                            placeholder="Digite o título do filme">
                    </div>

                    <div class="form-group">
                        <label for="image">Imagem:</label>
                        <input type="file" name="image" id="image" class="form-control-file">
                    </div>

                    <div class="form-group">
                        <label for="length">Duração:</label>
                        <input type="text" name="length" id="length" class="form-control"
                        placeholder="Digite a duração do filme">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoria*:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Ação">Ação</option>
                            <option value="Aventura">Aventura</option>
                            <option value="Animação">Animação</option>
                            <option value="Comédia">Comédia</option>
                            <option value="Drama">Drama</option>
                            <option value="Fantasia / Ficção">Fantasia / Ficção</option>
                            <option value="Romance">Romance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="trailer">Trailer:</label>
                        <input type="text" name="trailer" id="trailer" class="form-control"
                        placeholder="Insira o link do trailer">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição*:</label>
                        <textarea name="description" id="description" rows="5" class="form-control"
                            placeholder="Descreva o filme"></textarea>
                    </div>

                    <input type="submit" value="Adicionar" class="card-btn">
                </form>
            </div>
        </div>
    </main>

<?php
    require_once './templates/footer.php';
?>