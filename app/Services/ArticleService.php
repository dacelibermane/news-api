<?php declare(strict_types=1);

namespace App\Services;

use App\ArticlesCollection;
use App\Models\Article;
use jcobhams\NewsApi\NewsApi;

class ArticleService
{
    public function execute(string $search): ArticlesCollection
    {
        $apiKey = $_ENV['API_KEY'];
        $newsApi = new NewsApi($apiKey);

        $articlesApiResponse = $newsApi->getEverything($search);

        $articles = new ArticlesCollection();
        foreach ($articlesApiResponse->articles as $article) $articles->addArticles(new Article(
            $article->title,
            $article->description,
            $article->url,
            $article->urlToImage
        ));
        return $articles;
    }
}