<h1 class="mb-5 mt-2">Blog articles</h1>

<div>
  <?php if (!empty($tag ?? '')): ?>
    <section>
      <p>
        <span style="vertical-align: text-bottom;">タグ名「<?= $tag->name ?>」で絞り込み中</span>
        <?= $this->Html->link(
          '絞り込みをクリア',
          [ 'action' => 'index' ],
          [ 'class' => 'btn btn-outline-warning' ]
        ); ?>
      </p>
    </section>
  <?php endif; ?>
  <?php if(!empty($this->Auth->user('username') ?? '')): ?>
  <section>
    <?= $this->Html->link(
      '追加',
      ['action' => 'add'],
      ['class' => 'btn btn-primary m-2']
    ); ?>
  </section>
  <?php endif; ?>
  <section>
    <ul class="d-flex flex-wrap p-0 mb-3">
      <?php foreach ($articles as $article): ?>
        <li class="col-12 col-sm-6 col-md-4 col-lg-3 p-2">
          <div class="card">
            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
              <img
                src="https://mdbootstrap.com/img/new/standard/nature/184.jpg"
                class="card-img-top"
                alt="..."
              />
              <a href="/articles/show/<?= $article->id; ?>">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15)"></div>
              </a>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= $article->title;?></h5>
              <p class="card-text">
                <?= nl2br($article->body); ?>
              </p>
              <!-- カテゴリータグ -->
              <ul class="d-flex justify-content-start" style="height: 32px;">
                <?php foreach($article->tags as $tag): ?>
                  <li class="p-1">
                    <?= $this->Html->link(
                      $tag->name,
                      [ 'action' => 'index',
                        '?' => [
                          'tag' => $tag->id
                        ],
                      ],
                      [ 'class' => 'badge rounded-pill bg-warning']
                    );
                    ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"></li>
              <li class="list-group-item">
                <ul class="d-flex justify-content-between align-items-center">
                  <li>
                    <?= $this->Html->link($article->author->username, ['controller' => 'users', 'action' => 'show', $article->author->id]); ?>
                  </li>
                  <li class="d-flex">
                    <div class="p-1">
                      <?= $this->Html->link(
                        '編集',
                        ['action' => 'edit', $article->id],
                        ['class' => 'btn btn-sm btn-success'],
                    ); ?>
                    </div>
                    <div class="p-1">
                      <?= $this->Form->postLink('削除',
                        ['action' => 'delete', $article->id],
                        ['confirm' => '一度削除すると元に戻せません。削除してよろしいですか?', 'class' => 'btn btn-sm btn-danger']
                      )
                      ?>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
</div>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first(' << first ') ?>
        <?= $this->Paginator->prev(' < prev ') ?>
        <?= $this->Paginator->next(' next > ') ?>
        <?= $this->Paginator->last(' last >>') ?>
    </ul>
</div>


<?php $this->start('pageScript'); ?>
<script>
  $(function () {
    console.log($('footer'))
  })
</script>
<?php $this->end(); ?>
