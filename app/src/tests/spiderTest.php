<?php
namespace HiveGame\Tests;

// Added because tests would automatically fail for some reason if this was not in it
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use HiveGame\Features\Spider;

class SpiderTest extends TestCase {
    public function testSpiderValidMove() {
        // Arrange
        $board = [
            '0,0' => [[0, 'spider']],
            '1,0' => [[1, 'queen']],
            '2,0' => [[0, 'beetle']],
            '3,0' => [],
            '4,0' => []
        ];
        $from = '0,0';
        $to = '3,0';

        // Act
        $result = Spider::isValidMove($board, $from, $to);

        // Assert
        $this->assertTrue($result);
    }

    public function testSpiderInvalidMoveToSamePosition() {
        // Arrange
        $board = [
            '0,0' => [[0, 'spider']]
        ];
        $from = '0,0';
        $to = '0,0';

        // Act
        $result = Spider::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }

    public function testSpiderInvalidMoveToOccupiedSpace() {
        // Arrange
        $board = [
            '0,0' => [[0, 'spider']],
            '1,0' => [[1, 'queen']],
            '2,0' => [[0, 'ant']],
            '3,0' => [[1, 'queen']],
            '4,0' => [[1, 'queen']]
        ];
        $from = '0,0';
        $to = '4,0';

        // Act
        $result = Spider::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }

    public function testSpiderInvalidMoveWithBacktracking() {
        // Arrange
        $board = [
            '0,0' => [[0, 'spider']],
            '1,0' => [],
            '2,0' => [],
            '3,0' => [],
            '4,0' => []
        ];
        $from = '0,0';
        $to = '0,0';

        // Act
        $result = Spider::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }
}
