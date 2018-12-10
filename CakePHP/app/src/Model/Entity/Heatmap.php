<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
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

    public function getFullImageUrl()
    {
        if ($this->has('floorplans_library')) {
            return 'https://' . Configure::read('Settings.floorplans_container') . '/' . $this->floorplans_library->path . $this->floorplans_library->filename;
        }
        return $this->source_image_url;
    }

    // Helper function that passes in appropriate style code to position wifi icon on top of the floorplan.
    // Currenty user inputs pixels and we calculate a % based on floorplan height/width.
    public function positionAccessPoint() {
        $apstyle = '<style>
        .ap-position {
            position: absolute;
            z-index:10;
            left:' . $this->x/$this->floorplans_library->width*100 . '%;
            top:' . $this->y/$this->floorplans_library->height*100 . '%;
        </style>';
        return $apstyle;
    }
}
