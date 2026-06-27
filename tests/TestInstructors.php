<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\InstructorsController;
use App\Repository\InstructorsRepository;
use App\Service\InstructorsService;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class TestInstructors extends TestCase
{
    private $instructorsController;
    private $instructorsRepository;
    private $instructorsService;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->instructorsRepository = $this->createMock(InstructorsRepository::class);
        $this->instructorsService = $this->createMock(InstructorsService::class);
        $this->instructorsController = new InstructorsController($this->instructorsRepository, $this->instructorsService);
    }

    public function testGetInstructors()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM instructors')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new ServerRequest('GET', '/instructors');
        $response = $this->instructorsController->getInstructors($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testCreateInstructor()
    {
        $instructorData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO instructors (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new ServerRequest('POST', '/instructors', [], json_encode($instructorData));
        $response = $this->instructorsController->createInstructor($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testUpdateInstructor()
    {
        $instructorId = 1;
        $instructorData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE instructors SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new ServerRequest('PUT', '/instructors/' . $instructorId, [], json_encode($instructorData));
        $response = $this->instructorsController->updateInstructor($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testDeleteInstructor()
    {
        $instructorId = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM instructors WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new ServerRequest('DELETE', '/instructors/' . $instructorId);
        $response = $this->instructorsController->deleteInstructor($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'instructors' module. It uses mocked PDO statements to simulate database interactions. The tests cover GET, POST, PUT, and DELETE requests.