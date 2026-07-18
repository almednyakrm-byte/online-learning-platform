<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use App\Courses;

class TestCourses extends TestCase
{
    private $courses;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->courses = new Courses($this->pdo);
    }

    public function testGetCourses(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Course 1'],
                ['id' => 2, 'name' => 'Course 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM courses')
            ->willReturn($stmt);

        $result = $this->courses->getCourses();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetCourseById(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Course 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM courses WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->courses->getCourseById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateCourse(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Course 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO courses (name) VALUES (?)')
            ->willReturn($stmt);

        $result = $this->courses->createCourse('Course 1');
        $this->assertTrue($result);
    }

    public function testUpdateCourse(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Course 1', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE courses SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->courses->updateCourse(1, 'Course 1');
        $this->assertTrue($result);
    }

    public function testDeleteCourse(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM courses WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->courses->deleteCourse(1);
        $this->assertTrue($result);
    }
}