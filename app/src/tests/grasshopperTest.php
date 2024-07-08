<?php
use PHPUnit\Framework\TestCase;
use HiveGame\Features\Grasshopper;

class GrasshopperTest extends TestCase {
    public function testGrasshopperValidMove() {
        // Arrange
        $board = [
            '0,0' => [[0, 'queen']],
            '1,0' => [[1, 'queen']],
            '2,0' => [[0, 'grasshopper']]
        ];
        $from = '2,0';
        $to = '-1,0'; // Changed the destination to an empty space

        // Act
        $result = Grasshopper::isValidMove($board, $from, $to);

        // Assert
        $this->assertTrue($result);
    }

    public function testGrasshopperInvalidMoveEmptySpace() {
        // Arrange
        $board = [
            '0,0' => [[0, 'queen']],
            '2,0' => [[0, 'grasshopper']]
        ];
        $from = '2,0';
        $to = '0,0';

        // Act
        $result = Grasshopper::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }

    public function testGrasshopperInvalidMoveOccupiedSpace() {
        // Arrange
        $board = [
            '0,0' => [[0, 'queen']],
            '1,0' => [[1, 'queen']],
            '2,0' => [[0, 'grasshopper']],
            '3,0' => [[0, 'ant']]
        ];
        $from = '2,0';
        $to = '3,0';

        // Act
        $result = Grasshopper::isValidMove($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }
}
