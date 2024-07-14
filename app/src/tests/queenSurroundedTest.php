<?php
namespace HiveGame\Tests;

// Added because tests would automatically fail for some reason if this was not in it
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HiveGame\Util\Util;
use HiveGame\Util\SessionManager;

class QueenSurroundedTest extends TestCase
{
    protected function setUp(): void
    {
        SessionManager::destroySession();
        SessionManager::set('board', []);
        SessionManager::set('player', 0);
    }

    public function testQueenIsCompletelySurrounded()
    {
        // Arrange
        $board = [
            '0,0' => [[0, 'Q']],
            '0,1' => [[1, 'ant']],
            '1,0' => [[1, 'ant']],
            '1,-1' => [[1, 'ant']],
            '0,-1' => [[1, 'ant']],
            '-1,0' => [[1, 'ant']],
            '-1,1' => [[1, 'ant']]
        ];

        SessionManager::set('board', $board);

        // Act
        $result = Util::isQueenSurrounded($board, '0,0');

        // Assert
        $this->assertTrue($result);
    }

    public function testQueenIsNotCompletelySurrounded()
    {
        // Arrange
        $board = [
            '0,0' => [[0, 'Q']],
            '0,1' => [[1, 'ant']],
            '1,0' => [[1, 'ant']],
            '1,-1' => [[1, 'ant']],
            '0,-1' => [[1, 'ant']],
            '-1,0' => [[1, 'ant']]
        ];

        SessionManager::set('board', $board);

        // Act
        $result = Util::isQueenSurrounded($board, '0,0');

        // Assert
        $this->assertFalse($result);
    }
}
