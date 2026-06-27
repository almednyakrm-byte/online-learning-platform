// TestCourses.php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\CoursesController;
use App\Repository\CoursesRepository;
use App\Entity\Courses;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestCourses extends TestCase
{
    private $coursesController;
    private $coursesRepository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->coursesRepository = $this->createMock(CoursesRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->coursesController = new CoursesController(
            $this->coursesRepository,
            $this->entityManager,
            $this->router
        );
    }

    public function testGetCourses(): void
    {
        $courses = [
            new Courses('Course 1'),
            new Courses('Course 2'),
            new Courses('Course 3'),
        ];

        $this->coursesRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($courses);

        $response = $this->coursesController->getCourses($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($courses), $response->getContent());
    }

    public function testPostCourse(): void
    {
        $course = new Courses('New Course');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($course);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode(['name' => 'New Course']));

        $response = $this->coursesController->postCourse($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testPutCourse(): void
    {
        $course = new Courses('Updated Course');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(Courses::class, 1)
            ->willReturn($course);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($course);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(json_encode(['name' => 'Updated Course']));

        $response = $this->coursesController->putCourse(1, $this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testDeleteCourse(): void
    {
        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(Courses::class, 1)
            ->willReturn(null);

        $response = $this->coursesController->deleteCourse(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}