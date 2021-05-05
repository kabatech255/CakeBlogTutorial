<div class="user-following-box p-0 p-md-4 mt-5">
  <figure class="figure w-100">
    <h1 class="fs-5 m-0 p-2"><?= $user->username; ?>さんの<?= $associates[0]['label'] ?? 'フォロー' ?>一覧</h1>
    <figcaption class="figure-caption text-end">
      <small>
        <?= $this->Html->link(
          "{$associates[1]['label']}一覧に切り替える",
          [
            'action' => 'following',
            $user->id,
            '?' => [
              'relation' => $associates[1]['value']
            ],
          ],
          ['class' => 'link-info']
        ); ?>
      </small>
    </figcaption>
  </figure>
  <table class="table table-striped mt-3">
    <tbody>
    <?php foreach($rows as $row): ?>
    <tr>
      <td><?= $this->element('user-chip', [
        'id' => $row->id,
        'name' => $row->username,
        'file_name' => $row->file_name,
        ]); ?>
      </td>
      <td></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
