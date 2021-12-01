<?php

// traz $userData e $userDao
require_once './helpers/auth_verify.php';

require_once 'globals.php';
require_once 'connection.php';
require_once 'models/Movie.php';
require_once 'models/Message.php';
require_once 'models/Review.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/MovieDaoMysql.php';
require_once 'dao/ReviewDaoMysql.php';

$message = new Message(BASE_URL);
$movieDao = new MovieDaoMysql($conn, BASE_URL);
$reviewDao = new ReviewDaoMysql($conn, BASE_URL);

// Tipo do formulário
$type = filter_input(INPUT_POST, 'type');

if ($type === 'create') {

    // Recebendo dados do POST
    $rating = filter_input(INPUT_POST, 'rating');
    $review = filter_input(INPUT_POST, 'review');
    $movie_id = filter_input(INPUT_POST, 'movie_id');
    $users_id = $userData->id;

    $reviewObject = new Review();

    $movieData = $movieDao->findById($movie_id);

    if (! $movieData) {
        $message->setMessage(
            'Informações inválidas.',
            'error'    
        );
    }

    // Validação de dados mínimos
    if (empty($rating) || empty($review) || empty($movie_id)) {
        $message->setMessage(
            'Você precisa inserir a nota e o comentário.',
            'error',
            'back'
        );
    }

    $reviewObject->rating = $rating;
    $reviewObject->review = $review;
    $reviewObject->movies_id = $movie_id;
    $reviewObject->users_id = $users_id;

    $reviewDao->create($reviewObject);

} else {
    $message->setMessage(
        'Informações inválidas.',
        'error'
    );
}
