<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PDO;

class Testدورات extends TestCase
{
    private $pdo;
    private $request;
    private $response;
    private $stream;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
    }

    public function testGetدورات()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM دورات')
            ->willReturn($this->createMock(PDOStatement::class));

        $دوراتController = new دوراتController($this->pdo);
        $response = $دوراتController->getدورات($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPostدورات()
    {
        $data = ['name' => 'دورة جديدة'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO دورات (name) VALUES (:name)')
            ->willReturn($this->createMock(PDOStatement::class));

        $دوراتController = new دوراتController($this->pdo);
        $response = $دوراتController->postدورات($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPutدورات()
    {
        $data = ['name' => 'دورة محدثة'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE دورات SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $دوراتController = new دوراتController($this->pdo);
        $response = $دوراتController->putدورات($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteدورات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM دورات WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $دوراتController = new دوراتController($this->pdo);
        $response = $دوراتController->deleteدورات($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}