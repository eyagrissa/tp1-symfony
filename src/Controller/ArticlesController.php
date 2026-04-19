<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ArticlesController extends AbstractController
{
    #[Route('/articles/nouveau', name: 'app_article_nouveau')]
    #[IsGranted('ROLE_USER')]
    public function nouveau(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setTitre('Mon premier article');
        $article->setContenu('Ceci est le contenu de mon premier article créé avec Doctrine.');
        $article->setAuteur('Étudiant');
        $article->setDataCreation(new \DateTime());
        $article->setPublie(true);

        // Étape 15 — affecter l'auteur connecté
        $article->setAuteurUser($this->getUser());

        $em->persist($article);
        $em->flush();

        return new Response("Article créé avec l'id : " . $article->getId());
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_detail', requirements: ['id' => '\d+'])]
    public function detail(Article $article): Response
    {
        return $this->render('articles/detail.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/modifier', name: 'app_article_modifier', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function modifier(Article $article, EntityManagerInterface $em): Response
    {
        // Étape 16 — restreindre la modification à l'auteur
        if ($this->getUser() !== $article->getAuteurUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas l\'auteur !');
        }

        // TODO: logique de modification ici

        return new Response("Modification de l'article : " . $article->getId());
    }
}