<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\ArticleService;
use App\Template;

class ArticleController
{
    public function index(): Template
    {
        $search = $_GET['search'] ?? 'tesla';
        $articles = (new ArticleService())->execute($search);

        return new Template('article.twig', ['articles' => $articles->getArticles()]);
    }
}