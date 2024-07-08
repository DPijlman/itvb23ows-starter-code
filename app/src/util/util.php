<?php
namespace HiveGame\Util;

use HiveGame\Features\Grasshopper;

class Util {
    public static $OFFSETS = [
        [0, 1], [1, 0], [1, -1], [0, -1], [-1, 0], [-1, 1]
    ];

    public static function hasNeighbour($a, $board) {
        foreach (array_keys($board) as $b) {
            if (self::isNeighbour($a, $b)) return true;
        }
        return false;
    }

    public static function isNeighbour($a, $b) {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) return true;
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) return true;
        if (abs($a[0] - $b[0]) == 1 && abs($a[1] - $b[1]) == 1) return true;
        return false;
    }

    public static function slide($board, $from, $to) {
        if (!isset($board[$from])) {
            return false;
        }

        if (isset($board[$to]) && !empty($board[$to])) {
            return false;
        }

        if (!self::isNeighbour($from, $to)) {
            return false;
        }

        $piece = array_pop($board[$from]);
        $board[$to][] = $piece;

        if (empty($board[$from])) {
            unset($board[$from]);
        }

        return $board;
    }

    public static function isValidGrasshopperMove($board, $from, $to) {
        return Grasshopper::isValidMove($board, $from, $to);
    }
}
