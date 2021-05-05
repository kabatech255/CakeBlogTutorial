<?php
$isFollowed = in_array($this->Auth->user('id'), $followerIds);
$action = $isFollowed ? 'unfollow' : 'follow';
$string = $isFollowed ? 'フォロー解除' : 'フォロー';
$followBtnClass = $isFollowed ? 'btn-primary' : 'btn-outline-primary';

echo $this->Form->postLink($string,
  ['action' => $action, $id],
  [
    'style' => 'display: none;',
    'id' => 'follow_link_' . $id,
    'data' => [
      'followers._ids' => $followerIds
    ]
  ]
);
?>

<button class="btn btn-sm follow-btn <?= $followBtnClass; ?>" data-id="<?= $id; ?>" data-mdb-ripple-color="<?= $isFollowed ? 'light' : 'primary' ?>">
  <i class="fas fa-user-plus"></i>
  <span><?= $string; ?></span>
</button>

<?php $this->start('followScript');?>
<script>
  $(function () {
    $('.follow-btn').on('click', function() {
      const id = $(this).data('id');
      $(`#follow_link_${id}`).click();
    });
  });
</script>
<?php $this->end();?>
