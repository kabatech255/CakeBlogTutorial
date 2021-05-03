<h1><?= $article->title ?? ''; ?></h1>
<?php
echo $this->Form->create($article);
echo $this->Form->input('user_id', ['type' => 'hidden', 'value' => $this->Auth->user('id')]);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => '3']);
echo $this->Form->select('categories._ids', $categories, [
  'multiple' => 'checkbox',
]);
echo $this->Form->button(__('Edit Article'));
echo $this->Form->end();
?>
