<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/changerole/{id}", name="change_role")
     */
    public function index($id)
    {


        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        $user->setName('Anatolij');
        $user->setRoles(["ROLE_ADMIN"]);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('products');
    }
}