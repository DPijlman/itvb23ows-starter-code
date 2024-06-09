<?php

use PHPUnit\Framework\TestCase;
use App\classes\HiveGame;

class QueenPlayedAtOrBeforeFourthTurnTest extends TestCase {
    protected $hiveGame;

    protected function setUp(): void {
        $this->hiveGame = new HiveGame();

        // Initial setup for a new game
        $_SESSION = [];
        $this->hiveGame->restart();
    }

    public function testMustPlayQueenByFourthTurn() {
        // Simulate three turns for player 0 without playing the Queen
        $this->hiveGame->play('B', '0,0');  // Turn 1
        $_SESSION['player'] = 0;
        $this->hiveGame->play('B', '0,1');  // Turn 2
        $_SESSION['player'] = 0;
        $this->hiveGame->play('B', '0,2');  // Turn 3

        // Now try to play a non-Queen piece on the fourth turn
        $_SESSION['player'] = 0;
        $this->hiveGame->play('B', '0,3');  // Turn 4

        // Assert that the error message is set correctly
        $this->assertEquals("You must play your Queen Bee by the 4th turn.", $_SESSION['error']);
    }

    public function testCanPlayQueenByFourthTurn() {
        // Simulate three turns for player 0 without playing the Queen
        $this->hiveGame->play('B', '0,0');  // Turn 1
        $_SESSION['player'] = 0;
        $this->hiveGame->play('B', '0,1');  // Turn 2
        $_SESSION['player'] = 0;
        $this->hiveGame->play('B', '0,2');  // Turn 3

        // Now play the Queen piece on the fourth turn
        $_SESSION['player'] = 0;
        $this->hiveGame->play('Q', '0,3');  // Turn 4

        // Assert that the Queen was played successfully
        $this->assertArrayHasKey('0,3', $_SESSION['board']);
        $this->assertEquals([['0', 'Q']], $_SESSION['board']['0,3']);
    }
}
