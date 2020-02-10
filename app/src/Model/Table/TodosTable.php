<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Todo;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Todos Model
 *
 * @method Todo get($primaryKey, $options = [])
 * @method Todo newEntity($data = null, array $options = [])
 * @method Todo[] newEntities(array $data, array $options = [])
 * @method Todo|false save(EntityInterface $entity, $options = [])
 * @method Todo saveOrFail(EntityInterface $entity, $options = [])
 * @method Todo patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Todo[] patchEntities($entities, array $data, array $options = [])
 * @method Todo findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class TodosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('todos');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        return $validator;
    }
}
