<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Model Entity
 *
 * @property string $id
 * @property string $name
 * @property string $id_brand
 * @property string $id_type
 */
class Model extends Entity
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
		'id' => true,
        'name' => true,
        'id_brand' => true,
        'id_type' => true
    ];
}
