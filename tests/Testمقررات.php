<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\mqratsController;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testmqrats extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new mqratsController($this->pdoMock);
    }

    public function testGetAll()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'مقررة 1'],
            ['id' => 2, 'name' => 'مقررة 2'],
        ];

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM mqrats')
            ->willReturn($this->createMock(\PDOStatement::class));

        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedData);

        $this->pdoMock->expects($this->any())
            ->method('prepare')
            ->willReturn($stmtMock);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedData, $response);
    }

    public function testCreate()
    {
        $data = ['name' => 'مقررة جديدة'];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO mqrats (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $data['name']]);

        $this->pdoMock->expects($this->any())
            ->method('prepare')
            ->willReturn($stmtMock);

        $response = $this->controller->create($data);
        $this->assertTrue($response);
    }

    public function testUpdate()
    {
        $data = ['id' => 1, 'name' => 'مقررة تعديل'];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE mqrats SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $data['name'], 'id' => $data['id']]);

        $this->pdoMock->expects($this->any())
            ->method('prepare')
            ->willReturn($stmtMock);

        $response = $this->controller->update($data);
        $this->assertTrue($response);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM mqrats WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $stmtMock = $this->createMock(\PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $this->pdoMock->expects($this->any())
            ->method('prepare')
            ->willReturn($stmtMock);

        $response = $this->controller->delete($id);
        $this->assertTrue($response);
    }
}


This test class covers the basic CRUD operations for the 'مقررات' module. It uses PHPUnit's mocking capabilities to simulate the behavior of the PDO statements. Each test method is responsible for testing a specific operation, and it uses assertions to verify the expected outcome.