<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->meta('description', $description) ?>
  <!-- Font Awesome -->
  <?= $this->Html->meta([
        'link' => 'https://use.fontawesome.com/releases/v5.15.2/css/all.css',
        'rel' => 'stylesheet'
      ]);
  ?>
  <!-- Google Fonts Roboto -->
  <?= $this->Html->meta([
    'link' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap',
    'rel' => 'stylesheet'
  ]);
  ?>
  <!-- MDB -->
  <?= $this->Html->css('mdb/mdb.min.css') ?>
  <?= $this->Html->css('custom.css') ?>
  <!-- CSS -->
  <?= $this->Html->css('base.css') ?>
  <?= $this->Html->css('style.css') ?>
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <?= $this->Html->script('app.js') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
</head>
