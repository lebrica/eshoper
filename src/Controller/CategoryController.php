<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use App\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $userService;

    public function __construct(ProductService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin")
     */
    public function indexView()
    {


        return $this->render('test.html.twig');
    }
}