<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->meta('description', $description) ?>

  <?= $this->Html->css('base.css') ?>
  <?= $this->Html->css('style.css') ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <?= $this->Html->script('app.js') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
</head>
