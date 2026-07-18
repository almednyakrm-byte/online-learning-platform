<?php

namespace App\Tests\Controller;

use App\Controller\ معلمينController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testمعلمين extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new معلمينController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetAll()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM معلمين')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('page', 1);

        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO معلمين (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE معلمين SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->request->set('id', 1);
        $request->request->set('name', 'Jane Doe');
        $request->request->set('email', 'jane@example.com');

        $response = $this->controller->update($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM معلمين WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->attributes->set('id', 1);

        $response = $this->controller->delete($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'معلمين' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the correct HTTP status codes for each operation.