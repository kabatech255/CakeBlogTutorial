<div class="user-chip">
  <span class="user-chip-icon" style="background-image: url(<?= 'https://asset.risk-exam.site/tmp/' . ($file_name ?? ''); ?>)"></span>
  <?= $this->Html->link(
    $name . 'さん',
    ['controller' => 'users', 'action' => 'show', $id],
    ['class' => 'user-chip-link ms-2']
  ); ?>
</div>
