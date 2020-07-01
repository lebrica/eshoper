<?php


namespace App\Controller;

use App\Service\ProductService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends  AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function actionIndex(ProductService $productService)
    {
        $categories = $productService->findAllCategory();

        return $this->render('admin.html.twig', ['categories' => $categories, 'message' => '']);
    }

    /**
     * @Route("admin/change-role", name="change_role")
     */
    public function changeRoleUser(Request $request, UserService $userService)
    {
        $email = $request->get('email');
        $role = $request->get('role');
        if ($request->isMethod('POST')) {
            if ($userService->changeRole($email, $role) === null) {
                $message = 'Пользователя с таким email нет';
            } else {
                $userService->changeRole($email, $role);
                $message = 'Роль пользователя изменена';
            }
            return $this->render('admin.html.twig', ['message' => $message] );
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("admin/add-product", name="add_product")
     */
    public function addProduct(Request $request, ProductService $productService)
    {
        $data = $request->request->all();

        if ($request->isMethod('POST')) {
            foreach ($data as $field => $val) {
                if ($val === '') {
                    $message = 'Заполните поле - ' .$field;
                    return $this->render('admin.html.twig', ['message' => $message]);
                }
            }
            $productService->addNewProduct($data);
            return $this->render('admin.html.twig', ['message' => 'Продукт добавлен']);
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("admin/add-category", name="add_category")
     */
    public function addCategory(Request $request, ProductService $productService)
    {
        $data = $request->request->all();

        if ($request->isMethod('POST')) {
            if ($data['category'] === '') {
                $message = 'Заполните имя категории';
                return $this->render('admin.html.twig', ['message' => $message] );
            }
            $productService->addNewCategory($data);
            $message = 'Категория добавлена';
            return $this->render('admin.html.twig', ['message' => $message] );
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/delete-product", name="delete_product")
     */
    public function deleteProduct(Request $request, ProductService $productService)
    {
        $productCode = $request->get('product-code');

        if ($request->isMethod('POST')) {
            if ($productService->deleteProduct($productCode) === null) {
                $message = 'Продукта с таким кодом нет';
            } else {
                $productService->deleteProduct($productCode);
                $message = 'Продукт удален';
            }
            return $this->render('admin.html.twig', ['message' => $message]);
        }
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/change-status-category", name="change_status_category")
     */
    public function changeStatusCategory(Request $request, ProductService $productService)
    {
        $nameCategory = $request->get('category');

        if ($request->isMethod('POST')) {
            $productService->changeStatusCategory($nameCategory);
            return $this->render('admin.html.twig', ['message' => 'Статус категории изменен']);
        }
        return $this->redirectToRoute('admin');
    }
}