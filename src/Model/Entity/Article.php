<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

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

    /**
     * TODO
     * @param $event
     * @param Article $entity
     * @param array $options
     */
    public function beforeSave($event, $entity, $options): void
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // trim slug to maximum length defined in schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }
}