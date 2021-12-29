<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            
        ]);
    }

    #[Route('/compte/modifier-mon-mot-de-pass', name: 'account_password')]
    public function account_password(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $user = new User;
        $form = $this->createForm(ChangePasswordUserType::class,$user);
        $message = null;
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $old_password = $user->getPassword();
            if($userPasswordHasher->isPasswordValid($user,$old_password)){

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData() ));
                
                $message = "Mot de passe effectué avec succès.";
                // return $this->redirectToRoute('account_password');
            } else {
                $message = "Votre mot de passe est incorrect.";
            }


            
        }



        return $this->render('account/account_password.html.twig', [
            'form' => $form->createView(),
            'sms_password' => $message 
        ]);
    }
}
