<?php
namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    // ── LIST ──────────────────────────────────────────────
    #[Route('/', name: 'article_index')]
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    // ── SHOW ──────────────────────────────────────────────
    #[Route('/article/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    // ── NEW ───────────────────────────────────────────────
    #[Route('/article/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'Article créé avec succès !');
            return $this->redirectToRoute('article_index');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ── EDIT ──────────────────────────────────────────────
    #[Route('/article/edit/{id}', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès !');
            return $this->redirectToRoute('article_index');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form'    => $form->createView()
        ]);
    }

    // ── DELETE ────────────────────────────────────────────
    #[Route('/article/delete/{id}', name: 'article_delete')]
    public function delete(Article $article): Response
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();
        $this->addFlash('success', 'Article supprimé !');
        return $this->redirectToRoute('article_index');
    }
}
