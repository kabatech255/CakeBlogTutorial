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
