<?php

namespace HiveGame\Features;

use HiveGame\Util\Util;

class SoldierAnt {
    public static function isValidMove($board, $from, $to) {
        if ($from === $to) {
            return false;
        }
        $path = self::findPath($board, $from, $to);

        if (empty($path)) {
            return false;
        }

        foreach ($path as $step) {
            if (!Util::isSlideMove($board, $from, Util::coordsToString($step))) {
                return false;
            }
            $from = Util::coordsToString($step);
        }

        return true;
    }

    private static function findPath($board, $from, $to) {
        $fromCoords = explode(',', $from);
        $toCoords = explode(',', $to);
        $fromCoords = array_map('intval', $fromCoords);
        $toCoords = array_map('intval', $toCoords);
        $queue = [[$fromCoords]];
        $visited = [$from => true];
        $maxPathLength = 100;

        while (!empty($queue)) {
            $path = array_shift($queue);
            $current = end($path);
            $currentStr = $current[0] . ',' . $current[1];

            if ($currentStr === $to) {
                return $path;
            }

            foreach (Util::$OFFSETS as $offset) {
                $neighborCoords = [$current[0] + $offset[0], $current[1] + $offset[1]];
                $neighbor = $neighborCoords[0] . ',' . $neighborCoords[1];
                if (self::isValidPosition($neighborCoords, $board) && !isset($visited[$neighbor])) {
                    $visited[$neighbor] = true;
                    $newPath = $path;
                    $newPath[] = $neighborCoords;
                    if (count($newPath) <= $maxPathLength) {
                        $queue[] = $newPath;
                    }
                }
            }
        }

        return [];
    }

    private static function isValidPosition($coords, $board) {
        $pos = $coords[0] . ',' . $coords[1];
        return isset($board[$pos]) && empty($board[$pos]);
    }
}
