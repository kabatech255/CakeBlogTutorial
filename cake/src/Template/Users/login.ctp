<div class="form my-4">
  <?= $this->Flash->render() ?>
  <fieldset class="card p-5">
    <?= $this->Form->create(); ?>
    <legend class="mb-5"><?= __('ログイン') ?></legend>
    <div class="form-outline input text mb-5">
      <input type="text" name="username" id="username" value="<?= $this->request->getData('username') ?? ''; ?>" class="form-control" />
      <label class="form-label" for="username">ログインID</label>
    </div>
    <div class="form-outline input text mb-5">
      <input type="password" name="password" id="password" value="<?= $this->request->getData('password') ?? ''; ?>" class="form-control" />
      <label class="form-label" for="password">パスワード</label>
    </div>
    <div class="text-center">
      <?= $this->Form->button(__('ログイン'), [
        'class' => 'btn btn-primary btn-rounded'
      ]); ?>
    </div>
    <?= $this->Form->end(); ?>
  </fieldset>
</div>
