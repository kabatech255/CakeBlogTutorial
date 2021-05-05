<div class="user-detail-box p-0 p-md-4 mt-5">
  <section class="d-flex align-items-center">
    <div class="flex-grow-1 d-flex flex-column flex-md-row align-items-md-center"><!-- ユーザーアイコン -->
      <div class="flex-shrink-0">
        <span class="user-chip-icon --lg" style="background-image: url(<?= 'https://asset.risk-exam.site/tmp/' . ($user->file_name ?? ''); ?>)"></span>
      </div>
      <div class="flex-grow-1 py-2 px-md-3"><!-- ユーザー名、フォロー数、フォロワー数 -->
        <h1 class="fs-3 fw-bold"><?= $user->username; ?></h1>
        <p>
        <span>
          <a
            href="<?= "/users/following/{$user->id}"; ?>"
            class="text-muted"
            data-mdb-toggle="tooltip"
            title="フォロー一覧を見る"
          >
            <strong><?= count($user->follows); ?></strong>
          </a>
          <small>フォロー</small>
        </span>
          <span>
          <a
            href="<?= "/users/following/{$user->id}?relation=followers"; ?>"
            class="text-muted"
            data-mdb-toggle="tooltip"
            title="フォロワー一覧を見る"
          >
            <strong><?= count($user->followers); ?></strong>
          </a>
          <small>フォロワー</small>
        </span>
        </p>
      </div>
    </div>
    <div class="flex-shrink-0"><!-- フォローボタン -->
      <?php if($this->Auth->user('id') !== $user->id): ?>
      <?= $this->element('follow-button', [
        'followerIds' => $followerIds,
        'id' => $user->id,
      ]);
      ?>
      <?php endif; ?>
    </div>
  </section>
  <section class="mt-5">
    <!--  記事一覧  -->
    <?= $this->element('articles-layout', [
      'articles' => $articles,
      'tag' => $tag ?? '',
      'controllerName' => 'users',
      'actionName' => 'show',
      'requestPass' => $user->id,
      'colLg' => 4
    ]); ?>
  </section>
  <section class="mt-5 text-center">
    <a class="btn btn-dark btn-rounded" onclick="history.back()">BACK</a>
  </section>
</div>
<?php $this->start('pageScript'); ?>
<?= $this->fetch('followScript'); ?>
<?= $this->fetch('articleScript') ?>
<?php $this->end(); ?>
