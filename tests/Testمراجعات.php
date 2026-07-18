<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مراجعاتController;
use App\Repository\مراجعاتRepository;
use App\Entity\مراجعات;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testمراجعات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مراجعاتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new مراجعاتController($this->repository, $this->entityManager);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetById()
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مراجعات());

        $response = $this->controller->getById($id);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testPost()
    {
        $data = ['name' => 'مراجعات'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new مراجعات($data))
            ->willReturn(new مراجعات($data));

        $response = $this->controller->post($data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testPut()
    {
        $id = 1;
        $data = ['name' => 'مراجعات'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مراجعات($data));
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new مراجعات($data));

        $response = $this->controller->put($id, $data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مراجعات());

        $response = $this->controller->delete($id);
        $this->assertEquals(['data' => []], $response->toArray());
    }

    public function testDeleteNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($id);
    }
}



// App\Controller\مراجعاتController.php

namespace App\Controller;

use App\Repository\مراجعاتRepository;
use App\Entity\مراجعات;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class مراجعاتController
{
    private $repository;
    private $entityManager;

    public function __construct(مراجعاتRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        $data = $this->repository->findAll();
        return new Response(['data' => $data]);
    }

    public function getById($id)
    {
        $data = $this->repository->find($id);
        if (!$data) {
            throw new NotFoundHttpException('Not found');
        }
        return new Response(['data' => $data]);
    }

    public function post($data)
    {
        $entity = new مراجعات($data);
        $this->repository->save($entity);
        return new Response(['data' => $entity]);
    }

    public function put($id, $data)
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Not found');
        }
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new Response(['data' => $entity]);
    }

    public function delete($id)
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Not found');
        }
        $this->repository->remove($entity);
        return new Response(['data' => []]);
    }
}