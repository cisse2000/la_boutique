<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('commande/stripe', name: 'stripe')]
    public function index(): Response
    {
        

       Stripe::setApiKey('sk_test_51KCxZfG2MEht1Ob05MhjckzMtdLkooynDtBJ2LgxjWizMqMi7a5bCExLytGejhvW1HFwZ7HJxb5dZW7ahuDN3yiV00QUXyTnpZ');
        
        
        $YOUR_DOMAIN = 'https://localhost:8000/public';
        
        $checkout_session = Session::create([
          'line_items' => [[
            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
            'price' => '{{PRICE_ID}}',
            'quantity' => 1,
          ]],
          'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        
       
       return JsonResponse(); 
    }
}
