<?php

require_once './models/Review.php';
require_once './models/Message.php';
require_once './dao/UserDaoMysql.php';

class ReviewDaoMysql implements ReviewDaoInterface
{
    private PDO $conn;
    private string $url;
    private Message $message;

    public function __construct(PDO $conn, string $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildReview(array $data): object
    {
        $reviewObject = new Review();

        $reviewObject->id = $data['id'];
        $reviewObject->rating = $data['rating'];
        $reviewObject->review = $data['review'];
        $reviewObject->users_id = $data['users_id'];
        $reviewObject->movies_id = $data['movies_id'];

        return $reviewObject;
    }

    public function create(Review $review)
    {
        $stmt = $this->conn->prepare('INSERT INTO reviews (
            rating, review, movies_id, users_id
        ) VALUES (
            :rating, :review, :movies_id, :users_id
        )');

        $stmt->bindParam(':rating', $review->rating);
        $stmt->bindParam(':review', $review->review);
        $stmt->bindParam(':movies_id', $review->movies_id);
        $stmt->bindParam(':users_id', $review->users_id);
        $stmt->execute();

        // Mensagem de sucesso
        $this->message->setMessage(
            'Crítica adicionada com sucesso!',
            'success',
            'back'
        );
    }

    public function getMoviesReview(int $id)
    {
        $reviews = [];

        $stmt = $this->conn->prepare('SELECT * FROM reviews
            WHERE movies_id = :movies_id
        ');
        $stmt->bindParam(':movies_id', $id);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return $reviews;
        }

        $reviewsData = $stmt->fetchAll( PDO::FETCH_ASSOC );
        $userDao = new UserDaoMysql($this->conn, $this->url);

        foreach ($reviewsData as $reviewData) {
            $reviewObject = $this->buildReview($reviewData);
            
            // Chamando dados do usuário
            $user = $userDao->findById($reviewObject->users_id);
            $reviewObject->user = $user;

            $reviews[] = $reviewObject;
        }

        return $reviews;
    }

    /**
     * Retorna true caso já tenha feito uma review e
     * false para caso não tenha feito
     * 
     * @param int $id refer movies_id
     * @param int $userId
     * 
     * @return bool
     */
    public function hasAlreadyReviewed(int $id, int $userId)
    {
        $stmt = $this->conn->prepare('SELECT * FROM reviews WHERE movies_id = :movies_id AND users_id = :users_id');
        $stmt->bindParam(':movies_id', $id);
        $stmt->bindParam(':users_id', $userId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Retorna a média das reviews ou 'Não avaliado' para os filmes
     * que ainda não receberam avaliação
     * 
     * @param int $id
     * 
     * @return int|float|string
     */
    public function getRatings(int $id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM reviews WHERE movies_id = :movies_id');
        $stmt->bindParam(':movies_id', $id);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return 'Não avaliado';
        }

        $rating = 0;

        $reviews = $stmt->fetchAll( PDO::FETCH_OBJ );
        foreach ($reviews as $review) {
            $rating += $review->rating;
        }
        $rating = $rating / count($reviews);

        return $rating;
    }
}
