<nav class="top-bar expanded" data-topbar role="navigation">
  <ul class="title-area large-3 medium-4 columns">
    <li class="name">
      <h1><a href=""><?= $this->fetch('title') ?></a></h1>
    </li>
  </ul>
  <div class="top-bar-section">
    <ul class="right">
      <li><a target="_blank" href="https://book.cakephp.org/3/">Documentation</a></li>
      <li><a target="_blank" href="https://api.cakephp.org/3.0/">API</a></li>
      <?php if (!empty($this->Auth->user('username') ?? '')): ?>
        <li><?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout']); ?></li>
        <li><a><?= $this->Auth->user('username'); ?></a></li>
      <?php else: ?>
        <li><?= $this->Html->link('ログイン', ['controller' => 'users', 'action' => 'login']); ?></li>
        <li><?= $this->Html->link('新規登録', ['controller' => 'users', 'action' => 'register']); ?></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<?= $this->Flash->render() ?>
