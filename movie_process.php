<?php

// traz $userData e $userDao
require_once './helpers/auth_verify.php';

require_once 'globals.php';
require_once 'connection.php';
require_once 'models/Movie.php';
require_once 'models/Message.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/MovieDaoMysql.php';

$movieDao = new MovieDaoMysql($conn, BASE_URL);
$message = new Message(BASE_URL);

// Resgatando o tipo de formulário
$type = filter_input(INPUT_POST, 'type');

if ($type === 'create') {

    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $trailer = filter_input(INPUT_POST, 'trailer');
    $category = filter_input(INPUT_POST, 'category');
    $length = filter_input(INPUT_POST, 'length');

    if (empty($title) || empty($description) || empty($category)) {
        $message->setMessage(
            'Você precisa adicionar pelo menos: título, descrição e categoria.',
            'error',
            'back'
        );
    }

    $movie = new Movie();
    $movie->title = $title;
    $movie->description = $description;
    $movie->trailer = $trailer;
    $movie->category = $category;
    $movie->length = $length;
    $movie->users_id = $userData->id;
    $movie->image = 'movie_cover.jpg';
    
    // Upload de imagem do filme
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

            // Gerando o nome da imagem
            $imageName = $movie->generateImageName();

            imagejpeg($imageFile, './img/movies/'. $imageName, 80);

            $movie->image = $imageName;

        } else {
            $message->setMessage(
                'Tipo inválido de imagem. Insira PNG ou JPEG.',
                'error',
                'back'
            );
        }
    }

    $movieDao->create($movie);

} else if ($type === 'delete') {

    // Recebendo dados do form
    $id = filter_input(INPUT_POST, 'id');

    $movie = $movieDao->findById($id);

    if (! $movie || ! ($movie->users_id === $userData->id)) {
        $message->setMessage(
            'Informações inválidas',
            'error',
        );    
    }

    // Removendo imagem
    if ($movie->image && $movie->image !== 'movie_cover.jpg') {
        $oldImagePath = $movie->image;
        unlink('./img/movies/'. $oldImagePath);    
    }
    
    $movieDao->destroy($movie->id);

} else if ($type === 'update') {

    // Recebendo dados
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $trailer = filter_input(INPUT_POST, 'trailer');
    $category = filter_input(INPUT_POST, 'category');
    $length = filter_input(INPUT_POST, 'length');
    $id = filter_input(INPUT_POST, 'id');
    
    $movieData = $movieDao->findById($id);

    if (! $movieData || !($movieData->users_id === $userData->id)) {
        $message->setMessage(
            'Informações inválidas!',
            'error'
        );
    }

    if (empty($title) || empty($description) || empty($category)) {
        $message->setMessage(
            'Você precisa adicionar pelo menos: título, descrição e categoria.',
            'error',
            'back'
        );
    }

    // Edição do filme
    $movieData->title = $title;
    $movieData->description = $description;
    $movieData->trailer = $trailer;
    $movieData->category = $category;
    $movieData->length = $length;

    // Upload de imagem do filme
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
            if ($movieData->image && $movieData->image !== 'movie_cover.jpg') {
                $oldImagePath = $movieData->image;
                unlink('./img/movies/'. $oldImagePath);    
            }
            
            // Gerando o nome da imagem
            $imageName = $movieData->generateImageName();

            imagejpeg($imageFile, './img/movies/'. $imageName, 80);

            $movieData->image = $imageName;

        } else {
            $message->setMessage(
                'Tipo inválido de imagem. Insira PNG ou JPEG.',
                'error',
                'back'
            );
        }
    }
    
    $movieDao->update($movieData);

} else {
    $message->setMessage(
        'Informações inválidas',
        'error',
    );
}
