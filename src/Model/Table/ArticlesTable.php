<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * This class provides access to the collection of entities stored in the `articles` table
 *
 * @package App\Model\Table
 */
class ArticlesTable extends Table
{
    /**
     * @inheritdoc
     */
    public function initialize(array $config): void
    {
        // The 'Timestamp' behavior will automatically populate the created and modified columns
        $this->addBehavior('Timestamp');
    }

    /**
     * @inheritdoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmpty('title')
            ->lengthBetween('title', [10, 255])
            ->notEmpty('body')
            ->minLength('body', 10);

        return $validator;
    }
}