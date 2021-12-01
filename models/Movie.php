<?php

class Movie
{
    public int $id;
    public string $title;
    public string $description;
    public string $image;
    public string $trailer;
    public string $category;
    public string $length;
    public int $users_id;

    public function generateImageName(): string
    {
        $imageName = bin2hex(random_bytes(60)) .'.jpg';
        return $imageName;
    }
}

interface MovieDaoInterface
{
    public function buildMovie(array $data): object;
    public function findAll(): array;
    public function getLatesMovies(): array;
    public function getMoviesByCategory(string $category): array;
    public function getMoviesByUserId(int $id): array;
    public function findById(int $id);
    public function findByTitle(string $title);
    public function create(Movie $movie);
    public function update(Movie $movie);
    public function destroy(int $id);
}
