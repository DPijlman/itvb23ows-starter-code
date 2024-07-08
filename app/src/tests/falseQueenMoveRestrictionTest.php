<?php
namespace HiveGame\Tests;

use PHPUnit\Framework\TestCase;
use HiveGame\MoveHandler\MoveHandler;
use HiveGame\Util\SessionManager;
use HiveGame\Database\Database;

class FalseQueenMoveRestrictionTest extends TestCase
{
    protected function setUp(): void
    {
        // Start the session
        SessionManager::destroySession();
        SessionManager::set('board', [
            '0,0' => [[0, 'Q']], // Player 1's queen
            '1,0' => [[1, 'Q']], // Player 2's queen
        ]);
        SessionManager::set('player', 0); // Set to player 1's turn
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

    public function testPreventIsolatedQueenMove()
    {
        // Arrange
        $initialPosition = '0,0';
        $newPosition = '0,1';

        // Act
        $result = MoveHandler::move($initialPosition, $newPosition);

        // Assert
        $this->assertFalse($result, "The move should not be allowed.");
        $board = SessionManager::get('board');
        $error = SessionManager::get('error');

        $this->assertArrayHasKey($initialPosition, $board, "Initial position should still have the queen.");
        $this->assertArrayNotHasKey($newPosition, $board, "New position should not have the queen.");
        $this->assertSame('Invalid move: This move isolates the opponent\'s queen.', $error, "The error message should indicate an invalid move.");
    }

    protected function tearDown(): void
    {
        SessionManager::destroySession();
    }
}
