<?php
namespace HiveGame\Features;

use HiveGame\Util\Util;

class Grasshopper {
    public static function isValidMove($board, $from, $to) {
        $fromCoords = explode(',', $from);
        $toCoords = explode(',', $to);

        // Ensure movement is in a straight line
        if ($fromCoords[0] != $toCoords[0] && $fromCoords[1] != $toCoords[1] && abs($fromCoords[0] - $toCoords[0]) != abs($fromCoords[1] - $toCoords[1])) {
            return false;
        }

        // Ensure the grasshopper moves to a different position
        if ($from === $to) {
            return false;
        }

        $deltaP = $toCoords[0] - $fromCoords[0];
        $deltaQ = $toCoords[1] - $fromCoords[1];

        // Normalize deltas to a unit direction
        $dirP = $deltaP !== 0 ? $deltaP / abs($deltaP) : 0;
        $dirQ = $deltaQ !== 0 ? $deltaQ / abs($deltaQ) : 0;

        $currentP = $fromCoords[0] + $dirP;
        $currentQ = $fromCoords[1] + $dirQ;
        $jumped = false;

        // Traverse the path and ensure the Grasshopper jumps over at least one tile
        while ("$currentP,$currentQ" !== $to) {
            if (!isset($board["$currentP,$currentQ"])) {
                return false;
            }
            $jumped = true;
            $currentP += $dirP;
            $currentQ += $dirQ;
        }

        // Ensure the Grasshopper doesn't land on an occupied space
        if (isset($board[$to]) && !empty($board[$to])) {
            return false;
        }

        return $jumped;
    }
}
