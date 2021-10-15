<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Residue Entity
 *
 * @property string $residues_id
 * @property string $name1
 * @property string $identification1
 * @property string $name2
 * @property string $identification2
 * @property \Cake\I18n\FrozenDate $date
 * @property bool $descargado
 * @property string $file_name
 * @property string $path
 */
class Residue extends Entity
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
        'residues_id'=>true,
        'name1' => true,
        'identification1' => true,
        'name2' => true,
        'identification2' => true,
        'date' => true,
        'descargado' => true,
        'file' => true,
        'file_dir' => true
    ];
}
