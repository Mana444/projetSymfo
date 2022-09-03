<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette',methods: ['GET'])]
    public function index(
        PaginatorInterface $paginator,
        RecetteRepository $repository,
        Request $request
    ): Response
    {
        $recettes = $paginator ->paginate(
            $repository->findAll(),
            $request->query->getInt('page',1),5
        );

        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/nouveau','recette_new',methods: ['GET','POST'])]
    public function new(Request $request,EntityManagerInterface $manager) : Response
    {
        $recette = new Recette();
        $form =$this->createForm(RecetteType::class,$recette);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $recette= $form->getData();
            $manager->persist($recette);
            $manager->flush();
            $this->addFlash(
                'success','Votre recette a été créé avec succès!'
            );
            return $this->redirectToRoute('app_recette');
        }
        return $this->render('pages/recette/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /** function qui affiche un formulaire et qui recuprère la recette avec FindOneBy du Repository pour la Update
     **/
    #[Route('/recette/edition/{id}','recette_edit',methods: ['GET','POST'])]
    public function edit(Recette $recette,Request $request,EntityManagerInterface $manager) : Response
    {
        $form =$this->createForm(RecetteType::class,$recette);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();
            $this->addFlash(
                'success','Votre recette a été modifié avec succès!'
            );
            return $this->redirectToRoute('app_recette');
        }
        return $this->render('pages/recette/edit.html.twig', [
            'form'=>$form->createView()
        ]);

    }

}
