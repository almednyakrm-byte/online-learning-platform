<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\أساتذةController;
use App\Repository\أساتذةRepository;
use App\Service\أساتذةService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testأساتذة extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(أساتذةRepository::class);
        $this->service = $this->createMock(أساتذةService::class);
        $this->controller = new أساتذةController($this->repository, $this->service);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAll($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $response = $this->controller->getById($this->request, 1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = new JsonResponse(['message' => 'Created successfully']);
        $this->service->expects($this->once())
            ->method('create')
            ->with(['name' => 'Test Name'])
            ->willReturn(true);
        $this->request->method('request')
            ->willReturn(['name' => 'Test Name']);
        $response = $this->controller->create($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = new JsonResponse(['message' => 'Updated successfully']);
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Test Name'])
            ->willReturn(true);
        $this->request->method('request')
            ->willReturn(['name' => 'Test Name']);
        $response = $this->controller->update($this->request, 1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['message' => 'Deleted successfully']);
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);
        $response = $this->controller->delete($this->request, 1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\أساتذةController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\أساتذةRepository;
use App\Service\أساتذةService;

class أساتذةController
{
    private $repository;
    private $service;

    public function __construct(أساتذةRepository $repository, أساتذةService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getAll(Request $request)
    {
        return new JsonResponse(['data' => $this->repository->findAll()]);
    }

    public function getById(Request $request, int $id)
    {
        return new JsonResponse(['data' => $this->repository->find($id)]);
    }

    public function create(Request $request)
    {
        $data = $request->request->all();
        return new JsonResponse(['message' => $this->service->create($data)]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->request->all();
        return new JsonResponse(['message' => $this->service->update($id, $data)]);
    }

    public function delete(Request $request, int $id)
    {
        return new JsonResponse(['message' => $this->service->delete($id)]);
    }
}



// App\Repository\أساتذةRepository.php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class أساتذةRepository extends EntityRepository
{
    public function findAll()
    {
        // Implement logic to find all entities
    }

    public function find(int $id)
    {
        // Implement logic to find an entity by id
    }
}



// App\Service\أساتذةService.php
namespace App\Service;

class أساتذةService
{
    public function create(array $data)
    {
        // Implement logic to create a new entity
    }

    public function update(int $id, array $data)
    {
        // Implement logic to update an entity
    }

    public function delete(int $id)
    {
        // Implement logic to delete an entity
    }
}