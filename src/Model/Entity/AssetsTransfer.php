<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssetsTransfer Entity
 *
 * @property string $transfer_id
 * @property string $assets_id
 *
 * @property \App\Model\Entity\Transfer $transfer
 * @property \App\Model\Entity\Asset $asset
 */
class AssetsTransfer extends Entity
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
        'transfer' => true,
        'asset' => true
    ];
}
