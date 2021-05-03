<h1><?= $user->username ?? ''; ?></h1>
<?php
echo $this->Form->create($user, ['enctype' => 'multipart/form-data']);
echo $this->Form->file('file_name', [
  'accept' => 'image/jpeg,image/png,image/gif,image/svg+xml'
]);
//echo $this->Form->control('body', ['rows' => '3']);
echo $this->Form->button(__('保存'));
echo $this->Form->end();
?>

<img src="<?= "/img/app/{$user->file_name}"; ?>" alt="<?= "face_for_{$user->username}"; ?>">
