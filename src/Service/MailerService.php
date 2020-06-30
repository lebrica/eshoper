<?php


namespace App\Service;

use App\Entity\User;
use http\Message;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;


class MailerService
{
    public const FROM_ADDRESS = 'eshoper@eshop.ua';
    public  const ADMIN_EMAIL = 'testphp72019@gmail.com';

    private $mailer;
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationMessage(User $user)
    {
        $messageBody = $this->twig->render('message/confirmation.html.twig', [
            'user' => $user
        ]);

        $message = new Swift_Message();
        $message
            ->setSubject('Вы успешно прошли регистрацию')
            ->setFrom(self::FROM_ADDRESS)
            ->setTo($user->getEmail())
            ->setBody($messageBody, 'text/html');

        $this->mailer->send($message);
    }

    public function sendContactMessage(array $data)
    {
        $messageBody = $this->twig->render('message/contact_message.html.twig', [
            'message' => $data
        ]);

        $message = new Swift_Message();
        $message
            ->setFrom(self::ADMIN_EMAIL)
            ->setTo($data['email'])
            ->setSubject($data['subject'])
            ->setBody($messageBody, 'text/html');

        $this->mailer->send($message);
    }

    public function sendOrderMessage(array $data, array $products,array $countEveryProduct,int $totalPrice)
    {
        $messageBody = $this->twig->render('message/order_message.html.twig', [
            'products_in_cart' => $products,
            'count_one_product_in_cart' => $countEveryProduct,
            'total_price' => $totalPrice,
            'message' => $data
        ]);

        $message = new Swift_Message();
        $message
            ->setFrom(self::ADMIN_EMAIL)
            ->setSubject('Order placement')
            ->setBody($messageBody, 'text/html');

        $this->mailer->send($message);
    }
}