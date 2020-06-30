<?php


namespace App\Controller;

use App\Service\FeedbackService;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/feedback", name="feedback")
     */
    public function actionFeedback(Request $request, FeedbackService $feedbackService)
    {
        $userId = $this->getUser()->getId();
        $productId = $request->get('product_id');
        $comment = $request->get('textarea');
        $rating = $request->get('stars');

        if ($request->isMethod('POST')) {
            if ($request->get('textarea') === "") {
                return $this->redirect($request->server->get('HTTP_REFERER'));
            }
            $feedbackService->addFeedback($userId, $productId, $comment, $rating);
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

}