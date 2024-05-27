<?php

use PHPUnit\Framework\TestCase;

include_once 'hiveGame.php';

class HiveGameTest extends TestCase
{
    // Test valid options for placing a tile
    public function testValidTilePlacements()
    {
        // Arrange
        $board = [
            '0,0' => [['player' => 0, 'type' => 'queen_bee']], // White queen bee at 0,0
            '1,-1' => [['player' => 1, 'type' => 'beetle']] // Black beetle at 1,-1
        ];

        // Act
        $actualValidOptions = getPossiblePlacements($board);

        // Assert
        $expectedValidOptions = ['0,1', '1,0', '-1,0', '-1,1', '2,0', '0,0', '0,-1', '2,-1'];
        $this->assertEquals($expectedValidOptions, $actualValidOptions);
    }

    // Test invalid options for placing a tile
    public function testInvalidTilePlacements()
    {
        // Arrange
        $board = [
            '0,0' => [['player' => 0, 'type' => 'queen_bee']], // White queen bee at 0,0
            '1,-1' => [['player' => 1, 'type' => 'beetle']] // Black beetle at 1,-1
        ];

        // Act
        $actualValidOptions = getPossiblePlacements($board);

        // Assert
        $expectedInvalidOptions = ['1,1', '0,-2', '3,0', '-2,1'];
        foreach ($expectedInvalidOptions as $option) {
            $this->assertNotContains($option, $actualValidOptions);
        }
    }
}
