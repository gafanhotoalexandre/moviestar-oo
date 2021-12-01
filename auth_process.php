<?php

require_once 'globals.php';
require_once 'connection.php';
require_once 'models/User.php';
require_once 'models/Message.php';
require_once 'dao/UserDaoMysql.php';

$message = new Message(BASE_URL);
$userDao = new UserDaoMysql($conn, BASE_URL);

// Resgatando o tipo do formulário
$type = filter_input(INPUT_POST, 'type');

// Verificação do tipo de formulário
if ($type === 'register') {

    $name = filter_input(INPUT_POST, 'name');
    $lastname = filter_input(INPUT_POST, 'lastname');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password');

    // Verificação de dados mínimos
    if (! ($name && $lastname && $email && $password)) {
        // enviar mensagem de erro, dados faltantes
        $message->setMessage('Por favor, preencha todos os campos.', 'error', 'back');
    }
    if ($password !== $confirmPassword) {
        // senhas diferentes
        $message->setMessage('As senhas não são iguais.', 'error', 'back');
    }
    if ($userDao->emailExists($email)) {
        // e-mail já existente
        $message->setMessage('Usuário já cadastrado, tente outro e-mail', 'error', 'back');
    }

    // INSERINDO NOVO USUÁRIO NO BANCO
    $user = new User();

    // Criação de token e senha
    $userToken = $user->generateToken();
    $finalPassword = $user->generatePassword($password);

    $user->name = $name;
    $user->lastname = $lastname;
    $user->email = $email;
    $user->password = $finalPassword;
    $user->token = $userToken;

    $auth = true;

    $userDao->create($user, $auth);

} else if ($type === 'login') {
    // TRABALHAR NO LOGIN
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    // autenticação
    if($userDao->authenticateUser($email, $password)) {
        $message->setMessage(
            'Seja bem-vindo!.',
            'success',
            'editprofile.php'
        );
    } else {
        $message->setMessage(
            'Usuário e/ou senha incorretos.',
            'error',
            'back'
        );
    }


} else {
    $message->setMessage(
        'Informações inválidas.',
        'error'
    );
}
