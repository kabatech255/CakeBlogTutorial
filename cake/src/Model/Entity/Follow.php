<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Follow Entity
 *
 * @property int $id
 * @property int $follow_id
 * @property int $follower_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Follow $follow
 * @property \App\Model\Entity\Follow $follower
 */
class Follow extends Entity
{
  /**
   * Fields that can be mass assigned using newEntity() or patchEntity().
   *
   * Note that when '*' is set to true, this allows all unspecified fields to
   * be mass assigned. For security purposes, it is advised to set '*' to false
   * (or remove it), and explicitly make individual fields accessible as needed.
   *
   * @var array
   */
  protected $_accessible = [
    'follow_id' => true,
    'follower_id' => true,
    'created' => true,
    'modified' => true,
    'follow' => true,
    'follower' => true,
  ];
}
