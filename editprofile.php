<?php
    require_once './helpers/auth_verify.php';
    require_once 'templates/header.php';

    $userDao = new UserDaoMysql($conn, BASE_URL);
    $user = new User();

    // $userData -> vem do auth_verify.php
    $fullName = $userData->getFullName($userData);

    if ($userData->image == '') {
        $userData->image = 'user.png';
    }
?>

    <main id="main-container" class="container-fluid edit-profile-page">
        <!-- EDIÇÃO DE PERFIL -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <form action="<?= BASE_URL ?>user_process.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <h1><?= $fullName ?></h1>
                            <p class="page-description">Altere seus dados no formulário abaixo:</p>

                            <div class="form-group">
                                <label for="name">Nome:</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="<?= $userData->name ?>">
                            </div>

                            <div class="form-group">
                                <label for="lastname">Sobrenome:</label>
                                <input type="text" name="lastname" id="lastname" class="form-control"
                                    value="<?= $userData->lastname ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control disabled"
                                    value="<?= $userData->email ?>" readonly>
                            </div>

                            <input type="submit" value="Alterar" class="card-btn">
                        </div>

                        <div class="col-md-4">
                            <div id="profile-image-container"
                                style="background-image: url('<?= BASE_URL ?>img/users/<?= $userData->image ?>');"></div>
                            
                            <div class="form-group">
                                <label for="image">Foto:</label>
                                <input type="file" class="form-control-file" name="image" id="image">
                            </div>

                            <div class="form-group">
                                <label for="bio">Sobre você:</label>
                                <textarea name="bio" id="bio" class="form-control"
                                    placeholder="Fale um pouco sobre você." rows="5"><?= $userData->bio ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ALTERAÇÃO DE SENHA -->
        <div class="row" id="change-password-container">
            <div class="col-md-4">
                <h2>Alterar a senha</h2>
                <p class="page-description">Digite e confirme sua nova senha.</p>
                <form action="<?= BASE_URL ?>user_process.php" method="post">
                    <input type="hidden" name="type" value="changePassword">

                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" name="password" id="password"
                            class="form-control" placeholder="Digite sua nova senha">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmar senha:</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="form-control" placeholder="Confirme sua nova senha">
                    </div>

                    <input type="submit" value="Alterar senha" class="card-btn">
                </form>
            </div>
        </div>
    </main>

<?php
    require_once 'templates/footer.php';
?>