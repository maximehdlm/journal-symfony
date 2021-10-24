<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    /**
     * @Route("/categorie", name="categorie", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            if($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();
                return $this->redirectToRoute('categorie', [], Response::HTTP_SEE_OTHER);
                }else{
                $this->addFlash('error','Erreur lors de l\'enregistrement, champs incorrects');
                }
            }

        return $this->renderForm('categorie/index.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }
}
