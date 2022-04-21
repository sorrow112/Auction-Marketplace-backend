<?php

namespace App\Controller\Admin;

use App\Entity\Augmentation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AugmentationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Augmentation::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
