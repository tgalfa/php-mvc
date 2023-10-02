<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Exception\NotFoundException;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\News;

class NewsController extends Controller
{
    /**
     * Create a new NewsController.
     */
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware([
            'index',
            'store',
            'update',
            'delete',
        ]));
    }

    /**
     * Get the news view with all news.
     */
    public function index()
    {
        $news = News::findAll();
        $model = new News();

        return $this->render('news', compact('news', 'model'));
    }

    /**
     * Create a new news item.
     */
    public function store(Request $request, Response $response)
    {
        // Validate the request.
        $model = new News();

        // Load the request data into the model.
        $model->loadData($request->getBody());

        if (!$model->validation()) {
            $news = News::findAll();
            Application::$app->session->setFlash('error', 'Something went wrong.');

            return $this->render('news', compact('model', 'news'));
        }

        $model->save();

        Application::$app->session->setFlash('success', 'News item was created successfully.');

        return $response->redirect('/news');
    }

    /**
     * Update a news item.
     */
    public function update(Request $request, Response $response)
    {
        // Get Url parameter.
        $id = $request->getRouteParam('id');

        // Find the news item.
        $news = News::findOne(['id' => $id]);

        if (!$news) {
            throw new NotFoundException();
        }

        // Validate the request.
        $model = new News();

        // Load the request data into the model.
        $model->loadData($request->getBody());
        // remove line breaks
        $model->content = nl2br($model->content);

        if (!$model->validation()) {
            $news = News::findAll();
            $model->id = $id;
            Application::$app->session->setFlash('error', 'Something went wrong.');

            return $this->render('news', compact('model', 'news'));
        }

        // Update the news item.
        $model->update(['id' => $id]);

        Application::$app->session->setFlash('success', 'News item was updated successfully.');

        return $response->redirect('/news');
    }

    /**
     * Delete a news item.
     */
    public function delete(Request $request, Response $response)
    {
        // Get Url parameter.
        $id = $request->getRouteParam('id');

        // Find the news item.
        $news = News::findOne(['id' => $id]);

        $flash = [
            'type' => 'error',
            'message' => 'Something went wrong.',
        ];

        if ($news) {
            // Delete the news item.
            $news->delete();

            $flash = [
                'type' => 'success',
                'message' => 'News item was deleted successfully.',
            ];
        }

        Application::$app->session->setFlash($flash['type'], $flash['message']);

        return $response->redirect('/news');
    }
}
