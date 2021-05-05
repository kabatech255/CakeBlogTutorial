<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;

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
    if (in_array($this->request->getParam('action'), ['add', 'like', 'dislike'])) {
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

  public function initialize()
  {
    parent::initialize();
    $this->paginate = [
      'limit' => 5,
      'order' => [
        'id' => 'DESC'
      ],
      'contain' => ['Tags', 'LikeUsers', 'Authors'],
    ];
  }

  /**
   * Index method
   *
   * @return \Cake\Http\Response|null
   */
  public function index()
  {
    if($tagId = (int)$this->request->getQuery('tag')){
      // クエリパラメータ"tag"がある場合
      $query = $this->Articles->find('tag', [
        'tagId' => $tagId
      ]);
      $articles = $this->paginate($query);
      $tag = $this->loadModel('Tags')->get($tagId);
      $this->set(compact('tag'));
    } else {
      // クエリパラメータ"tag"がない場合
      $articles = $this->paginate($this->Articles);
    }

    $title = 'ブログ一覧';

    $this->set(compact(['articles', 'title']));
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
      'contain' => ['Comments.Users', 'Tags', 'Authors', 'LikeUsers']
    ]);
    $c = new Collection($article->likes);
    $likeIds = $c->extract('id')->toList();
    $comment = $this->Articles->Comments->newEntity();
    $this->set(compact(['connection', 'article', 'likeIds', 'comment']));
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
      $data['tags'] = empty($data['tags']) ? [] : $this->makeTagArr($data['tags']);
      $article = $this->Articles->patchEntity($article, $data, [
        'associated' => ['Tags']
      ]);
      // ↑の1行は、以下のようにも書ける
      //$newData = ['user_id' => $this->Auth->user('id')];
      //$article = $this->Articles->patchEntity($article, $newData);
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('記事を投稿しました。'));
        return $this->redirect(['action' => 'index']);
      } else {
        $this->Flash->error(__('投稿に失敗しました'));
      }
    }
    $article = $this->Articles->newEntity();
    $this->set(compact(['article']));
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
      'contain' => ['Tags'],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->getData();
      $data['tags'] = empty($data['tags']) ? [] : $this->makeTagArr($data['tags']);
      $article = $this->Articles->patchEntity($article, $data, [
        'associated' => ['Tags']
      ]);
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('記事を更新しました'));

        return $this->redirect(str_replace('/index.php', '/', $this->referer(null, true)));
      }
      $this->Flash->error(__('The article could not be saved. Please, try again.'));
    }
    $c = new Collection($article->tags);
    $tagsArr = $c->extract(function($tag){
      return "#{$tag->name}";
    })->toList();
    $tags = implode(' ', $tagsArr);
    $this->set(compact(['article', 'tags']));
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

    return $this->redirect(str_replace('/index.php', '', $this->referer()));
  }

  public function like($id = null)
  {
    $article = $this->Articles->get($id, [
      'contain' => ['Tags', 'LikeUsers'],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->getData();
      $data['likes']['_ids'] = empty($data['likes']['_ids']) ? [$this->Auth->user('id')] : array_merge($data['likes']['_ids'], [$this->Auth->user('id')]);

      $article = $this->Articles->patchEntity($article, $data, [
        'associated' => ['LikeUsers'],
      ]);
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('いいねをつけました。'));
      } else {
        $this->Flash->error(__('The like could not be saved. Please, try again.'));
      }
      return $this->redirect(['action' => 'show', $article->id]);
    }
  }

  public function dislike($id = null)
  {
    $article = $this->Articles->get($id, [
      'contain' => ['Tags', 'LikeUsers'],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {

      $data = $this->request->getData();
      $index = array_search($this->Auth->user('id'), $data['likes']['_ids']);
      array_splice($data['likes']['_ids'], $index, 1);

      $article = $this->Articles->patchEntity($article, $data, [
        'associated' => ['LikeUsers'],
      ]);
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('いいねを解除しました'));
      } else {
        $this->Flash->error(__('The like could not be saved. Please, try again.'));
      }
      return $this->redirect(['action' => 'show', $article->id]);
    }
  }

  protected function makeTagArr($tagsStr)
  {
    $tags = explode('#', preg_replace('/( |　)/', '', $tagsStr ));
    array_splice($tags, 0, 1);
    $tagsTable = TableRegistry::getTableLocator()->get('Tags');
    $arr = [];
    foreach($tags as $tag) {
      if (!empty($row = $tagsTable->findByName($tag)->first() ?? '')) {
        array_push($arr, ['id' => $row->id]);
      } else {
        array_push($arr, ['name' => $tag]);
      }
    }
    return $arr;
  }
}
