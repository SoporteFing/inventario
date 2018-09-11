<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TechnicalReport Entity
 *
 * @property int $technical_report_id
 * @property string $assets_id
 * @property string $evaluation
 * @property string $recommendation
 * @property \Cake\I18n\FrozenDate $date
 * @property string $file_name
 * @property string $path
 * @property string $residues_id
 * @property string $evaluator_name
 * @property string $year
 * @property string $facultyInitials
 * @property int $internal_id
 * @property bool $descargado
 *
 * @property \App\Model\Entity\Asset $asset
 * @property \App\Model\Entity\Residue $residue
 * @property \App\Model\Entity\Internal $internal
 */
class TechnicalReport extends Entity
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
        'technical_report_id'=>true,
        'assets_id' => true,
        'evaluation' => true,
        'recommendation' => true,
        'date' => true,
        'file_name' => true,
        'path' => true,
        'residues_id' => true,
        'evaluator_name' => true,
        'year' => true,
        'facultyInitials' => true,
        'internal_id' => true,
        'descargado' => true,
        'asset' => true,
        'residue' => true,
        'internal' => true
    ];
}
