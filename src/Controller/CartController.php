<?php


namespace App\Controller;

use App\Form\CheckoutType;
use App\Service\CartService;
use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * @Route("/product/cart", name="product_cart")
     */
    public function actionCart()
    {
        $countEveryProduct = $this->cartService->getProductInCart();

        if ($countEveryProduct) {
            $productsIds = array_keys($countEveryProduct);
            $products = $this->cartService->getProductsByIds($productsIds);
            $totalPrice = $this->cartService->getTotalPrice($products);

            return $this->render('cart.html.twig', [
                'products_in_cart' => $products,
                'count_one_product_in_cart' => $countEveryProduct,
                'total_price' => $totalPrice
            ]);
        }
        return $this->render('clear.cart.html.twig');

    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id"="\d+"})
     */
    public function actionAdd(Request $request, int $id)
    {
        return new Response($this->cartService->addProduct($id));
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_in_cart")
     */
    public function actionDelete(Request $request, int $id)
    {
        $this->cartService->deleteProductInCart($id);

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("/cart/checkout", name="cart_checkout")
     */
    public function actionCheckout(Request $request, MailerService $mailerService)
    {
        $countEveryProduct = $this->cartService->getProductInCart();
        $productsIds = array_keys($countEveryProduct);
        $products = $this->cartService->getProductsByIds($productsIds);
        $totalPrice = $this->cartService->getTotalPrice($products);

        $form = $this->createForm(CheckoutType::class);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mailerService->sendOrderMessage($form->getData(), $products, $countEveryProduct, $totalPrice);
                return $this->render('checkout-order.html.twig', ['form' => $form->createView(),
                    'message' => 'Ваш заказ отправлен']);
            }
        }
        return $this->render('checkout-order.html.twig', ['form' => $form->createView(),
            'message' => '']);
    }
}