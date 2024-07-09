<?php

use PHPUnit\Framework\TestCase;
use HiveGame\Features\SoldierAnt;

class SoldierAntTest extends TestCase {
    public function testSoldierAntInvalidMoveOccupiedSpace() {
        // Arrange
        $board = [
            '0,0' => [[0, 'queen']],
            '1,0' => [[1, 'queen']],
            '2,0' => [[0, 'soldierant']],
            '2,2' => [[0, 'ant']]
        ];
        $from = '2,0';
        $to = '2,2';

        // Act
        $result = SoldierAnt::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }

    public function testSoldierAntInvalidMoveSameSpace() {
        // Arrange
        $board = [
            '0,0' => [[0, 'queen']],
            '1,0' => [[1, 'queen']],
            '2,0' => [[0, 'soldierant']]
        ];
        $from = '2,0';
        $to = '2,0';

        // Act
        $result = SoldierAnt::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }
}
