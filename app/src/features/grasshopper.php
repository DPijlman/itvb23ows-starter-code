<?php
namespace App\features;

class Grasshopper
{
    public function canMove($from, $to, $board)
    {
        list($fromX, $fromY) = explode(',', $from);
        list($toX, $toY) = explode(',', $to);

        if ($fromX == $toX && $fromY == $toY) {
            $_SESSION['error'] = "Invalid move: Grasshopper cannot move to its current position.";
            return false;
        }

        if (isset($board[$to])) {
            $_SESSION['error'] = "Invalid move: Destination occupied.";
            return false;
        }

        $dx = $toX - $fromX;
        $dy = $toY - $fromY;

        if (($dx != 0 && $dy != 0) || ($dx == 0 && $dy == 0)) {
            $_SESSION['error'] = "Invalid move: Grasshopper must move in a straight line.";
            return false;
        }

        $stepX = ($dx != 0) ? $dx / abs($dx) : 0;
        $stepY = ($dy != 0) ? $dy / abs($dy) : 0;

        $x = $fromX + $stepX;
        $y = $fromY + $stepY;
        $jumped = false;

        while ($x != $toX || $y != $toY) {
            $pos = "$x,$y";
            if (!isset($board[$pos])) {
                $_SESSION['error'] = "Invalid move: Grasshopper must jump over at least one tile.";
                return false;
            }
            $x += $stepX;
            $y += $stepY;
            $jumped = true;
        }

        return $jumped;
    }
}
