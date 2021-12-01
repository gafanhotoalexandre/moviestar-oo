<?php

class Review
{
    public int $id;
    public int $rating;
    public string $review;
    public int $users_id;
    public int $movies_id;
}

interface ReviewDaoInterface
{
    public function buildReview(array $data): object;
    public function create(Review $review);
    public function getMoviesReview(int $id);
    public function hasAlreadyReviewed(int $id, int $userId);
    public function getRatings(int $id);
}
