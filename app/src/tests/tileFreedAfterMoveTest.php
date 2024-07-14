<?php
namespace HiveGame\Tests;

// Added because tests would automatically fail for some reason if this was not in it
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HiveGame\MoveHandler\MoveHandler;
use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;
use HiveGame\Database\Database;

class TileFreedAfterMoveTest extends TestCase
{
    protected function setUp(): void
    {
        // Start the session
        SessionManager::destroySession();
        SessionManager::set('board', [
            '0,0' => [[0, 'Q']],
        ]);
        SessionManager::set('player', 0);
        SessionManager::set('game_id', 1);

        // Mock the Database connection
        $dbMock = $this->createMock(\mysqli::class);
        $stmtMock = $this->createMock(\mysqli_stmt::class);

        $dbMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('bind_param')->willReturn(true);

        // Inject the mock Database instance
        $mockDbInstance = Database::getInstance($dbMock);
        Database::setInstance($mockDbInstance);
    }

    public function testMoveFreesUpSpace()
    {
        // Arrange
        $initialPosition = '0,0';
        $newPosition = '0,1';

        // Act
        MoveHandler::move($initialPosition, $newPosition);

        // Assert
        $board = SessionManager::get('board');

        $this->assertArrayNotHasKey($initialPosition, $board, "Initial position should be empty after move.");
        $this->assertArrayHasKey($newPosition, $board, "New position should have the moved tile.");
        $this->assertSame([[0, 'Q']], $board[$newPosition], "The moved tile should be in the new position.");
    }

    protected function tearDown(): void
    {
        SessionManager::destroySession();
    }
}
