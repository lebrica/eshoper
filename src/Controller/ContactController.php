<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function actionContact(Request $request, MailerService $mailer)
    {
        $form = $this->createForm(ContactType::class);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $mailer->sendContactMessage($form->getData());
                return $this->render('contact.html.twig', [
                    'form' => $form->createView(),
                    'message' => 'Сообщение отправлено'
                ]);
            }
        }
        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'message' => ''
        ]);
    }
}