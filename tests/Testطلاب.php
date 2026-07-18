<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Driver\PDOConnection;
use Doctrine\DBAL\DriverManager;

class Testطلاب extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $this->client = new Client($kernel);
    }

    public function testGetAllStudents()
    {
        $pdoMock = $this->createMock(PDOConnection::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM students')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->willReturn(['id' => 1, 'name' => 'John']);

        $this->client->request('GET', '/students');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['id' => 1, 'name' => 'John']), $response->getContent());
    }

    public function testCreateStudent()
    {
        $pdoMock = $this->createMock(PDOConnection::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name) VALUES (:name)')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'John']);

        $this->client->request('POST', '/students', ['name' => 'John']);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Student created successfully']), $response->getContent());
    }

    public function testUpdateStudent()
    {
        $pdoMock = $this->createMock(PDOConnection::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name WHERE id = :id')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'John', 'id' => 1]);

        $this->client->request('PUT', '/students/1', ['name' => 'John']);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Student updated successfully']), $response->getContent());
    }

    public function testDeleteStudent()
    {
        $pdoMock = $this->createMock(PDOConnection::class);
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id')
            ->willReturn($pdoMock);

        $pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->client->request('DELETE', '/students/1');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Student deleted successfully']), $response->getContent());
    }
}


Note: This code assumes that you have a `students` table in your database with `id` and `name` columns. The `PDO` mock objects are used to simulate the database interactions. The `Client` object is used to send HTTP requests to the API. The `Response` object is used to get the response from the API. The `JsonResponse` object is used to get the JSON response from the API.