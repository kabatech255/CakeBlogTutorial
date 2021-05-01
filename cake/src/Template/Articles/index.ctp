<h1>Blog articles <button><?= $this->Html->link('Add', ['action' => 'add']) ?></button></h1>
<?php if (!empty($category ?? '')): ?>
<div>
  <p>
    <span>タグ名「<?= $category->name ?>」で絞り込み中</span>
    <?= $this->Html->link('絞り込みをクリア', [
      'action' => 'index'
    ]); ?>
  </p>
</div>
<?php endif; ?>

<table>
  <tr>
    <th>投稿者</th>
    <th>タイトル</th>
    <th>カテゴリー</th>
    <th>作成日</th>
    <th class="short">編集</th>
    <th class="short">削除</th>
  </tr>

  <!-- ここから、$articles のクエリーオブジェクトをループして、投稿記事の情報を表示 -->
  <?php foreach ($articles as $article): ?>
    <tr>
      <td><?= $article->user->username ?></td>
      <td>
        <?= $this->Html->link($article->title, ['action' => 'show', $article->id]) ?>
      </td>
      <?php if(count($article->categories) > 0): ?>
      <td>
        <?php foreach($article->categories as $category): ?>
          <?= $this->Html->link($category->name, [
              'action' => 'index',
              '?' => [
                'category' => $category->id
              ]
            ]); ?>
        <?php endforeach; ?>
      </td>
      <?php else: ?>
      <td>
      </td>
      <?php endif; ?>
      <td>
        <?= $article->created->format(DATE_RFC850) ?>
      </td>
      <td>
        <?= $this->Html->link('編集', ['action' => 'edit', $article->id]); ?>
      </td>
      <td>
        <!-- Confirm付き削除フォームの書き方 -->
        <?= $this->Form->postLink('削除',
          ['action' => 'delete', $article->id],
          ['confirm' => '一度削除すると元に戻せません。削除してよろしいですか?']
        )
        ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
