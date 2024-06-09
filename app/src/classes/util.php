<?php
namespace App\classes;

class Util {
    public static $OFFSETS = [
        [0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]
    ];

    public static function isNeighbour($a, $b) {
        $a = explode(',', $a);
        $b = explode(',', $b);
        return abs($a[0] - $b[0]) <= 1 && abs($a[1] - $b[1]) <= 1 && ($a[0] != $b[0] || $a[1] != $b[1]);
    }

    public static function hasNeighbour($a, $board) {
        foreach (array_keys($board) as $b) {
            if (self::isNeighbour($a, $b)) return true;
        }
        return false;
    }

    public static function len($tile) {
        return $tile ? count($tile) : 0;
    }

    public static function slide($board, $from, $to) {
        if (!self::hasNeighbour($to, $board)) {
            error_log("Slide Debug: Destination $to has no neighbours.");
            return false;
        }
        if (!self::isNeighbour($from, $to)) {
            error_log("Slide Debug: $from and $to are not neighbours.");
            return false;
        }

        if (self::breaksHive($board, $from)) {
            error_log("Slide Debug: Moving from $from to $to would break the hive structure.");
            return false;
        }

        return self::canSlide($board, $from, $to);
    }

    private static function getNeighbours($pos, $board) {
        $neighbours = [];
        $p = explode(',', $pos);
        foreach (self::$OFFSETS as $offset) {
            $neighbour = ($p[0] + $offset[0]) . ',' . ($p[1] + $offset[1]);
            if (isset($board[$neighbour])) {
                $neighbours[] = $neighbour;
            }
        }
        error_log("Slide Debug: Neighbours of $pos: " . implode(', ', $neighbours));
        return $neighbours;
    }

    private static function canSlide($board, $from, $to) {
        $fromNeighbours = self::getNeighbours($from, $board);
        $toNeighbours = self::getNeighbours($to, $board);

        if (count($fromNeighbours) < 2 || count($toNeighbours) < 2) {
            error_log("Slide Debug: Not enough neighbours to slide.");
            return true;
        }

        $fromPos = explode(',', $from);
        $toPos = explode(',', $to);
        $deltaP = $toPos[0] - $fromPos[0];
        $deltaQ = $toPos[1] - $fromPos[1];

        if ($deltaP == 0 || $deltaQ == 0 || abs($deltaP) == abs($deltaQ)) {
            return true;
        }

        return false;
    }

    private static function breaksHive($board, $from) {
        $visited = [];
        $toVisit = [$from];
        while ($toVisit) {
            $current = array_pop($toVisit);
            if (isset($visited[$current])) {
                continue;
            }
            $visited[$current] = true;
            $currentNeighbours = self::getNeighbours($current, $board);
            foreach ($currentNeighbours as $neighbour) {
                if (!isset($visited[$neighbour]) && $neighbour != $from) {
                    $toVisit[] = $neighbour;
                }
            }
        }

        foreach (self::getNeighbours($from, $board) as $neighbour) {
            if (!isset($visited[$neighbour])) {
                return true;
            }
        }

        return false;
    }
}