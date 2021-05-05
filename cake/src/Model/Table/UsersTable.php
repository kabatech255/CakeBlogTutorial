<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

    $this->setTable('users');
    $this->setDisplayField('id');
    $this->setPrimaryKey('id');

    $this->addBehavior('Timestamp');

    $this->belongsToMany('FollowMembers', [
      'className' => 'Users',
      'joinTable' => 'follows',
      'foreignKey' => 'follow_id',
      'targetForeignKey' => 'follower_id',
    ])->setProperty('follows');

    $this->belongsToMany('FollowerMembers', [
      'className' => 'Users',
      'joinTable' => 'follows',
      'foreignKey' => 'follower_id',
      'targetForeignKey' => 'follow_id',
    ])->setProperty('followers');

    $this->hasMany('Articles', [
      'foreignKey' => 'user_id'
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
      ->scalar('username')
      ->maxLength('username', 30)
      ->requirePresence('username', 'create')
      ->notEmptyString('username');

    $validator
      ->scalar('file_name')
      ->maxLength('file_name', 255)
      ->notEmptyString('file_name');

    $validator
      ->scalar('password')
      ->maxLength('password', 255)
      ->requirePresence('password', 'create')
      ->notEmptyString('password');

    $validator
      ->scalar('role')
      ->maxLength('role', 20)
      ->requirePresence('role', 'create')
      ->notEmptyString('role');

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
    $rules->add($rules->isUnique(['username']));

    return $rules;
  }
}
