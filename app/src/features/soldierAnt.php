<?php
namespace App\features;

class SoldierAnt
{
    public function canMove($from, $to, $board)
    {
        list($fromX, $fromY) = explode(',', $from);
        list($toX, $toY) = explode(',', $to);

        if ($fromX == $toX && $fromY == $toY) {
            $_SESSION['error'] = "Invalid move: Soldier Ant cannot move to its current position.";
            return false;
        }

        if (isset($board[$to])) {
            $_SESSION['error'] = "Invalid move: Destination occupied.";
            return false;
        }

        return Util::slide($board, $from, $to);
    }
}
