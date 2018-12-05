<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Heatmap Entity
 *
 * @property int $id
 * @property int $accesspoint_id
 * @property int $floorplan_id
 * @property int $x
 * @property int $y
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\AccessPoint $access_point
 * @property \App\Model\Entity\FloorplansLibrary $floorplans_library
 */
class Heatmap extends Entity
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
        'accesspoint_id' => true,
        'floorplan_id' => true,
        'x' => true,
        'y' => true,
        'created' => true,
        'modified' => true,
        'access_point' => true,
        'floorplans_library' => true
    ];
}
