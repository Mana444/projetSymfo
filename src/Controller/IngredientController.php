<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**Injection de dépendance du Repository d'ingredient dans la function index pour aller chercher des données en DB avec  FindAll()
     **/
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository): Response
    {
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $repository->findAll()
        ]);
    }
}
