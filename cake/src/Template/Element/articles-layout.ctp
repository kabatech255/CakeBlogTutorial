<div class="articles-layout">
  <?php if (!empty($tag ?? '')): ?>
    <section class="mt-3">
      <div class="card card-bar">
        <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center">
          <span class="card-text me-3">タグ名「<?= $tag->name ?>」で絞り込み中</span>
          <?= $this->Html->link(
            '絞り込みをクリア',
            [ 'controller' => $controllerName ?? 'articles', 'action' => $actionName ?? 'index', $requestPass ?? '' ],
            [ 'class' => 'btn btn-sm btn-outline-warning my-2' ]
          ); ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
  <?php if(!empty($this->Auth->user('username') ?? '')): ?>
    <section class="mt-3 d-flex align-items-center justify-content-between">
      <h2 class="fs-5 fw-light text-muted p-2 m-0">投稿記事一覧</h2>
      <div class="p-2">
        <?= $this->element('add-button'); ?>
      </div>
    </section>
  <?php endif; ?>
  <article>
    <section class="d-flex flex-wrap p-0">
      <?php foreach ($articles as $article): ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-<?= $colLg ?? '3'; ?> p-2">
          <div class="card">
            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
              <img
                src="https://mdbootstrap.com/img/new/standard/nature/184.jpg"
                class="card-img-top"
                alt="..."
              />
              <a href="/articles/show/<?= $article->id; ?>">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15)"></div>
              </a>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= $article->title;?></h5>
              <p class="card-text" style="height: 60px;">
                <?= nl2br($article->body); ?>
              </p>
              <!-- カテゴリータグ -->
              <ul class="d-flex justify-content-start" style="height: 32px;">
                <?php foreach($article->tags as $tag): ?>
                  <li class="p-1">
                    <?= $this->Html->link(
                      $tag->name,
                      [
                        'controller' => $controllerName ?? 'articles',
                        'action' => $actionName ?? 'index',
                        $requestPass ?? '',
                        '?' => [
                          'tag' => $tag->id
                        ],
                      ],
                      [ 'class' => 'badge rounded-pill bg-warning']
                    );
                    ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <ul class="d-flex justify-content-between align-items-center border-top p-3" style="height: 53px;">
              <li>
                <?= $this->element('user-chip', [
                  'id' => $article->author->id,
                  'name' => $article->author->username,
                  'file_name' => $article->author->file_name,
                ]); ?>
                <!--                --><?//= $this->Html->link($article->author->username, ['controller' => 'users', 'action' => 'show', $article->author->id]); ?>
              </li>
              <?php if(!empty($this->Auth->user('username') ?? '') && $this->Auth->user('id') === $article->user_id): ?>
                <li class="d-flex flex-shrink-0">
                  <a
                    href="/articles/edit/<?= $article->id; ?>"
                    class="btn btn-success btn-floating m-1"
                    data-mdb-toggle="tooltip"
                    title="編集"
                  >
                    <i class="far fa-edit"></i>
                  </a>
                  <button
                    class="btn btn-danger btn-floating m-1 article-del-icon"
                    data-mdb-toggle="tooltip"
                    title="削除"
                    data-article-id="<?= $article->id; ?>"
                  >
                    <i class="far fa-trash-alt"></i>
                  </button>
                  <?= $this->Form->postLink(
                    '削除',
                    [ 'controller' => 'articles', 'action' => 'delete', $article->id ],
                    [
                      'confirm' => '一度削除すると元に戻せません。この記事を削除してよろしいですか?',
                      'id' => 'article_del_' . $article->id,
                      'style' => 'display: none;',
                    ]
                  );
                  ?>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </article>
</div>
<div class="paginator mt-5">
  <ul class="pagination justify-content-center">
    <?= $this->Paginator->first(' << first ') ?>
    <?= $this->Paginator->prev(' < prev ') ?>
    <?= $this->Paginator->next(' next > ') ?>
    <?= $this->Paginator->last(' last >>') ?>
  </ul>
</div>

<?php $this->start('articleScript'); ?>
<script>
  $(function () {
    // 記事の削除ボタン
    $('.article-del-icon').on('click', function() {
      const articleId = $(this).data('article-id');
      $(`#article_del_${articleId}`).click()
    });
  })
</script>
<?php $this->end(); ?>
