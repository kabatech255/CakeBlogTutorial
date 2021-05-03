<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Follows Model
 *
 * @property \App\Model\Table\FollowsTable&\Cake\ORM\Association\BelongsTo $Follows
 * @property \App\Model\Table\FollowsTable&\Cake\ORM\Association\BelongsTo $Followers
 *
 * @method \App\Model\Entity\Follow get($primaryKey, $options = [])
 * @method \App\Model\Entity\Follow newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Follow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Follow|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Follow saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Follow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Follow[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Follow findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FollowsTable extends Table
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

    $this->setTable('follows');
    $this->setDisplayField('id');
    $this->setPrimaryKey('id');

    $this->addBehavior('Timestamp');

    $this->belongsTo('Follows', [
      'foreignKey' => 'follow_id',
      'joinTable' => 'users',
      'joinType' => 'INNER',
    ])->setProperty('follow');

    $this->belongsTo('Followers', [
      'className' => 'Users',
      'foreignKey' => 'follower_id',
      'joinTable' => 'users',
      'joinType' => 'INNER',
    ])->setProperty('follower');
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
    $rules->add($rules->existsIn(['follow_id'], 'Users'));
    $rules->add($rules->existsIn(['follower_id'], 'Users'));

    return $rules;
  }
}
