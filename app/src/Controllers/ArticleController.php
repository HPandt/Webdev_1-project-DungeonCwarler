<?php

namespace App\Controllers;

use App\Services\IArticleService;
use App\Services\ArticleService;

class ArticleController
{
    private IArticleService $articleService;

    public function __construct()
    {
        // mock service for doing article CRUD
        $this->articleService = new ArticleService();
    }

    public function index($vars = [])
    {
        // we are going to do everything via JS and an API so, we will simply load a view here
        require __DIR__ . '/../Views/article/index.php';
    }

    public function apiGetAll()
    {
        // use the article service to get all articles and return as JSON
        $articles = $this->articleService->getAllArticles();
        // set the content type header
        header('Content-Type: application/json');
        // return the data as JSON to the client
        echo json_encode($articles);
    }

    public function apiCreate()
    {
        header('Content-Type: application/json');
        // get the $_POST data and decode into an associative array
        $data = json_decode(file_get_contents('php://input'), true);
        // use ths service to save the data
        $article = $this->articleService->createArticle($data);
        // return the created article as JSON
        echo json_encode($article);
    }
}