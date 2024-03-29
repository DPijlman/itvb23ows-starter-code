<?php

function validPlacementCheck($position, $board) {
    $coordinates = explode(',', $position);
    $x = intval($coordinates[0]);
    $y = intval($coordinates[1]);

    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $adjX = $x + $pq[0];
        $adjY = $y + $pq[1];
        $adjacentPosition = $adjX . ',' . $adjY;

        if (isset($board[$adjacentPosition])) {
            return true;
        }
    }

    return false;
}

function getPossiblePlacements($board) {
    $possiblePlacements = [];

    foreach (array_keys($board) as $pos) {
        $coordinates = explode(',', $pos);
        $x = intval($coordinates[0]);
        $y = intval($coordinates[1]);

        foreach ($GLOBALS['OFFSETS'] as $pq) {
            $adjX = $x + $pq[0];
            $adjY = $y + $pq[1];
            $adjacentPosition = $adjX . ',' . $adjY;

            if (validPlacementCheck($adjacentPosition, $board)) {
                $possiblePlacements[] = $adjacentPosition;
            }
        }
    }

    if (empty($possiblePlacements)) {
        $possiblePlacements[] = '0,0';
    }

    return $possiblePlacements;
}
