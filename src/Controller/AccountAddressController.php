<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{

    private $entityManager;
    private $cart;

    public function __construct(EntityManagerInterface $entityManager ,Cart $cart)
    {
        $this->entityManager = $entityManager;
        $this->cart = $cart;
    }

    #[Route('/compte/address', name: 'account_address')]
    public function index(): Response
    {


        return $this->render('account_address/index.html.twig', [
         
        ]);
    }

    #[Route('/compte/address/remove/{id}', name: 'remove_address')]
    public function remove($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->find($id);

        if($address && $this->getUser() == $address->getUser() ){
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        
        return $this->redirectToRoute('account_address');
    }

    #[Route('/compte/ajouter-une-adresse', name: 'add_address')]
    public function add(Request $request): Response
    {
        $address = new Address;
        
        $form = $this->createForm(AddressType::class,$address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            
            if($this->cart->get()){

                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
                
            }
        }

        return $this->render('account_address/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/modifier-une-adresse/{id}', name: 'edit_address')]
    public function edit(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->find($id);

        if(!$address || $this->getUser() != $address->getUser() ){

            return $this->redirectToRoute('account_address');
        }
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){

            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account_address/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
