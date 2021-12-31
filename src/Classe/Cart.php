<?php
namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class Cart 
{

    private $session;
    private $entityManager;

    public function __construct(Session $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart',[]);

        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        $this->session->set('cart',$cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart',[]);
        unset($cart[$id]);
        return $this->session->set('cart',$cart);
    }

    public function getFullCart()
    {
        $cartComplete = [];

        if($this->get()){

            foreach($this->get() as $id => $quantity){
    
                $object = $this->entityManager->getRepository(Product::class)->find($id);
                if(!$object) {
                    $this->delete($id);
                    continue;
                }
    
                $cartComplete[] = [
                    'product' => $object,
                    'quantity' => $quantity
                ];
            }

        }


        return $cartComplete;
    }


    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if($cart[$id] > 1){
            $cart[$id]--;
        }else {
            unset($cart[$id]);
        }

        return $this->session->set('cart',$cart);
    }

}