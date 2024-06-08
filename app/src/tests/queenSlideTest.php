<?php
use PHPUnit\Framework\TestCase;
use App\classes\Util;

class QueenSlideTest extends TestCase
{
    public function testSlideValidMove()
    {
        // Arrange
        $board = [
            '0,0' => [['player', 'piece']],
            '1,0' => [['player', 'piece']],
        ];
        $from = '0,0';
        $to = '0,1';

        // Act
        $result = Util::slide($board, $from, $to);

        // Assert
        $this->assertTrue($result);
    }

    public function testSlideInvalidMove()
    {
        // Arrange
        $board = [
            '0,0' => [['player', 'piece']],
            '1,0' => [['player', 'piece']],
        ];
        $from = '0,0';
        $to = '1,0';

        // Act
        $result = Util::slide($board, $from, $to);

        // Assert
        $this->assertFalse($result);
    }
}