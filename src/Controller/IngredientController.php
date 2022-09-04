<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**Injection de dépendance du Repository d'ingredient dans la function index pour aller chercher des données en DB avec  FindAll()
     *fonction pour Display tous les ingredients en db
     */
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository,PaginatorInterface $paginator,Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page',1),10);

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }
    #[Route('/ingredient/nouveau','ingredient_new',methods: ['GET','POST'])]
    public function new(Request $request,EntityManagerInterface $manager) : Response
    {
        $ingredient = new Ingredient ();
        $form =$this->createForm(IngredientType::class,$ingredient);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success','Votre ingrédient a été créé avec succès!'
            );
            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('pages/ingredient/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /** function qui affiche un formulaire et qui recuprère l'ingredient avec FindOneBy du Repository pour Update un ingrédient
    **/
    #[Route('/ingredient/edition/{id}','ingredient_edit',methods: ['GET','POST'])]
    public function edit(Ingredient $ingredient,Request $request,EntityManagerInterface $manager) : Response
    {
        $form =$this->createForm(IngredientType::class,$ingredient);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success','Votre ingrédient a été modifié avec succès!'
            );
            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('pages/ingredient/edit.html.twig', [
            'form'=>$form->createView()
        ]);

    }
    #[Route('/ingredient/suppression/{id}','ingredient_delete',methods: ['GET'])]
    public function delete(EntityManagerInterface $manager,Ingredient $ingredient) : Response {

    /**
    Appelle $ingredient de l'Entity Ingredient pour récup {id} et le passer au manager pour le remove en db
    **/
        $manager->remove($ingredient);
        $manager->flush();
        $this->addFlash(
            'success','Votre ingrédient a été supprimé avec succès!'
        );
        return $this->redirectToRoute('app_ingredient');
    }

}
