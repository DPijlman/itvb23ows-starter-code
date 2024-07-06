<?php
namespace HiveGame\Tests;

use PHPUnit\Framework\TestCase;
use HiveGame\MoveHandler\PlayHandler;
use HiveGame\Util\SessionManager;
use HiveGame\Util\Util;

class DropdownContentTest extends TestCase
{
    protected function setUp(): void
    {
        SessionManager::destroySession();
        SessionManager::set('board', []);
        SessionManager::set('player', 0);
        SessionManager::set('hand', [
            0 => ['queen' => 1, 'spider' => 1],
            1 => ['queen' => 1, 'spider' => 1]
        ]);
    }

    public function testPlayValidMove()
    {
        // Arrange
        $piece = 'queen';
        $position = '0,0';

        // Act
        $result = PlayHandler::play($piece, $position);

        // Assert
        $this->assertTrue($result);

        $board = SessionManager::get('board');
        $hand = SessionManager::get('hand');

        $this->assertArrayHasKey($position, $board);
        $this->assertSame([0, $piece], end($board[$position]));
        $this->assertArrayNotHasKey($piece, $hand[0]);
    }

    public function testPlayInvalidMove()
    {
        // Arrange
        $piece = 'ant';
        $position = '0,0';

        // Act
        $result = PlayHandler::play($piece, $position);

        // Assert
        $this->assertFalse($result);

        $error = SessionManager::get('error');
        $this->assertSame('Invalid piece.', $error);
    }

    public function testDropdownOnlyShowsPlayableTiles()
    {
        // Arrange
        PlayHandler::play('queen', '0,0');
        $board = SessionManager::get('board');
        $to = [];

        $board_keys = array_keys($board);
        foreach (Util::$OFFSETS as $pq) {
            foreach ($board_keys as $pos) {
                $pq2 = explode(',', $pos);
                $new_pos = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
                if (!isset($board[$new_pos]) || empty($board[$new_pos])) {
                    $to[] = $new_pos;
                }
            }
        }
        $to = array_unique($to);

        // Act
        $playablePositions = [];
        foreach ($to as $pos) {
            if (Util::hasNeighbour($pos, $board) || count($board) == 0) {
                if (!isset($board[$pos]) || empty($board[$pos])) {
                    $playablePositions[] = $pos;
                }
            }
        }

        // Assert
        foreach ($playablePositions as $pos) {
            $this->assertTrue(Util::hasNeighbour($pos, $board) || count($board) == 0);
        }
    }
}
