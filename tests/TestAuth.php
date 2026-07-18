<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Auth;
use App\Database;

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
        $username = 'testuser';
        $password = 'testpassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => $password]);

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
        $this->assertEquals($username, $_SESSION['username']);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => 'testpassword']);

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
        $this->assertNull($_SESSION['username']);
    }

    public function testRegisterSuccess()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(null);

        $this->database->expects($this->once())
            ->method('insertUser')
            ->with($username, $password);

        $result = $this->auth->register($username, $password);

        $this->assertTrue($result);
        $this->assertEquals($username, $_SESSION['username']);
    }

    public function testRegisterFailure()
    {
        $username = 'existinguser';
        $password = 'newpassword';

        $this->database->expects($this->once())
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username]);

        $result = $this->auth->register($username, $password);

        $this->assertFalse($result);
        $this->assertNull($_SESSION['username']);
    }
}