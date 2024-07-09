<?php
namespace HiveGame\Features;

use HiveGame\Util\Util;

class Spider {
    public static function isValidMove($board, $from, $to) {
        if ($from === $to) {
            return false;
        }

        $visited = [];
        return self::canMove($board, $from, $to, 3, $visited);
    }

    private static function canMove($board, $current, $destination, $stepsRemaining, $visited) {
        if ($stepsRemaining === 0) {
            return $current === $destination;
        }

        $visited[$current] = true;
        $neighbors = self::getValidNeighbors($board, $current, $visited);

        foreach ($neighbors as $neighbor) {
            if (self::canMove($board, $neighbor, $destination, $stepsRemaining - 1, $visited)) {
                return true;
            }
        }

        unset($visited[$current]);
        return false;
    }

    private static function getValidNeighbors($board, $current, $visited) {
        $currentCoords = explode(',', $current);
        $neighbors = [];

        foreach (Util::$OFFSETS as $offset) {
            $neighborCoords = [
                $currentCoords[0] + $offset[0],
                $currentCoords[1] + $offset[1]
            ];
            $neighbor = implode(',', $neighborCoords);

            if (!isset($board[$neighbor]) && !isset($visited[$neighbor]) && Util::isNeighbour($current, $neighbor)) {
                $neighbors[] = $neighbor;
            }
        }

        return $neighbors;
    }
}
