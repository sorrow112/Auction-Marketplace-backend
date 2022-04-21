<?php

namespace App\Controller\Admin;


use App\Entity\Article;
use App\Entity\Enchere;
use App\Entity\Category;
use App\Entity\Fermeture;
use App\Entity\Reduction;
use App\Entity\Proposition;
use App\Entity\Augmentation;
use App\Entity\DemandeDevis;
use App\Entity\EnchereInverse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Marketplace D Enchere Pfe')
             ->setTranslationDomain('my-custom-domain')
             ->setTextDirection('ltr')
             ->renderContentMaximized()
             ->renderSidebarMinimized()
             ->disableUrlSignatures()
             ->generateRelativeUrls()
             ;
    }

    public function configureMenuItems(): iterable
    {
        return [MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
        MenuItem::section('Article'),
            MenuItem::linkToCrud('Articles', 'fa fa-tags', Article::class),
        MenuItem::section('Category'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
        MenuItem::section('Augmentation'),
            MenuItem::linkToCrud('Augmentations', 'fa fa-tags', Augmentation::class),
            MenuItem::section('DemandeDevis'),
            MenuItem::linkToCrud('DemandeDeviss', 'fa fa-tags', DemandeDevis::class),
            MenuItem::section('Enchere'),
            MenuItem::linkToCrud('Encheres', 'fa fa-tags', Enchere::class),
            MenuItem::section('EnchereInverse'),
            MenuItem::linkToCrud('EnchereInverses', 'fa fa-tags', EnchereInverse::class),
            MenuItem::section('Fermeture'),
            MenuItem::linkToCrud('Fermetures', 'fa fa-tags', Fermeture::class),
            MenuItem::section('Proposition'),
            MenuItem::linkToCrud('Propositions', 'fa fa-tags', Proposition::class),
            MenuItem::section('Reduction'),
            MenuItem::linkToCrud('Reductions', 'fa fa-tags', Reduction::class),

    ];

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
