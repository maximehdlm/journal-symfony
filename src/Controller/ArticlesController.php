<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\ArticlesRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles", methods={"GET"})
     */
    public function index(ArticlesRepository $repo, CategorieRepository $repoCategorie): Response
    {
        $articles = $repo->findBy(array(),array('id' => 'DESC'));
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'categorie' => $repoCategorie->findAll()
        ]);
    }

    /**
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            if($form->isValid()) {

                //récuperation de l'image transmise
                $image = $form->get('image')->getData();

                //je genere un nom unique de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension(); //guessExtension sert à deviner le type de fichier(jpeg, npg...)

                //copie du fichier image dans le dossier upload
                $image->move(
                    $this->getParameter('img_directory'),
                    $fichier
                );

                //stockage du nom de l'image dans la bdd
                $article->setImage($fichier);
                 

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();
                $this->addFlash('success','L\'article a été rajouté avec succés');
                return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
                }else{
                $this->addFlash('error','Erreur lors de l\'enregistrement, champs incorrects');
                }
            }

        return $this->renderForm('articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET"})
     */
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //récuperation de l'image transmise
            $image = $form->get('image')->getData();

            //je genere un nom unique de fichier
            $fichier = md5(uniqid()) . '.' . $image->guessExtension(); //guessExtension sert à deviner le type de fichier(jpeg, npg...)

            //copie du fichier image dans le dossier upload
            $image->move(
                $this->getParameter('img_directory'),
                $fichier
            );

            //stockage du nom de l'image dans la bdd
            $article->setImage($fichier);
            
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','L\'article a été modifié avec succés');
            return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('delete','L\'article a été supprimé avec succés');
        }

        return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/categorie/{categorie}"),name="categ_article", methods={"GET"})
     */
    public function filtreCateg(ArticlesRepository $repoArticle, CategorieRepository $repoCategorie, $categorie): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $repoArticle->findBy(array('categorie'=> $categorie)),
            'categorie' => $repoCategorie->findAll()
        ]);

    }


}
