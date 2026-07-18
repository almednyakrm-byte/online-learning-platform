<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ مقرراتدراسيةController;
use App\Repository\ مقرراتدراسيةRepository;
use App\Entity\ مقرراتدراسية;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\MockObject\MockObject;

class Testمقرراتدراسية extends TestCase
{
    private $controller;
    private $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MقرراتدراسيةRepository::class);
        $this->controller = new مقرراتدراسيةController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = new Response(json_encode([new مقرراتدراسية()]));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new مقرراتدراسية()]);

        $response = $this->controller->getAll();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $id = 1;
        $expectedResponse = new Response(json_encode(new مقرراتدراسية()));

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مقرراتدراسية());

        $response = $this->controller->getOne($id);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $data = ['name' => 'مقرر دراسي'];
        $expectedResponse = new Response(json_encode(new مقرراتدراسية()));

        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(new مقرراتدراسية());

        $request = new Request();
        $request->request->replace($data);

        $response = $this->controller->create($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'مقرر دراسي'];
        $expectedResponse = new Response(json_encode(new مقرراتدراسية()));

        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(new مقرراتدراسية());

        $request = new Request();
        $request->request->replace($data);

        $response = $this->controller->update($id, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $id = 1;
        $expectedResponse = new Response('', Response::HTTP_NO_CONTENT);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);

        $this->assertEquals($expectedResponse, $response);
    }
}


Note: This code assumes that the `مقرراتدراسيةController` class has methods `getAll`, `getOne`, `create`, `update`, and `delete` which are responsible for handling the CRUD operations. Also, it assumes that the `مقرراتدراسيةRepository` class has methods `findAll`, `find`, `create`, `update`, and `delete` which are responsible for interacting with the database.