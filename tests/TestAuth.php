<?php

namespace App\Tests;

use App\Auth;
use App\Database;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class TestAuth extends TestCase
{
    private $auth;
    private $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->auth = new Auth($this->database);
    }

    public function testLoginSuccess()
    {
        $username = 'testUser';
        $password = 'testPassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => $password]);

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testUser';
        $password = 'wrongPassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => 'testPassword']);

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'newUser';
        $password = 'newPassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(null);

        $this->database->expects($this->once())
            ->method('createUser')
            ->with($username, $password);

        $result = $this->auth->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'existingUser';
        $password = 'newPassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username]);

        $result = $this->auth->register($username, $password);

        $this->assertFalse($result);
    }
}