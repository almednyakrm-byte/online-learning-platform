<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ExamController;
use App\Repository\ExamRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testاختبارات extends TestCase
{
    private $examController;
    private $examRepository;

    protected function setUp(): void
    {
        $this->examRepository = $this->createMock(ExamRepository::class);
        $this->examController = new ExamController($this->examRepository);
    }

    public function testGetAllExams()
    {
        $this->examRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Exam 1'],
                ['id' => 2, 'name' => 'Exam 2'],
            ]);

        $response = $this->examController->getAllExams();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateExam()
    {
        $this->examRepository->expects($this->once())
            ->method('create')
            ->with(['name' => 'New Exam']);

        $request = new Request([], [], ['name' => 'New Exam']);
        $response = $this->examController->createExam($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateExam()
    {
        $this->examRepository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Updated Exam']);

        $request = new Request([], [], ['name' => 'Updated Exam']);
        $response = $this->examController->updateExam(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteExam()
    {
        $this->examRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->examController->deleteExam(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `ExamController` class has methods `getAllExams`, `createExam`, `updateExam`, and `deleteExam` which interact with the `ExamRepository` class. The `ExamRepository` class has methods `findAll`, `create`, `update`, and `delete` which interact with the database. The `Request` object is used to simulate the HTTP request. The `Response` object is used to simulate the HTTP response.