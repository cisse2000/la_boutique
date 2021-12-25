<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            ImageField::new('illustration', 'Image')->setUploadDIr('public/uploads/'),
            TextField::new('name','Nom'),
            SlugField::new('slug','Slug')->setTargetFieldName('name')->onlyOnIndex(),
            TextField::new('subtitle','Sous titre'),
            TextEditorField::new('content','Description'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            AssociationField::new('category','Catégories'),
            DateField::new('createdAt','Créer le')->onlyOnDetail()
        ];
    }
    
}
