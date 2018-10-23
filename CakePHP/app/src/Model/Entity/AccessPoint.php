<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccessPoint Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property string $mac_addr
 * @property int $total_devices_count
 * @property int $total_unique_devices_count
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Customer $customer
 */
class AccessPoint extends Entity
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
        'customer_id' => true,
        'mac_addr' => true,
        'total_devices_count' => true,
        'total_unique_devices_count' => true,
        'created' => true,
        'modified' => true,
        'customer' => true
    ];
}
