<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Model\Entity\Todo;
use App\Model\Table\TodosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\TodosController Test Case
 *
 * @property TodosTable $Todos
 * @uses \App\Controller\TodosController
 */
class TodosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Todos',
    ];

    /**
     * {@inheritDocs}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Todos = TableRegistry::getTableLocator()->get('Todos');
    }

    /**
     * {@inheritDocs}
     */
    public function tearDown(): void
    {
        unset($this->Todos);
        parent::tearDown();
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        $recordCount = 30;
        for ($i = 0; $i < $recordCount; $i++) {
            $this->generateTodo();
        }

        $this->get('/api/todos.json');
        $this->assertResponseCode(200, '200が返ってきていない');

        $actual = $this->viewVariable('todos');
        $this->assertCount(20, $actual, 'Todoをページングの上限だけ取得できていない');
        $this->assertInstanceOf(
            Todo::class,
            $actual->first(),
            'Todoが返ってきていない'
        );
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $expected = $this->generateTodo(['title' => 'viewのテスト用タイトル']);

        $this->get("/api/todos/{$expected->id}.json");
        $this->assertResponseCode(200, '200が返ってきていない');

        $actual = $this->viewVariable('todo');
        $this->assertSame(
            $expected->title,
            $actual->title,
            '意図したTodoが返ってきていない'
        );
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        $params = [
            'title' => 'テスト用タイトル',
            'description' => 'テスト用説明文',
        ];
        $this->post('/api/todos.json', $params);
        $this->assertResponseCode(200, '200が返ってきていない');

        $actual = $this->viewVariable('todo');
        $this->assertTrue(
            $this->Todos->exists($params),
            '正しく保存できていない'
        );
        $this->assertSame(
            $params['title'],
            $actual->title,
            '意図したデータでTodoを保存できていない'
        );
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit(): void
    {
        $todo = $this->generateTodo();

        $params = ['title' => '更新用タイトル', 'description' => '更新用説明文'];
        $this->patch("/api/todos/{$todo->id}.json", $params);
        $this->assertResponseCode(200, '200が返ってきていない');

        $actual = $this->viewVariable('todo');
        $this->assertTrue(
            $this->Todos->exists($params),
            '正しく編集できていない'
        );
        $this->assertSame(
            $params['title'],
            $actual->title,
            '意図したデータでTodoを編集できていない'
        );
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete(): void
    {
        $todo = $this->generateTodo(['title' => '削除されるtodo']);

        $this->delete("/api/todos/{$todo->id}.json");
        $this->assertResponseCode(200, '200が返ってきていない');

        $this->assertFalse(
            $this->Todos->exists(['title' => $todo->title]),
            '正しく削除できていない'
        );
    }

    /**
     * factory method
     *
     * @param array $overrideData 上書きしたいパラメータ
     * @return Todo
     */
    protected function generateTodo(array $overrideData = []): Todo
    {
        $data = array_merge([
            'title' => 'タイトルです',
            'description' => '説明です',
        ], $overrideData);

        $todo = $this->Todos->newEntity($data);
        if (!$this->Todos->save($todo)) {
            $this->fail('データの生成に失敗しました');
        }

        return $todo;
    }
}
