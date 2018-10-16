<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ScanResult Entity
 *
 * @property int $id
 * @property int $accesspoint_id
 * @property \Cake\I18n\Time $scan_timestamp
 * @property string $mac_addr
 * @property int $rssi
 * @property string $vendor
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\AccessPoint $access_point
 */
class ScanResult extends Entity
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
        'scan_timestamp' => true,
        'mac_addr' => true,
        'rssi' => true,
        'vendor' => true,
        'created' => true,
        'modified' => true,
        'access_point' => true
    ];
}
