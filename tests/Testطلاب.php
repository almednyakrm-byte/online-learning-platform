<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testطلاب extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetطلاب()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM طلاب')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'طالب 1'],
                ['id' => 2, 'name' => 'طالب 2'],
            ]);

        $طلاب = new طلاب($this->pdo);
        $result = $طلاب->getطلاب();

        $this->assertEquals([
            ['id' => 1, 'name' => 'طالب 1'],
            ['id' => 2, 'name' => 'طالب 2'],
        ], $result);
    }

    public function testPostطلاب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO طلاب (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'طالب جديد');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $طلاب = new طلاب($this->pdo);
        $result = $طلاب->postطلاب(['name' => 'طالب جديد']);

        $this->assertTrue($result);
    }

    public function testPutطلاب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE طلاب SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'طالب محدث');

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $طلاب = new طلاب($this->pdo);
        $result = $طلاب->putطلاب(1, ['name' => 'طالب محدث']);

        $this->assertTrue($result);
    }

    public function testDeleteطلاب()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM طلاب WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $طلاب = new طلاب($this->pdo);
        $result = $طلاب->deleteطلاب(1);

        $this->assertTrue($result);
    }
}

class طلاب
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getطلاب()
    {
        $stmt = $this->pdo->query('SELECT * FROM طلاب');
        return $stmt->fetchAll();
    }

    public function postطلاب(array $data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO طلاب (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    public function putطلاب(int $id, array $data)
    {
        $stmt = $this->pdo->prepare('UPDATE طلاب SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteطلاب(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM طلاب WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}