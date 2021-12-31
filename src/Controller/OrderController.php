<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{

    private $cart;
    private $entityManager;

    public function __construct(Cart $cart, EntityManagerInterface $entityManager )
    {
        $this->cart = $cart;
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(Request $request): Response
    {

        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('add_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $this->cart->getFullCart()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods:['POST'])]
    public function add(Request $request): Response
    {
       
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //Enregistrer ma commande.

            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '. 
            $delivery->getLastname();
            
            $delivery_content .= '<br/>'. $delivery->getPhone();
            
            
            if($delivery->getCompany()){
                
                $delivery_content .= '<br/>'. $delivery->getCompany();
            }
            
            $delivery_content .= '<br/>'. $delivery->getAddress();
            $delivery_content .= '<br/>'. $delivery->getPostal() . ' '.  $delivery->getCity();;
            
            $delivery_content .= '<br/>'. $delivery->getCountry();

            $date = new \DateTimeImmutable();
            $order = new Order();
            

            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);
            
            $this->entityManager->persist($order);

            $orderDetails = new OrderDetails();

            foreach($this->cart->getFullCart() as $product){
                
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);
            }
            // $this->entityManager->flush();
            
            return $this->render('order/add.html.twig', [
                'cart' => $this->cart->getFullCart(),
                'carrier' => $carriers,
                'delivery' => $delivery_content
            ]);
        }

        return $this->redirectToRoute('cart');

    }
}
