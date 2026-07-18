<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ProfesseursController;
use App\Repository\ProfesseursRepository;
use App\Entity\Professeurs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $professeursRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->professeursRepository = $this->createMock(ProfesseursRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->professeursController = new ProfesseursController($this->professeursRepository, $this->entityManager);
    }

    public function testGetProfesseurs(): void
    {
        $this->professeursRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Professeurs('Professeur 1'),
                new Professeurs('Professeur 2'),
            ]);

        $response = $this->professeursController->getProfesseurs();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetProfesseur(): void
    {
        $professeur = new Professeurs('Professeur 1');
        $this->professeursRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($professeur);

        $response = $this->professeursController->getProfesseur(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetProfesseurNotFound(): void
    {
        $this->professeursRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->professeursController->getProfesseur(1);
    }

    public function testCreateProfesseur(): void
    {
        $professeur = new Professeurs('Professeur 1');
        $this->professeursRepository
            ->expects($this->once())
            ->method('save')
            ->with($professeur);

        $request = new Request([], [], ['professeur' => 'Professeur 1']);
        $response = $this->professeursController->createProfesseur($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateProfesseur(): void
    {
        $professeur = new Professeurs('Professeur 1');
        $this->professeursRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($professeur);

        $this->professeursRepository
            ->expects($this->once())
            ->method('save')
            ->with($professeur);

        $request = new Request([], [], ['professeur' => 'Professeur 2']);
        $response = $this->professeursController->updateProfesseur(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateProfesseurNotFound(): void
    {
        $this->professeursRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->professeursController->updateProfesseur(1, new Request());
    }

    public function testDeleteProfesseur(): void
    {
        $professeur = new Professeurs('Professeur 1');
        $this->professeursRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($professeur);

        $this->professeursRepository
            ->expects($this->once())
            ->method('remove')
            ->with($professeur);

        $response = $this->professeursController->deleteProfesseur(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProfesseurNotFound(): void
    {
        $this->professeursRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->professeursController->deleteProfesseur(1);
    }
}