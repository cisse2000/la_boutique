<?php

namespace App\Controller;

use App\Classe\Searche;
use App\Entity\Product;
use App\Form\SearcheType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Break_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager ;

    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {
        
        $searche = new Searche;
        $form = $this->createForm(SearcheType::class,$searche);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($searche) ;
            if(empty($products)){
                $this->redirectToRoute('products');
            }
            
        } else {

           $products = $this->entityManager->getRepository(Product::class)->findAll();
        }
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findBy(['slug' => $slug]);
        
        return $this->render('product/show.html.twig', [
            'products' => $products
        ]);
    }
}



