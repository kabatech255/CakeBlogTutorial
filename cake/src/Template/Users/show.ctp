<h1>
  <span><?= $user->username; ?></span>
  <!-- ↓ フォロー（フォロー解除）リンク -->
  <?php
    $action = in_array($this->Auth->user('id'), $followerIds) ? 'unfollow' : 'follow';
    $string = in_array($this->Auth->user('id'), $followerIds) ? 'フォロー解除' : 'フォロー';
    echo $this->Form->postLink($string,
      ['action' => $action, $user->id],
      [
        'data' => [
          'followers._ids' => $followerIds
        ]
      ]
    );
  ?>
</h1>

<h2 class="flex">
  <span>フォロー:</span>
  <span><?= count($user->follows); ?></span>
</h2>
<!--フォロー一覧-->
<?php if( count($user->follows) > 0 ): ?>
<ul>
  <?php foreach($user->follows as $follow): ?>
  <li>
    <?= $this->Html->link($follow->username, [ 'action' => 'show', $follow->id ]); ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
<p>フォロー中のユーザーはいません。</p>
<?php endif; ?>

<h2 class="flex">
  <span>フォロワー:</span>
  <span><?= count($user->followers); ?></span>
</h2>
<!--フォロワー一覧-->
<?php if( count($user->followers) > 0 ): ?>
  <ul>
    <?php foreach($user->followers as $follower): ?>
      <li>
        <?= $this->Html->link($follower->username, [ 'action' => 'show', $follower->id ]); ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>フォロワーはいません。</p>
<?php endif; ?>

<a onclick="history.back()">BACK</a>
