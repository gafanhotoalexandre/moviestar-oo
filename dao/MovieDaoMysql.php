<?php

require_once 'models/Movie.php';
require_once 'models/Message.php';
require_once 'dao/ReviewDaoMysql.php';

class MovieDaoMysql implements MovieDaoInterface
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

    public function buildMovie(array $data): object
    {
        $movie = new Movie();

        $movie->id = $data['id'];
        $movie->title = $data['title'] ?? '';
        $movie->description = $data['description'] ?? '';
        $movie->image = $data['image'] ?? '';
        $movie->trailer = $data['trailer'] ?? '';
        $movie->category = $data['category'] ?? '';
        $movie->length = $data['length'] ?? '';
        $movie->users_id = $data['users_id'];

        // Recebendo ratings do filme
        $reviewDao = new ReviewDaoMysql($this->conn, $this->url);
        $rating = $reviewDao->getRatings($movie->id);

        $movie->rating = $rating;

        return $movie;
    }

    public function findAll(): array
    {
        return [];
    }

    public function getLatesMovies(): array
    {
        $movies = [];
        $stmt = $this->conn->query('SELECT * FROM movies ORDER BY id DESC');

        if (! ($stmt->rowCount() > 0)) {
            return $movies;
        }

        $moviesArray = $stmt->fetchAll( PDO::FETCH_ASSOC );
        
        foreach ($moviesArray as $movie) {
            $movies[] = $this->buildMovie($movie);
        }

        return $movies;
    }

    public function getMoviesByCategory(string $category): array
    {
        $movies = [];

        $stmt = $this->conn->prepare('
            SELECT * FROM movies 
            WHERE category = :category 
            ORDER BY id DESC'
        );
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return $movies;
        }

        $moviesArray = $stmt->fetchAll( PDO::FETCH_ASSOC );

        foreach ($moviesArray as $movie) {
            $movies[] = $this->buildMovie($movie);
        }

        return $movies;
    }

    public function getMoviesByUserId(int $id): array
    {
        $UserMovies = [];

        $stmt = $this->conn->prepare('SELECT * FROM movies WHERE users_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return $UserMovies;
        }

        $moviesArray = $stmt->fetchAll( PDO::FETCH_ASSOC );

        foreach ($moviesArray as $movie) {
            $UserMovies[] = $this->buildMovie($movie);
        }

        return $UserMovies;
    }

    public function findById(int $id)
    {
        $movie = [];

        $stmt = $this->conn->prepare('SELECT * FROM movies WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return false;
        }

        $movieArray = $stmt->fetch( PDO::FETCH_ASSOC );
        $movie = $this->buildMovie($movieArray);

        return $movie;
    }

    /**
     * @param string $title
     * 
     * @return array|object
     */
    public function findByTitle(string $title = '')
    {
        $movies = [];

        $stmt = $this->conn->prepare('SELECT * FROM movies
            WHERE title LIKE :title');

        $stmt->bindValue(':title', '%'. $title .'%');
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return [];
        }

        $movieArray = $stmt->fetchAll( PDO::FETCH_ASSOC );
        
        foreach ($movieArray as $movie) {
            $movies[] = $this->buildMovie($movie);
        }

        return $movies;
    }

    public function create(Movie $movie)
    {
        $stmt = $this->conn->prepare('INSERT INTO movies (
            title, description, image, trailer, category, length, users_id
        ) VALUES (
            :title, :description, :image, :trailer, :category, :length, :users_id
        )');

        $stmt->bindParam(':title', $movie->title);
        $stmt->bindParam(':description', $movie->description);
        $stmt->bindParam(':image', $movie->image);
        $stmt->bindParam(':trailer', $movie->trailer);
        $stmt->bindParam(':category', $movie->category);
        $stmt->bindParam(':length', $movie->length);
        $stmt->bindParam(':users_id', $movie->users_id);

        $stmt->execute();

        // redirecionamento para perfil do usuário
        $this->message->setMessage(
            'Filme adicionado com sucesso!',
            'success',
            'editprofile.php'
        );
    }

    public function update(Movie $movie)
    {
        $stmt = $this->conn->prepare('UPDATE movies SET
                title = :title,
                description = :description,
                image = :image,
                category = :category,
                trailer = :trailer,
                length = :length
            WHERE id = :id
        ');

        $stmt->bindParam(':title', $movie->title);
        $stmt->bindParam(':description', $movie->description);
        $stmt->bindParam(':image', $movie->image);
        $stmt->bindParam(':category', $movie->category);
        $stmt->bindParam(':trailer', $movie->trailer);
        $stmt->bindParam(':length', $movie->length);
        $stmt->bindParam(':id', $movie->id);
        $stmt->execute();

        // Messagem de sucesso por atualizar filme
        $this->message->setMessage(
            'Filme atualizado com sucesso!',
            'success',
            'dashboard.php'
        );        
    }

    public function destroy(int $id)
    {
        $stmt = $this->conn->prepare('DELETE FROM movies WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Messagem de sucesso por remover filme
        $this->message->setMessage(
            'Filme removido com sucesso!',
            'success',
            'dashboard.php'
        );
    }

}
