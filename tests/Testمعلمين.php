<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class Testمعلمين extends TestCase
{
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testGetAllمعلمين()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Teacher 1'],
                ['id' => 2, 'name' => 'Teacher 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM معلمين')
            ->willReturn($stmt);

        $معلمينController = new معلمينController($this->pdo);
        $result = $معلمينController->getAllمعلمين($this->request, $this->response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Teacher 1'],
            ['id' => 2, 'name' => 'Teacher 2'],
        ], json_decode($result->getBody()->getContents(), true));
    }

    public function testGetمعلمينById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Teacher 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM معلمين WHERE id = ?')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $معلمينController = new معلمينController($this->pdo);
        $result = $معلمينController->getمعلمينById($this->request, $this->response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'Teacher 1'], json_decode($result->getBody()->getContents(), true));
    }

    public function testCreateمعلمين()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Teacher']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO معلمين (name) VALUES (?)')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Teacher']);

        $معلمينController = new معلمينController($this->pdo);
        $result = $معلمينController->createمعلمين($this->request, $this->response);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals(['message' => 'Teacher created successfully'], json_decode($result->getBody()->getContents(), true));
    }

    public function testUpdateمعلمين()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'Updated Teacher', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE معلمين SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Teacher']);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $معلمينController = new معلمينController($this->pdo);
        $result = $معلمينController->updateمعلمين($this->request, $this->response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['message' => 'Teacher updated successfully'], json_decode($result->getBody()->getContents(), true));
    }

    public function testDeleteمعلمين()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM معلمين WHERE id = ?')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $معلمينController = new معلمينController($this->pdo);
        $result = $معلمينController->deleteمعلمين($this->request, $this->response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(['message' => 'Teacher deleted successfully'], json_decode($result->getBody()->getContents(), true));
    }
}