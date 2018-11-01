<?php

namespace App\Controller;

/**
 * This controller handles HTTP requests and execute business logic for 'articles' to prepare the response.
 * The methods should query the model layer, and prepare a response by rendering a template in the view.
 *
 * @package App\Controller
 */
class ArticlesController extends AppController
{
    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
    }

    /**
     * Fetch a paginated set of articles from the database, using the Articles Model that is automatically loaded via
     * naming conventions. It then uses set() to pass the articles into the template.
     * The template will be rendered automatically after the controller action completes.
     *
     * Method: GET
     * Route: /articles
     * Route: /articles/index
     *
     * @throws \Exception
     */
    public function index(): void
    {
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    /**
     * Fetch a record by the given parameter and render the view template.
     *
     * Method: GET
     * Route: /articles/view/{$slug}
     *
     * @param string $slug
     */
    public function view($slug = null): void
    {
        // use dynamic finder findBySlug() to create a basic query that finds articles by a given slug.
        // then use firstOrFail() to either fetch the first record, or throw a NotFoundException
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    /**
     * If the HTTP method of the request was POST, try to save the data using the Articles model.
     * If for some reason it doesn’t save, just render the view.
     * Redirect to index on success.
     *
     * Method: GET|POST
     * Route: /articles/add
     *
     * @return \Cake\Http\Response|null
     */
    public function add(): ?\Cake\Http\Response
    {
        $article = $this->Articles->newEntity();
        // $this->request is available for every request
        pr($this->request->getData());
        if ($this->request->is('post')) {

            $article = $this->Articles->patchEntity($article, $this->request->getData());
            // Hardcoding the user_id is temporary, and will be removed later when we build authentication out.
            $article->user_id = 1;
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }

        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list');

        // set the view context
        $this->set(compact('article', 'tags'));
        return null;
    }

    /**
     * Updates the article found by given parameter with the values from request.
     * If no parameter was passed or the article does not exist, a NotFoundException will be thrown.
     * Redirect to index on success.
     *
     * Method: GET|POST|PUT
     * Route: /articles/edit
     *
     * @param string $slug
     *
     * @return \Cake\Http\Response|null
     */
    public function edit($slug): ?\Cake\Http\Response
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list');

        // set the view context
        $this->set(compact('article', 'tags'));
        return null;
    }

    /**
     * Deletes the article found by given parameter.
     * If no parameter was passed or the article does not exist, a NotFoundException will be thrown.
     * Redirect to index on success.
     *
     * Method: POST|DELETE
     * Route: /articles/delete
     *
     * @param string $slug
     *
     * @return \Cake\Http\Response|null
     */
    public function delete($slug): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }

        return null;
    }

    /**
     * Finds all articles that are tagged by the given parameter values
     *
     * @param string[] $tags
     */
    public function tags(...$tags): void
    {
        // The 'pass' key is provided by CakePHP and contains all
        // the passed URL path segments in the request.
        // $tags = $this->request->getParam('pass');

        // Use the ArticlesTable to find tagged articles.
        $articles = $this->Articles->find('tagged', [
            'tags' => $tags
        ]);

        // set the view context
        $this->set(compact('articles', 'tags'));
    }
}