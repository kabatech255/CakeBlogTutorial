<div class="form mt-5">
  <fieldset class="card p-5">
    <h1 class="mb-5 text-center"><?= $user->username ?? ''; ?></h1>
    <?= $this->Form->create($user, ['enctype' => 'multipart/form-data']); ?>
    <?= $this->Form->input('user_id', ['type' => 'hidden', 'value' => $this->Auth->user('id')]); ?>
    <legend class="mb-5"><?= __($formTitle ?? 'プロフィール画像') ?></legend>
    <div class="mb-5">
      <label for="form_file_sm" class="form-label"><small>画像サイズは5MBまで</small></label>
      <?= $this->Form->file('file_name', [
        'accept' => 'image/jpeg,image/png,image/gif,image/svg+xml',
        'class' => 'form-control',
        'id' => 'form_file_sm',
      ]);
      ?>
    </div>
    <div class="mb-5">
      <figure class="figure" style="max-width: 300px;">
        <figcaption class="figure-caption mb-3">
          <small>現在のプロフィール画像</small>
        </figcaption>
        <img
          src="<?= sprintf('%s/%s', 'https://asset.risk-exam.site/tmp', $user->file_name); ?>"
          class="figure-img img-fluid rounded shadow-3"
          alt="<?= "face_for_{$user->username}"; ?>"
        />
      </figure>
    </div>
    <div class="text-center">
      <?=
      $this->Form->button(__('保存'), [
        'class' => 'btn btn-primary btn-rounded'
      ]);
      ?>
    </div>
    <?= $this->Form->end(); ?>
  </fieldset>
</div>
