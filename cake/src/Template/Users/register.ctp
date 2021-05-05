<div class="form my-4">
  <?= $this->Flash->render() ?>
  <fieldset class="card p-5">
    <?= $this->Form->create(); ?>
    <legend class="mb-5"><?= __('新規登録') ?></legend>
    <div class="form-outline mb-5">
      <input type="text" name="username" id="username" value="<?= $this->request->getData('username') ?? ''; ?>" class="form-control" />
      <label class="form-label" for="username">ログインID</label>
    </div>
    <div class="form-outline mb-5">
      <input type="password" name="password" id="password" value="<?= $this->request->getData('password') ?? ''; ?>" class="form-control" />
      <label class="form-label" for="password">パスワード</label>
    </div>
    <div class="col-12 mb-5">
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
