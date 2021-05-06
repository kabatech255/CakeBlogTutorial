<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
  public function isAuthorized($user)
  {
    if (in_array($this->request->getParam('action'), ['edit', 'follow', 'unfollow'])) {
      return true;
    }

    return parent::isAuthorized($user);
  }

  public function beforeFilter(Event $event)
  {
    parent::beforeFilter($event);
    $this->Auth->allow(['register', 'logout']);
  }

  public function initialize()
  {
    parent::initialize();
    $this->paginate = [
      'limit' => 5,
      'order' => [
        'id' => 'DESC'
      ],
    ];
  }

  public function login()
  {
    if ($this->request->is('post')) {
      $user = $this->Auth->identify();
      if ($user) {
        $this->Auth->setUser($user);
        return $this->redirect($this->Auth->redirectUrl());
      }
      $this->Flash->error(__('ログインIDまたはパスワードが一致しませんでした'));
    }
  }

  public function logout()
  {
    return $this->redirect($this->Auth->logout());
  }

  /**
   * Register method
   *
   * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
   */
  public function register()
  {
    $user = $this->Users->newEntity();
    if ($this->request->is('post')) {
      $user = $this->Users->patchEntity($user, $this->request->getData());
      if ($this->Users->save($user)) {
        $this->Flash->success(__('ユーザーが保存されました。'));
        return $this->redirect(['controller' => 'articles', 'action' => 'index']);
      }
      $this->Flash->error(__('ユーザーの保存に失敗しました。 お手数ですが、再度お試しください。'));
    }
    $this->set(compact('user'));
  }

  /**
   * Index method
   *
   * @return \Cake\Http\Response|null
   */
  public function index()
  {
    $users = $this->paginate($this->Users);

    $this->set(compact('users'));
  }

  /**
   * Show method
   *
   * @param string|null $id User id.
   * @return \Cake\Http\Response|null
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function show($id = null)
  {
    $user = $this->Users->get($id, [
      'contain' => ['FollowMembers', 'FollowerMembers'],
    ]);
    $articleTable = $this->loadModel('Articles')->find('author', [
      'userId' => $user->id
    ]);
    $c = new Collection($user->followers);
    $followerIds = $c->extract('id')->toList();

    // クエリパラメータ"tag"がある場合
    if($tagId = (int)$this->request->getQuery('tag')){
      $query = $articleTable->find('tag', [
        'tagId' => $tagId
      ]);
      $articles = $this->paginate($query, [
        'contain' => ['Tags', 'Authors'],
      ]);
      $tag = $this->loadModel('Tags')->get($tagId);
      $this->set(compact('tag'));
    } else {
      // クエリパラメータ"tag"がない場合
      $articles = $this->paginate($articleTable,  [
        'contain' => ['Tags', 'Authors'],
      ]);
    }
    $this->set(compact(['user', 'followerIds', 'articles']));
  }


  /**
   * Edit method
   *
   * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit()
  {
    $user = $this->Users->get($this->Auth->user('id'), [
      'contain' => [],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->getData();
      $fileSource = $data['file_name']['tmp_name'];

      if (empty($fileSource)) {
        $this->Flash->success(__('The user has been saved.'));
        return $this->redirect(['action' => 'edit']);
      }
      $saveDir = "users/{$this->Auth->user('id')}";
      $data['file_name'] = $this->getSavePath($data['file_name']['name']);
      $deletePath = $this->Auth->user('file_name') !== null ? $this->Auth->user('file_name') : '';
      $user = $this->Users->patchEntity($user, $data);
      if ($this->Users->save($user)) {
        $this->Auth->setUser($user);
        $this->putTmpFile($fileSource, $saveDir, $data['file_name']);
        $s3Controller = new S3Controller();
        $s3Controller->upload($this->Auth->user('file_name'));
        $this->deleteTmpFile(STORAGE_PATH, $this->Auth->user('file_name'));
        $result = !empty($deletePath) ? $s3Controller->delete($deletePath) : '';
        $this->Flash->success(__('The user has been saved.'));
        return $this->redirect(['action' => 'edit']);
      }
      $this->Flash->error(__('The user could not be saved. Please, try again.'));
    }
    $this->set(compact('user'));
  }

  /**
   * Follow method
   * @param null $id
   */
  public function following($id = null)
  {
    $associates = [
      [
        'label' => 'フォロー',
        'value' => 'follows',
      ],
      [
        'label' => 'フォロワー',
        'value' => 'followers',
      ],
    ];
    $prevIndex = ($this->request->getQuery('relation') ?? 'follows') === 'follows' ? 0 : 1;
    $prev = array_splice($associates, $prevIndex, 1);
    $associates = array_merge($prev, $associates);
    $user = $this->Users->get($id, [
      'contain' => ['FollowMembers', 'FollowerMembers'],
    ]);
    $currentAssociate = $associates[0]['value'];
    $rows = $user->$currentAssociate;
    $this->set(compact(['rows', 'user', 'associates']));
  }

  /**
   * Delete method
   *
   * @param string|null $id User id.
   * @return \Cake\Http\Response|null Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $user = $this->Users->get($id);
    if ($this->Users->delete($user)) {
      $this->Flash->success(__('The user has been deleted.'));
    } else {
      $this->Flash->error(__('The user could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function follow($id = null)
  {
    $user = $this->Users->get($id, [
      'contain' => ['FollowMembers', 'FollowerMembers'],
    ]);

    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->getData();
      $data['followers']['_ids'] = empty($data['followers']['_ids']) ? [$this->Auth->user('id')] : array_merge($data['followers']['_ids'], [$this->Auth->user('id')]);

      $user = $this->Users->patchEntity($user, $data, [
        'associated' => ['FollowerMembers'],
      ]);
//      dd($user);
      if ($this->Users->save($user)) {
        $this->Flash->success(__('フォローしました。'));
      } else {
        $this->Flash->error(__('フォローに失敗しました. 恐れ入りますが、再度お試しください'));
      }
    }

    return $this->redirect(['action' => 'show', $user->id]);
  }

  public function unfollow($id = null)
  {
    $user = $this->Users->get($id, [
      'contain' => ['FollowMembers', 'FollowerMembers'],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $data = $this->request->getData();
      $index = array_search($this->Auth->user('id'), $data['followers']['_ids']);
      array_splice($data['followers']['_ids'], $index, 1);

      $user = $this->Users->patchEntity($user, $data, [
        'associated' => ['FollowerMembers'],
      ]);
      if ($this->Users->save($user)) {
        $this->Flash->success(__('フォローを解除しました'));
      } else {
        $this->Flash->error(__('フォローの解除に失敗しました. 恐れ入りますが、再度お試しください'));
      }
      return $this->redirect(['action' => 'show', $user->id]);
    }
  }

  /**
   * @param string $originalName
   * @return string
   */
  protected function getSavePath(string $originalName)
  {
    $fileName = "user_thumbnail_{$this->Auth->user('id')}_{$this->getRandomName()}";
    $extension = $this->getExtension($originalName);
    $fileName .= $extension;
    $saveDir = "users/{$this->Auth->user('id')}";
    return $saveDir . DS . $fileName;
  }

  protected function getExtension(string $filePath)
  {
    return mb_substr($filePath, mb_strrpos($filePath, '.'));
  }

  protected function getRandomName($length = 12)
  {
    $characters = array_merge(
      range(0, 9),
      range('a', 'z'),
      range('A', 'Z')
    );

    return substr(str_shuffle(implode('', $characters)), 0, $length);
  }

  /**
   * @param $fileSource
   * @param $saveDir
   * @param $fileName
   */
  protected function putTmpFile($fileSource, $saveDir, $fileName)
  {
    $dir = new Folder(STORAGE_PATH);
    $dir->create($saveDir);
    $savePath = $dir->path . DS . $fileName;
    move_uploaded_file($fileSource, $savePath);
  }

  /**
   * @param string $tmpDir
   * @param string $deletePath
   */
  protected function deleteTmpFile(string $tmpDir, string $deletePath)
  {
    $f = new File($tmpDir . DS . $deletePath);
    $f->delete($tmpDir . DS . $deletePath);
    $f->close();
  }
}
