<?php

namespace App\Controller;

use App\Classe\Cart;
use Doctrine\ORM\Database;
use Doctrine\ORM\EntityManager;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $cart;
    private $entityManager ;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;

    }

    #[Route('/mon-panier', name: 'cart')]
    public function index(): Response
    {   
        // dd($this->cart->getFullCart());
        return $this->render('cart/index.html.twig',[
            'cart' => $this->cart->getFullCart()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add($id): Response
    {   
        $this->cart->add($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(): Response
    {   
        $this->cart->remove();
        return $this->redirectToRoute('products');
    }

    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete($id): Response
    {   
        $this->cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease($id): Response
    {   
        $this->cart->decrease($id);
        return $this->redirectToRoute('cart');
    }
    

    
}
