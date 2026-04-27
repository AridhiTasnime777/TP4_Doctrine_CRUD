<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\CategorySearch;
use App\Entity\PriceSearch;
use App\Entity\PropertySearch;
use App\Form\ArticleType;
use App\Form\CategorySearchType;
use App\Form\CategoryType;
use App\Form\PriceSearchType;
use App\Form\PropertySearchType;
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

    // ── HOME + RECHERCHE PAR NOM ──────────────────────────
    #[Route('/', name: 'article_list')]
    public function home(Request $request): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $propertySearch->getNom();
            $articles = $this->entityManager
                ->getRepository(Article::class)
                ->findBy(['nom' => $nom]);
        } else {
            $articles = $this->entityManager
                ->getRepository(Article::class)
                ->findAll();
        }

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'form'     => $form->createView()
        ]);
    }

    // ── NEW ARTICLE ───────────────────────────────────────
    #[Route('/article/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'Article créé !');
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView()
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

    // ── EDIT ──────────────────────────────────────────────
    #[Route('/article/edit/{id}', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Article modifié !');
            return $this->redirectToRoute('article_list');
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
        return $this->redirectToRoute('article_list');
    }

    // ── NEW CATEGORY ──────────────────────────────────────
    #[Route('/category/newCat', name: 'new_category', methods: ['GET', 'POST'])]
    public function newCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Catégorie créée !');
            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/newCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ── RECHERCHE PAR CATEGORIE ───────────────────────────
    #[Route('/article/search/category', name: 'article_search_category', methods: ['GET', 'POST'])]
    public function searchByCategory(Request $request): Response
    {
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class, $categorySearch);
        $form->handleRequest($request);

        $articles = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categorySearch->getCategory();
            if ($category) {
                $articles = $this->entityManager
                    ->getRepository(Article::class)
                    ->findBy(['category' => $category]);
            }
        }

        return $this->render('articles/searchCategory.html.twig', [
            'articles' => $articles,
            'form'     => $form->createView()
        ]);
    }

    // ── RECHERCHE PAR PRIX ────────────────────────────────
    #[Route('/article/search/price', name: 'article_search_price', methods: ['GET', 'POST'])]
    public function searchByPrice(Request $request): Response
    {
        $priceSearch = new PriceSearch();
        $form = $this->createForm(PriceSearchType::class, $priceSearch);
        $form->handleRequest($request);

        $articles = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $minPrice = $priceSearch->getMinPrice();
            $maxPrice = $priceSearch->getMaxPrice();
            $all = $this->entityManager->getRepository(Article::class)->findAll();

            foreach ($all as $article) {
                $prix = $article->getPrix();
                $passesMin = $minPrice === null || $prix >= $minPrice;
                $passesMax = $maxPrice === null || $prix <= $maxPrice;
                if ($passesMin && $passesMax) {
                    $articles[] = $article;
                }
            }
        }

        return $this->render('articles/searchPrice.html.twig', [
            'articles' => $articles,
            'form'     => $form->createView()
        ]);
    }
}
