<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * This class represent a single article record in the database, and provides row level behavior for our data.
 *
 * @package App\Model\Entity
 */
class Article extends Entity
{
    /**
     * Configures witch values are accessible (true) or not (false).
     * '*' maps to all not mentioned properties
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'slug' => false,
    ];
}