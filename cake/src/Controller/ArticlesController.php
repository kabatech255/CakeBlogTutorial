<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
  public function isAuthorized($user)
  {
    if ($this->request->getParam('action') === 'add') {
      return true;
    }

    if (in_array($this->request->getParam('action'), ['edit', 'delete'])) {
      $articleId = (int)$this->request->getParam('pass')[0];
      if ($this->Articles->isOwnedBy($articleId, $user['id'])) {
        return true;
      }
    }
    return parent::isAuthorized($user);
  }

  /**
   * Index method
   *
   * @return \Cake\Http\Response|null
   */
  public function index()
  {
    $this->paginate = [
      'contain' => ['Categories', 'Users'],
    ];
    if($categoryId = (int)$this->request->getQuery('category')){
      // クエリパラメータ"category"がある場合
      $query = $this->Articles->find('category', [
        'categoryId' => $categoryId
      ]);
      $articles = $this->paginate($query);
      $category = $this->loadModel('Categories')->get($categoryId);
      $this->set(compact('category'));
    } else {
      // クエリパラメータ"category"がない場合
      $articles = $this->paginate($this->Articles);
    }

    $this->set(compact('articles'));
  }

  /**
   * Show method
   *
   * @param string|null $id Article id.
   * @return \Cake\Http\Response|null
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function show($id = null)
  {
//    $dsn = 'mysql://admin:admin@db_master/cake_blog';
//    ConnectionManager::config('default', ['url' => $dsn]);
    $connection = ConnectionManager::get('default');
    $article = $this->Articles->get($id, [
      'contain' => ['Comments']
    ]);
    $comment = $this->Articles->Comments->newEntity();
    $this->set(compact(['connection', 'article', 'comment']));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $article = $this->Articles->newEntity();
    if ($this->request->is('post')) {
      $data = $this->request->getData();
      $article = $this->Articles->patchEntity($article, $data);
      // ↑の1行は、以下のようにも書ける
      //$newData = ['user_id' => $this->Auth->user('id')];
      //$article = $this->Articles->patchEntity($article, $newData);
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('The article has been saved.'));
        return $this->redirect(['action' => 'index']);
      } else {
        $this->Flash->error(__('The article could not be saved. Please, try again.'));
      }
    }
    $article = $this->Articles->newEntity();
    $categories = $this->Articles->Categories->find('treeList');
    $this->set(compact(['article', 'categories']));
  }

  /**
   * Edit method
   *
   * @param string|null $id Article id.
   * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $article = $this->Articles->get($id, [
      'contain' => ['Categories'],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $article = $this->Articles->patchEntity($article, $this->request->getData());
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('The article has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The article could not be saved. Please, try again.'));
    }
    $categories = $this->Articles->Categories->find('treeList');
    $this->set(compact(['article', 'categories']));
  }

  /**
   * Delete method
   *
   * @param string|null $id Article id.
   * @return \Cake\Http\Response|null Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $article = $this->Articles->get($id);
    if ($this->Articles->delete($article)) {
      $this->Flash->success(__('The article has been deleted.'));
    } else {
      $this->Flash->error(__('The article could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }
}
