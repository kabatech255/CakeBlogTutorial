<h1 class="mt-5 mb-2">ブログサイト</h1>

<?= $this->element('articles-layout', [
  'articles' => $articles,
  'tag' => $tag ?? '',
]); ?>

<?php $this->start('pageScript'); ?>
<?= $this->fetch('articleScript') ?>
<?php $this->end(); ?>
