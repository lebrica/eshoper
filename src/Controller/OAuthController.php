<?php


namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class OAuthController extends AbstractController
{
    /**
     * @Route("/connect/facebook", name="connect_facebook")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function redirectToFacebookConnect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect([
                'email', 'public_profile'
            ]);
    }

    /**
     * @Route("/connect/google", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function redirectToGoogleConnect(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'email', 'profile'
            ]);
    }

    /**
     * @Route("/facebook/auth", name="facebook_auth")
     */
    public function connectFacebookCheck()
    {
        if (!$this->getUser()) {
            return new JsonResponse(['status' => false, 'message' => "User not found!"]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/google/auth", name="google_auth")
     */
    public function connectGoogleCheck()
    {
        if (!$this->getUser()) {
            return new JsonResponse(['status' => false, 'message' => "User not found!"]);
        } else {
            return $this->redirectToRoute('home');
        }
    }
}