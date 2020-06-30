<?php


namespace App\Service;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;

class FeedbackService
{
    private $productRepository;
    private $userRepository;
    private $feedbackRepository;

    public function __construct(
        ProductRepository $productRepository,
        UserRepository $userRepository,
        FeedbackRepository $feedbackRepository)
    {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->feedbackRepository = $feedbackRepository;
    }

    public function addFeedback(int $userId, int $productId, $comment, $rating)
    {
        $user = $this->userRepository->find($userId);
        $product = $this->productRepository->find($productId);

        $feedback = new Feedback();
        $feedback->setComments($comment);
        $feedback->setRating( $rating);
        $feedback->setDate(new \DateTime());
        $feedback->setUser_id($user);
        $feedback->setProduct_id($product);

        return $this->productRepository->load($feedback);
    }

    public function findLastComments(int $id, int $count = 3): array
    {
        return $this->feedbackRepository->findLastComment($id, $count);
    }

    public function findCommentsOneProduct(int $id): ?int
    {
        return $this->feedbackRepository->findCommentsOneProduct($id);
    }

    public function findAvgRatingProduct(int $id): ?int
    {
        return $this->feedbackRepository->findAvgRatingOneProduct($id);
    }
}