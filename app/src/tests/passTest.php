<?php
namespace HiveGame\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HiveGame\MoveHandler\PassHandler;
use HiveGame\Util\SessionManager;

class PassTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        SessionManager::set('board', [
            '0,0' => [[0, 'queen']],
            '1,0' => [[1, 'queen']],
            '0,1' => [[0, 'ant']],
        ]);
        SessionManager::set('player', 0);
        SessionManager::set('game_id', 1);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testPassWithValidMoves()
    {
        // Arrange
        SessionManager::set('player', 0);

        // Act
        PassHandler::pass();

        // Assert
        $this->assertEquals('You cannot pass your turn as you have valid moves available.', SessionManager::get('error'));
        $this->assertEquals(0, SessionManager::get('player'));
    }

    public function testPassWithoutValidMoves()
    {
        // Arrange
        SessionManager::set('player', 0);
        SessionManager::set('board', [
            '0,0' => [[1, 'queen']],
        ]);

        // Act
        PassHandler::pass();

        // Assert
        $this->assertNull(SessionManager::get('error'));
        $this->assertEquals(1, SessionManager::get('player'));
    }
}
