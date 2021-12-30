<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    #[Route('/compte/address', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account_address/index.html.twig', [
         
        ]);
    }

    #[Route('/compte/ajouter-une-adresse', name: 'add_address')]
    public function add(): Response
    {
        $address = new Address;
        
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($this->request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
        }

        return $this->render('account_address/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
