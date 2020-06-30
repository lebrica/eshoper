<?php

namespace App\Controller;

use App\Event\RegisteredUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use App\Form\UserType;
use App\Entity\User;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function actionRegister(Request $request, UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userService->register($user);

            $userRegisteredEvent = new RegisteredUserEvent($user);
            $eventDispatcher->dispatch($userRegisteredEvent, RegisteredUserEvent::NAME);
            return $this->render('signup.html.twig', [
                'form' => $form->createView(),
                'message' => 'Подтвердите регистрацию на своей почте'
            ]);
        }
        return $this->render('signup.html.twig', [
            'form' => $form->createView(),
            'message' => ''
        ]);
    }

    /**
     * @Route("/confirm/{code}", name="email_confirmation")
     */
    public function confirmEmail(UserService $userService, string $code)
    {
        $userService->confirmed($code);

        if ($userService->confirmed($code) === false) {
            return new Response('404');
        }

        return $this->render('account_confirm.html.twig');
    }
}
