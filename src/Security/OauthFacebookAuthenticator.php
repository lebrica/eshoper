<?php


namespace App\Security;

use App\Entity\SocialUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OauthFacebookAuthenticator extends SocialAuthenticator
{

    private $clientRegistry;
    private $em;
    private $userRepository;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, UserRepository $userRepository, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->router->generate('login'));
    }


    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $googleUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        $existingUser = $this->userRepository
            ->findSocialId($googleUser->getId());

        if ($existingUser) {
            return $existingUser;
        }

        $user = $this->userRepository
            ->findOneBy(['email' => $email]);
        if (!$user) {
            $user = new User();
            $userSocial = new SocialUser();
            $user->fromSocialRequest($email, $googleUser->getName());
            $userSocial->setUser_id($user);
            $userSocial->setClientId($googleUser->getId());
            $userSocial->setOauthType('Facebook');

            $this->em->persist($userSocial);
            $this->em->flush();
        } else {
            $userSocial = new SocialUser();
            $userSocial->setUser_id($user);
            $userSocial->setClientId($googleUser->getId());
            $userSocial->setOauthType('Google');

            $this->em->persist($userSocial);
            $this->em->flush();
        }

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response('Authentication failed', Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    private function getFacebookClient(): \KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('facebook');
    }

    public function supportsRememberMe(): bool
    {
        return true;
    }
}