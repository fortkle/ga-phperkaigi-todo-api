<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Todo;
use App\Model\Table\TodosTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;

/**
 * Todos Controller
 *
 * @property TodosTable $Todos
 *
 * @method Todo[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TodosController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $todos = $this->paginate($this->Todos);

        $this->set(compact('todos'));
    }

    /**
     * View method
     *
     * @param string|null $id Todo id.
     * @return void
     */
    public function view($id = null)
    {
        $todo = $this->Todos->get($id);

        $this->set('todo', $todo);
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $todo = $this->Todos->newEmptyEntity();
        $todo = $this->Todos->patchEntity($todo, $this->request->getData());
        $this->Todos->saveOrFail($todo);

        $this->set(compact('todo'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Todo id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $todo = $this->Todos->get($id);
        $todo = $this->Todos->patchEntity($todo, $this->request->getData());
        $this->Todos->saveOrFail($todo);

        $this->set(compact('todo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Todo id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $todo = $this->Todos->get($id);
        $this->Todos->deleteOrFail($todo);

        $this->set(compact('todo'));
    }
}
