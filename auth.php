<?php
    require_once 'templates/header.php';
?>

    <main id="main-container" class="container-fluid">
        <div class="col-md-12">
            <div class="row" id="auth-row">
                <!-- FAZER LOGIN -->
                <div class="col-md-4" id="login-container">
                    <h2>Entrar</h2>

                    <form action="<?= BASE_URL ?>auth_process.php" method="post">
                        <input type="hidden" name="type" value="login">
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email"
                                class="form-control" placeholder="Digite seu e-mail">
                        </div>

                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" name="password" id="password"
                                class="form-control" placeholder="Digite sua senha">
                        </div>

                        <input type="submit" class="card-btn" value="Entrar">
                    </form>
                </div>

                <!-- CRIAR CONTA -->
                <div class="col-md-4" id="register-container">
                    <h2>Criar Conta</h2>

                    <form action="<?= BASE_URL ?>auth_process.php" method="post">
                        <input type="hidden" name="type" value="register">
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email"
                                class="form-control" placeholder="Digite seu e-mail">
                        </div>

                        <div class="form-group">
                            <label for="name">Nome:</label>
                            <input type="text" name="name" id="name"
                                class="form-control" placeholder="Digite seu nome">
                        </div>

                        <div class="form-group">
                            <label for="lastname">Sobrenome:</label>
                            <input type="text" name="lastname" id="lastname"
                                class="form-control" placeholder="Digite seu sobrenome">
                        </div>

                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" name="password" id="password"
                                class="form-control" placeholder="Digite sua senha">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirmação de senha:</label>
                            <input type="password" name="confirm_password" id="confirm_password"
                                class="form-control" placeholder="Confirme sua sobrenome">
                        </div>

                        <input type="submit" class="card-btn" value="Registrar">
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php
    require_once 'templates/footer.php';
?>
