$(function () {
  // 記事の削除ボタン
  $('.article-del-icon').on('click', function() {
    const articleId = $(this).data('article-id');
    $(`#article_del_${articleId}`).click()
  });
});
