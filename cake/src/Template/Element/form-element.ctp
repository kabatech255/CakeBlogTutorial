<div class="form my-5">
  <fieldset class="card p-5">
    <?= $this->Form->create($article); ?>
    <?= $this->Form->input('user_id', ['type' => 'hidden', 'value' => $this->Auth->user('id')]); ?>
    <legend class="mb-5"><?= __($formTitle ?? '投稿フォーム') ?></legend>
    <ul>
      <?php if ($this->Form->isFieldError('title')): ?>
        <li><?= $this->Form->error('title'); ?></li>
      <?php endif;?>
      <?php if ($this->Form->isFieldError('body')): ?>
        <li><?= $this->Form->error('body'); ?></li>
      <?php endif;?>
      <?php if ($this->Form->isFieldError('tags')): ?>
        <li><?= $this->Form->error('tags'); ?></li>
      <?php endif;?>
    </ul>
    <div class="form-outline input text mb-5">
      <input type="text" name="title" id="title" value="<?= $this->request->getData('title') ?? ($article->title ?? ''); ?>" class="form-control" />
      <label class="form-label" for="title">タイトル</label>
    </div>
    <div class="form-outline mb-5">
      <textarea class="form-control" name="body" id="body" rows="10"><?= nl2br($this->request->getData('body') ?? ($article->body ?? '')); ?></textarea>
      <label class="form-label" for="body">本文</label>
    </div>
    <div class="form-outline mb-5">
      <input type="text" name="tags" id="tags" value="<?= $this->request->getData('tags') ?? ($tags ?? ''); ?>" class="form-control" placeholder="例）#PHP #JavaScript #M1チップ" />
      <label class="form-label" for="tags">タグ</label>
    </div>
    <div class="text-center">
      <?= $this->Form->button(__($formBtnText ?? 'post'), [
        'class' => 'btn btn-primary btn-rounded'
      ]); ?>
    </div>
    <?= $this->Form->end(); ?>
  </fieldset>
</div>
