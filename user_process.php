<?php

require_once 'globals.php';
require_once 'connection.php';
require_once 'models/User.php';
require_once 'models/Message.php';
require_once 'dao/UserDaoMysql.php';

$message = new Message(BASE_URL);
$userDao = new UserDaoMysql($conn, BASE_URL);

// Resgatando tipo do formulário
$type = filter_input(INPUT_POST, 'type');

// Atualizar usuário
if ($type === 'update') {

    // Regatando dados do usuário
    $userData = $userDao->verifyToken();

    // Recebendo dados do POST
    $name = filter_input(INPUT_POST, 'name');
    $lastname = filter_input(INPUT_POST, 'lastname');
    $email = filter_input(INPUT_POST, 'email');
    $bio = filter_input(INPUT_POST, 'bio');

    // Substituindo e atualizando
    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {

        $image = $_FILES['image'];
        
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $jpgTypes = ['image/jpeg', 'image/jpg',];

        // Checar o tipo de imagem
        if (in_array($image['type'], $imageTypes)) {

            // Checar se é JPEG/JPG
            if (in_array($image['type'], $jpgTypes)) {
                $imageFile = imagecreatefromjpeg($image['tmp_name']);
            // Imagem PNG
            } else {
                $imageFile = imagecreatefrompng($image['tmp_name']);
            }

            // Removendo imagem anterior
            $oldImagePath = $userData->image;
            unlink('./img/users/'. $oldImagePath);

            // Gerando nome da imagem | salvando seu caminho no banco e seu arquivo no backend
            $imageName = $userData->generateImageName();

            imagejpeg($imageFile, './img/users/'. $imageName, 80);

            $userData->image = $imageName;

        // Tipo de arquivo inválido
        } else {
            $message->setMessage(
                'Tipo inválido de imagem. Insira PNG ou JPEG.',
                'error',
                'back'
            );
        }
    }

    $userDao->update($userData);

// Atualizar senha do usuário
} else if ($type === 'changePassword') {

    // Recebendo dados
    $password = filter_input(INPUT_POST, 'password');
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password');

    // Regatando dados do usuário
    $userData = $userDao->verifyToken();
    $id = $userData->id;

    if (! ($password && $confirmPassword)) {
        $message->setMessage(
            'Preencha os campos para alterar a senha.',
            'error',
            'back'
        );    
    }
    if ($password != $confirmPassword) {
        $message->setMessage(
            'As senhas não são iguais!',
            'error',
            'back'
        );    
    }

    $user = new User();

    $finalPassword = $user->generatePassword($password);
    
    $user->password = $finalPassword;
    $user->id = $id;

    $userDao->changePassword($user);

} else {
    $message->setMessage(
        'Informações inválidas!',
        'error',
        'index.php'
    );
}
