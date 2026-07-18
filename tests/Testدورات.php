<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\CourseController;
use App\Repository\CourseRepository;
use App\Entity\Course;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

class Testدورات extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CourseRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new CourseController($this->repository, $this->router);
    }

    public function testGetCourses(): void
    {
        $courses = [
            new Course('Course 1', 'Description 1'),
            new Course('Course 2', 'Description 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($courses);

        $response = $this->controller->getCourses();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($courses), $response->getContent());
    }

    public function testCreateCourse(): void
    {
        $course = new Course('Course 1', 'Description 1');
        $request = new Request([], [], ['json' => ['name' => 'Course 1', 'description' => 'Description 1']]);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($course);

        $response = $this->controller->createCourse($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testUpdateCourse(): void
    {
        $course = new Course('Course 1', 'Description 1');
        $request = new Request([], [], ['json' => ['name' => 'Course 2', 'description' => 'Description 2']]);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($course);

        $response = $this->controller->updateCourse(1, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testDeleteCourse(): void
    {
        $course = new Course('Course 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($course);

        $response = $this->controller->deleteCourse(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This test class assumes that the `CourseController` has methods `getCourses`, `createCourse`, `updateCourse`, and `deleteCourse` which handle the respective CRUD operations. Also, it assumes that the `CourseRepository` has methods `findAll`, `save`, `find`, and `remove` which handle the respective CRUD operations.