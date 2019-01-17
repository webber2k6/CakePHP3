<?php

namespace App\Model\Table;

use App\Model\Entity\Article;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Text;
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
        // associate one article to many tags
        $this->hasMany('Tags');
    }

    /**
     * @inheritdoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->allowEmptyString('title', false)
            ->lengthBetween('title', [10, 255])
            ->allowEmptyString('body', false)
            ->minLength('body', 10);

        return $validator;
    }

    /**
     * @param Query $query An instance of the query builder
     * @param array $options contains the 'tags' option
     *
     * @return Query
     */
    public function findTagged(Query $query, array $options): Query
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];
        $query->select($columns)->distinct($columns);
        if (empty($options['tags'])) {
            // If there are no tags provided, find articles that have no tags.
            $query->leftJoinWith('Tags')
                  ->whereNull(['Tags.title']);
        } else {
            // Find articles that have one or more of the provided tags.
            $query->innerJoinWith('Tags')
                  ->whereInList('Tags.title', $options['tags']);
        }

        return $query->group(['Articles.id']);
    }

    /**
     * @param EventInterface $event
     * @param Article $entity
     * @param \ArrayObject $options
     */
    public function beforeSave(EventInterface $event, Article $entity, \ArrayObject $options): void
    {
        if (!$entity->slug && $entity->isNew()) {
            $sluggedTitle = Text::slug($entity->title);
            // trim slug to maximum length defined in schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }
}