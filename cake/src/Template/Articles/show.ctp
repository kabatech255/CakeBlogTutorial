<h1>
  <span><?= $article->title; ?></span>
  <small><?= $this->Html->link('編集', ['action' => 'edit', $article->id]); ?></small>
</h1>
<p>投稿者:
  <?= $this->Html->link($article->author->username, [
        'controller' => 'users', 'action' => 'show', $article->author->id
      ]);
  ?>
</p>
<p><?= nl2br($article->body); ?></p>

<p>
<?php
$action = in_array($this->Auth->user('id'), $likeIds) ? 'dislike' : 'like';
echo $this->Form->postLink('いいね',
  ['action' => $action, $article->id],
  [
    'data' => [
      'likes._ids' => $likeIds
    ]
  ]
)
?>の数：<strong><?= count($article->likes); ?></strong>
</p>

<h2>Comments</h2>
<!--コメント一覧-->
<?php if( count($article->comments) > 0 ): ?>
<ul>
  <?php foreach($article->comments as $comment): ?>
  <li>
    <span><?= $comment->body; ?></span>
    <?php
    /**
     * 第1引数: リンクのテキスト
     * 第2引数: $url
     * 第3引数: $options(今回は'confirm'と'data'を配列形式で指定)
     */
    echo $this->Form->postLink(
      '削除',
      ['controller' => 'comments', 'action' => 'delete', $comment->id],
      [
        'confirm' => '一度削除すると元に戻せません。削除してよろしいですか?',
        'data' => [
          'article_id' => $article->id,
        ],
      ]
    );
    ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
<p>コメントはありません。</p>
<?php endif; ?>

<!--コメントフォーム-->
<?php
echo $this->Form->create('Comment', ['url' => ['controller' => 'comments', 'action' => 'add']]);
echo $this->Form->input('article_id', ['type' => 'hidden', 'value' => $article->id]);
echo $this->Form->input('body', ['rows' => '1', 'placeholder' => 'コメント']);
echo $this->Form->button(__('Save Comment'));
echo $this->Form->end();
?>

<a onclick="history.back()">BACK</a>
