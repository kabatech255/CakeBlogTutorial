<h1>Blog articles <button><?= $this->Html->link('Add', ['action' => 'add']) ?></button></h1>
<table>
  <tr>
    <th>Id</th>
    <th>Title</th>
    <th>Created</th>
    <th>編集</th>
    <th>削除</th>
  </tr>

  <!-- ここから、$articles のクエリーオブジェクトをループして、投稿記事の情報を表示 -->

  <?php foreach ($articles as $article): ?>
    <tr>
      <td><?= $article->id ?></td>
      <td>
        <?= $this->Html->link($article->title, ['action' => 'show', $article->id]) ?>
      </td>
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
