<div class="form my-4">
  <?= $this->Flash->render() ?>
  <fieldset class="card p-5">
    <?= $this->Form->create(); ?>
    <legend class="mb-4"><?= __('新規登録') ?></legend>
    <div class="form-outline mb-4">
      <input type="text" name="username" id="username" value="<?= $this->request->getData('username') ?? ''; ?>" class="form-control" />
      <label class="form-label" for="username">ログインID</label>
    </div>
    <div class="form-outline mb-4">
      <input type="password" name="password" id="password" value="<?= $this->request->getData('password') ?? ''; ?>" class="form-control" />
      <label class="form-label" for="password">パスワード</label>
    </div>
    <div class="col-12 mb-4">
      <label class="visually-hidden" for="inlineFormSelectPref">権限</label>
      <select name="role" class="form-select">
        <option value="admin">管理者</option>
        <option value="author">一般</option>
      </select>
    </div>
    <div class="text-center">
      <?= $this->Form->button(__('登録'), [
        'class' => 'btn btn-primary btn-rounded'
      ]); ?>
    </div>
    <?= $this->Form->end(); ?>
  </fieldset>
</div>



<h1>新規登録</h1>
<div class="users form">
  <?= $this->Form->create($user); ?>
  <fieldset>
    <legend><?= __('Add User'); ?></legend>
    <?= $this->Form->control('username') ?>
    <?= $this->Form->control('password') ?>
    <?= $this->Form->control('role', [
      'options' => ['admin' => 'Admin', 'author' => 'Author']
    ]); ?>
  </fieldset>
  <?= $this->Form->button(__('新規登録')); ?>
  <?= $this->Form->end(); ?>
</div>
