<div class="article-detail-box card p-4 mt-5">
  <!-- サムネイル -->
  <section class="bg-image">
    <figure class="figure">
      <img
        src="https://mdbootstrap.com/img/new/standard/nature/184.jpg"
        class="figure-img img-fluid rounded shadow-3 mb-3"
        alt="..."
      />
      <figcaption class="figure-caption text-end">
        <time><?= $article->created->format('Y年n月j日 H:i'); ?></time>
      </figcaption>
    </figure>
  </section>
  <!-- ヘッドライン -->
  <section class="mt-5">
    <!-- タイトル -->
    <h1 class="fs-3 fw-bold">
      <span><?= $article->title; ?></span>
    </h1>
    <!-- 投稿者／いいね-->
    <div class="d-flex flex-wrap align-items-center py-1 mt-3">
      <!-- 投稿者 -->
      <div class="">
        <?= $this->element('user-chip', [
          'id' => $article->author->id,
          'name' => $article->author->username,
          'file_name' => $article->author->file_name,
        ]); ?>
      </div>
      <!-- いいね -->
      <div class="ms-4">
        <button class="like-btn">
          <?php
          $action = in_array($this->Auth->user('id'), $likeIds) ? 'dislike' : 'like';
          $m = $action === 'dislike' ? 'fas' : 'far';
          echo '<i class="' . $m . ' fa-heart"></i>';
          echo $this->Form->postLink("いいね",
            ['action' => $action, $article->id],
            [
              'class' => 'like-link',
              'style' => 'display: none',
              'data' => [
                'likes._ids' => $likeIds
              ]
            ]
          )
          ?>
          <strong><?= count($article->likes); ?></strong>
        </button>
      </div>
    </div>
    <!-- タグ -->
    <ul class="d-flex justify-content-start flex-wrap mt-4">
      <?php foreach($article->tags as $tag): ?>
        <li class="p-1">
          <?= $this->Html->link(
            $tag->name,
            [ 'action' => 'index',
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
  </section>
  <!-- 記事本文 -->
  <section class="mt-5">
    <p class="article-detail-box-body"><?= nl2br($article->body); ?></p>
  </section>
</div><!-- .card -->

<div class="border-top mt-5"></div>

<div class="article-detail-box">
  <!-- コメント -->
  <section class="mt-5">
    <h3 class="mt-3">
      <fieldset class="m-0 p-0">
        <legend><?= __($formTitle ?? 'コメント投稿フォーム') ?></legend>
      </fieldset>
    </h3>
    <div class="mt-4">
      <!-- コメントフォーム -->
      <?php
      echo $this->Form->create('Comment', ['url' => ['controller' => 'comments', 'action' => 'add']]);
      echo $this->Form->input('article_id', ['type' => 'hidden', 'value' => $article->id]);
      ?>
      <div class="form-outline input text">
        <input type="text" name="body" id="body" value="<?= $this->request->getData('body') ?? ''; ?>" class="form-control" />
        <label class="form-label" for="body">コメント</label>
      </div>
      <div class="mt-3">
        <?php
        echo $this->Form->button(__('コメントを保存する'), [
          'class' => 'btn btn-sm btn-primary btn-rounded'
        ]);
        echo $this->Form->end();
        ?>
      </div>
    </div>
    <!-- コメント一覧 -->
    <div class="mt-5">
      <h3 class="mb-2 fs-5">
        コメント一覧
      </h3>
      <?php if( count($article->comments) > 0 ): ?>
        <ul class="">
          <?php foreach($article->comments as $comment): ?>
            <li>
              <div class="card p-3">
                <div class="fw-light">
                  <p><?= $comment->body; ?></p>
                  <div class="d-flex justify-content-end align-items-center">
                    <?= $this->element('user-chip', [
                      'id' => $comment->commentedBy->id,
                      'name' => $comment->commentedBy->username,
                      'file_name' => $comment->commentedBy->file_name,
                    ]); ?>
                    <span data-comment-id="<?= $comment->id; ?>" class="icon-btn comment-del-icon ripple d-flex justify-content-center align-items-center">
                      <i class="far fa-trash-alt text-black-50"></i>
                    </span>
                  </div>
                </div>
              </div>
            </li>
            <?php
            /**
             * 第1引数: リンクのテキスト
             * 第2引数: $url
             * 第3引数: $options(今回は'confirm'と'data'を配列形式で指定)
             */
            if($this->Auth->user('id') === $comment->commentedBy->id) {
              echo $this->Form->postLink(
                '削除',
                ['controller' => 'comments', 'action' => 'delete', $comment->id],
                [
                  'confirm' => '一度削除すると元に戻せません。削除してよろしいですか?',
                  'style' => 'display: none;',
                  'id' => 'comment_del_' . $comment->id,
                  'data' => [
                    'article_id' => $article->id,
                  ],
                ]
              );
            }
            ?>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="card p-3">コメントはありません。</p>
      <?php endif; ?>
    </div>
  </section>
  <div class="text-center mt-5">
    <a class="btn btn-dark btn-rounded" onclick="history.back()">BACK</a>
  </div>

  <!-- 編集ボタン、削除ボタンをposition: fixedで -->
  <?php if($this->Auth->user('id') === $article->user_id): ?>
  <section class="position-fixed bottom-50p">
    <ul>
      <!-- 編集ボタン -->
      <li>
        <a
          href="/articles/edit/<?= $article->id; ?>"
          class="btn btn-success btn-floating m-1"
          data-mdb-toggle="tooltip"
          title="編集"
        >
          <i class="far fa-edit"></i>
        </a>
      </li>
      <!-- 削除ボタン -->
      <li>
        <button
          class="btn btn-danger btn-floating m-1 article-del-icon"
          data-mdb-toggle="tooltip"
          title="削除"
          data-article-id="<?= $article->id; ?>"
        >
          <i class="far fa-trash-alt"></i>
        </button>
      </li>
      <?= $this->Form->postLink(
        '削除',
        [ 'action' => 'delete', $article->id ],
        [
          'confirm' => '一度削除すると元に戻せません。この記事を削除してよろしいですか?',
          'id' => 'article_del_' . $article->id,
          'style' => 'display: none;',
        ]
      );
      ?>
    </ul>
  </section>
  <?php endif; ?>
</div>

<?php $this->start('pageScript'); ?>
<script>
  $(function () {
    // いいねボタン
    $('.like-btn').on('click', function() {
      $('.like-link').click();
    })
    // コメントの削除ボタン
    $('.comment-del-icon').each(function(index, item) {
      $(item).on('click', function() {
        const commentId = $(this).data('comment-id');
        $(`#comment_del_${commentId}`).click()
      })
    });
    // 記事の削除ボタン
    $('.article-del-icon').on('click', function() {
      const articleId = $(this).data('article-id');
      $(`#article_del_${articleId}`).click()
    });
  })
</script>
<?php $this->end(); ?>
