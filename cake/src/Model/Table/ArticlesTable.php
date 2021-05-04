<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Articles Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{
  /**
   * Initialize method
   *
   * @param array $config The configuration for the Table.
   * @return void
   */
  public function initialize(array $config)
  {
    parent::initialize($config);

    $this->setTable('articles');
    $this->setDisplayField('title');
    $this->setPrimaryKey('id');

    $this->addBehavior('Timestamp');
//
    $this->hasMany('Comments', [
      'foreignKey' => 'article_id',
    ]);
    $this->belongsToMany('Categories', [
      'joinTable' => 'article_categories',
      'foreignKey' => 'article_id',
    ]);

    $this->belongsToMany('Tags', [
      'foreignKey' => 'article_id',
      'targetForeignKey' => 'tag_id',
      'joinTable' => 'articles_tags',
    ])->setProperty('tags');

    $this->belongsToMany('LikeUsers', [
      'className' => 'Users',
      'joinTable' => 'likes',
      'foreignKey' => 'article_id',
      'targetForeignKey' => 'user_id'
    ])->setProperty('likes');

    $this->belongsTo('Authors', [
      'className' => 'Users',
      'joinTable' => 'users',
      'foreignKey' => 'user_id',
    ]);
  }

  /**
   * Default validation rules.
   *
   * @param \Cake\Validation\Validator $validator Validator instance.
   * @return \Cake\Validation\Validator
   */
  public function validationDefault(Validator $validator)
  {
    $validator
      ->integer('id')
      ->allowEmptyString('id', null, 'create');

    $validator
      ->scalar('title')
      ->maxLength('title', 255)
      ->requirePresence('title', 'create')
      ->notEmptyString('title');

    $validator
      ->scalar('body')
      ->requirePresence('body', 'create')
      ->notEmptyString('body');

    return $validator;
  }

  /**
   * Returns a rules checker object that will be used for validating
   * application integrity.
   *
   * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
   * @return \Cake\ORM\RulesChecker
   */
  public function buildRules(RulesChecker $rules)
  {
//        $rules->add($rules->existsIn(['category_id'], 'Categories'));

    return $rules;
  }

  /**
   * @param $articleId
   * @param $userId
   * @return bool
   */
  public function isOwnedBy($articleId, $userId)
  {
    return $this->exists(['id' => $articleId, 'user_id' => $userId]);
  }

  /**
   * @param Query $query
   * @param array $options
   * @return Query
   */
  public function findTag(Query $query, array $options)
  {
    $tagId = $options['tagId'];
    return $this->find()->matching('Tags', function($q) use ($tagId) {
      return $q->where([ 'Tags.id' => $tagId ]);
    });
  }

}
