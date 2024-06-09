<?php
namespace App\features;

class Spider {
    public function canMove($from, $to, $board) {
        $visited = [];
        return $this->canMoveRecursive($from, $to, $board, 3, $visited);
    }

    private function canMoveRecursive($current, $target, $board, $stepsLeft, &$visited) {
        if ($stepsLeft == 0) {
            return $current === $target;
        }

        $neighbors = Util::getNeighbors($current);
        foreach ($neighbors as $neighbor) {
            if (!isset($board[$neighbor]) && Util::hasNeighbour($neighbor, $board) && !in_array($neighbor, $visited)) {
                $visited[] = $neighbor;
                if ($this->canMoveRecursive($neighbor, $target, $board, $stepsLeft - 1, $visited)) {
                    return true;
                }
                array_pop($visited); // backtrack
            }
        }

        return false;
    }
}
