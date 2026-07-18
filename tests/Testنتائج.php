<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ResultRepository;
use App\Entity\Result;
use App\Controller\ResultController;
use PHPUnit\Framework\MockObject\MockObject;

class Testنتائج extends TestCase
{
    private $resultController;
    private $resultRepository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->resultRepository = $this->createMock(ResultRepository::class);
        $this->resultController = new ResultController($this->resultRepository);

        $this->pdoMock->method('prepare')->willReturn($this->pdoMock);
        $this->pdoMock->method('execute')->willReturn(true);
        $this->pdoMock->method('fetch')->willReturn(null);
        $this->pdoMock->method('fetchAll')->willReturn([]);
    }

    public function testGetResults()
    {
        $this->resultRepository->method('findAll')->willReturn([new Result()]);
        $response = $this->resultController->getResults();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetResult()
    {
        $result = new Result();
        $this->resultRepository->method('find')->willReturn($result);
        $response = $this->resultController->getResult(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetResultNotFound()
    {
        $this->resultRepository->method('find')->willReturn(null);
        $this->expectException(NotFoundHttpException::class);
        $this->resultController->getResult(1);
    }

    public function testPostResult()
    {
        $result = new Result();
        $this->resultRepository->method('save')->willReturn($result);
        $response = $this->resultController->postResult(new Result());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutResult()
    {
        $result = new Result();
        $this->resultRepository->method('find')->willReturn($result);
        $this->resultRepository->method('save')->willReturn($result);
        $response = $this->resultController->putResult(1, new Result());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutResultNotFound()
    {
        $this->resultRepository->method('find')->willReturn(null);
        $this->expectException(NotFoundHttpException::class);
        $this->resultController->putResult(1, new Result());
    }

    public function testDeleteResult()
    {
        $result = new Result();
        $this->resultRepository->method('find')->willReturn($result);
        $this->resultRepository->method('remove')->willReturn(true);
        $response = $this->resultController->deleteResult(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteResultNotFound()
    {
        $this->resultRepository->method('find')->willReturn(null);
        $this->expectException(NotFoundHttpException::class);
        $this->resultController->deleteResult(1);
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'نتائج' module. It uses mocked PDO statements to isolate the dependencies of the ResultController. The tests cover the following scenarios:

- `testGetResults`: Tests the GET request to retrieve all results.
- `testGetResult`: Tests the GET request to retrieve a single result.
- `testGetResultNotFound`: Tests the GET request to retrieve a non-existent result.
- `testPostResult`: Tests the POST request to create a new result.
- `testPutResult`: Tests the PUT request to update an existing result.
- `testPutResultNotFound`: Tests the PUT request to update a non-existent result.
- `testDeleteResult`: Tests the DELETE request to delete an existing result.
- `testDeleteResultNotFound`: Tests the DELETE request to delete a non-existent result.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should consider using a more robust testing framework and mocking library to handle more complex scenarios.