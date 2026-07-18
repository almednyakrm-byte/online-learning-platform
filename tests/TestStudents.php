<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestStudents extends TestCase
{
    private $pdo;
    private $studentsController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->studentsController = new StudentsController($this->pdo);
    }

    public function testGetAllStudents()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe']
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM students')
            ->willReturn($stmt);

        $response = $this->studentsController->getAllStudents();
        $this->assertIsArray($response);
        $this->assertCount(2, $response);
    }

    public function testGetStudentById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM students WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->studentsController->getStudentById(1);
        $this->assertIsArray($response);
        $this->assertEquals(1, $response['id']);
    }

    public function testCreateStudent()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name) VALUES (?)')
            ->willReturn($stmt);

        $response = $this->studentsController->createStudent(['name' => 'John Doe']);
        $this->assertTrue($response);
    }

    public function testUpdateStudent()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->studentsController->updateStudent(1, ['name' => 'John Doe']);
        $this->assertTrue($response);
    }

    public function testDeleteStudent()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->studentsController->deleteStudent(1);
        $this->assertTrue($response);
    }
}

class StudentsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllStudents()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM students');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getStudentById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createStudent($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO students (name) VALUES (?)');
        return $stmt->execute([$data['name']]);
    }

    public function updateStudent($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE students SET name = ? WHERE id = ?');
        return $stmt->execute([$data['name'], $id]);
    }

    public function deleteStudent($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM students WHERE id = ?');
        return $stmt->execute([$id]);
    }
}