<?php
namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier 
{
    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    
}