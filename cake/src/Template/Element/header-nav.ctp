<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container-fluid">
    <button
      class="navbar-toggler"
      type="button"
      data-mdb-toggle="collapse"
      data-mdb-target="#navbarExample01"
      aria-controls="navbarExample01"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarExample01">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item active">
          <a class="nav-link" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="https://book.cakephp.org/3/">Documentation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" target="_blank" href="https://api.cakephp.org/3.0/">API</a>
        </li>
        <?php if (!empty($this->Auth->user('username') ?? '')): ?>
          <li class="nav-item">
            <?= $this->Html->link(
              $this->Auth->user('username'),
              ['controller' => 'users', 'action' => 'show', $this->Auth->user('id')],
              ['class' => 'nav-link']
            );
            ?>
          </li>
          <li class="nav-item">
            <?= $this->Html->link(
              'ログアウト',
              ['controller' => 'users', 'action' => 'logout'],
              ['class' => 'nav-link']
            ); ?>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <?= $this->Html->link(
              'ログイン',
              ['controller' => 'users', 'action' => 'login'],
              ['class' => 'nav-link']
            ); ?>
          </li>
          <li class="nav-item">
            <?= $this->Html->link(
              '新規登録',
              ['controller' => 'users', 'action' => 'register'],
              ['class' => 'nav-link']
            ); ?>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div style="margin-top: 58px;"></div>
<!-- Navbar -->
<?= $this->Flash->render() ?>
