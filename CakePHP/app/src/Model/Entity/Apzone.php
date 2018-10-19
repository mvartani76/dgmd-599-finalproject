<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Apzone Entity
 *
 * @property int $id
 * @property int $location_id
 * @property int $accesspoint_id
 * @property string $fixture_no
 * @property string $placement
 * @property string $floor
 * @property int $scanresults_count
 * @property bool $ignore_further_incidents
 * @property bool $is_reviewed
 * @property \Cake\I18n\Time $review_date
 * @property \Cake\I18n\Time $last_scanresult
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Location $location
 * @property \App\Model\Entity\AccessPoint $access_point
 */
class Apzone extends Entity
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
        'location_id' => true,
        'accesspoint_id' => true,
        'fixture_no' => true,
        'placement' => true,
        'floor' => true,
        'scanresults_count' => true,
        'ignore_further_incidents' => true,
        'is_reviewed' => true,
        'review_date' => true,
        'last_scanresult' => true,
        'created' => true,
        'modified' => true,
        'location' => true,
        'access_point' => true
    ];
}
