<?php
namespace App\classes;

use App\database\Database;
use App\features\Grasshopper;
use App\features\SoldierAnt;
use Exception;

class HiveGame {
    public function play($piece, $to) {
        $board = $_SESSION['board'];
        $player = $_SESSION['player'];
        $hand = &$_SESSION['hand'];

        if (!isset($_SESSION['turns'])) {
            $_SESSION['turns'] = [0, 0];
        }

        $_SESSION['turns'][$player]++;
        $totalTurns = array_sum($_SESSION['turns']);
        $queenNotPlayed = !isset($hand[$player]['Q']) || $hand[$player]['Q'] > 0;
        if ($_SESSION['turns'][$player] >= 4 && $queenNotPlayed) {
            if ($piece !== 'Q') {
                $_SESSION['error'] = "You must play your Queen Bee by the 4th turn.";
                return;
            }
        }

        if (empty($board)) {
            $board[$to] = [[$player, $piece]];
            $_SESSION['board'] = $board;
            $this->recordMove($piece, $to, $board);

            $hand[$player][$piece]--;
            if ($hand[$player][$piece] == 0) {
                unset($hand[$player][$piece]);
            }

            $_SESSION['player'] = 1 - $player;
            $_SESSION['message'] = "Move successful.";
        } else {
            $hasNeighbour = Util::hasNeighbour($to, $board);

            if ($hasNeighbour) {
                if (!isset($board[$to])) {
                    $board[$to] = [];
                }
                $board[$to][] = [$player, $piece];
                $_SESSION['board'] = $board;
                $this->recordMove($piece, $to, $board);

                $hand[$player][$piece]--;
                if ($hand[$player][$piece] == 0) {
                    unset($hand[$player][$piece]);
                }

                $_SESSION['player'] = 1 - $player;
                $_SESSION['message'] = "Move successful.";
            } else {
                $_SESSION['error'] = "Invalid move: No neighboring pieces.";
            }
        }
    }

    public function move($from, $to) {
        $board = $_SESSION['board'];
        $piece = end($board[$from]);

        if ($piece[1] == 'G') {
            $grasshopper = new Grasshopper();
            if ($grasshopper->canMove($from, $to, $board)) {
                array_pop($board[$from]);
                if (empty($board[$from])) {
                    unset($board[$from]);
                }
                if (!isset($board[$to])) {
                    $board[$to] = [];
                }
                $board[$to][] = $piece;
                $_SESSION['board'] = $board;

                $this->recordMoveWithFrom('move', $to, $board, $from);

                $_SESSION['player'] = 1 - $_SESSION['player'];
                return true;
            } else {
                return false;
            }
        }

        if ($piece[1] == 'A') {
            $soldierAnt = new SoldierAnt();
            if ($soldierAnt->canMove($from, $to, $board)) {
                array_pop($board[$from]);
                if (empty($board[$from])) {
                    unset($board[$from]);
                }
                if (!isset($board[$to])) {
                    $board[$to] = [];
                }
                $board[$to][] = $piece;
                $_SESSION['board'] = $board;

                $this->recordMoveWithFrom('move', $to, $board, $from);

                $_SESSION['player'] = 1 - $_SESSION['player'];
                return true;
            } else {
                return false;
            }
        }

        if (Util::slide($board, $from, $to)) {
            array_pop($board[$from]);
            if (empty($board[$from])) {
                unset($board[$from]);
            }
            if (!isset($board[$to])) {
                $board[$to] = [];
            }
            $board[$to][] = $piece;
            $_SESSION['board'] = $board;

            $this->recordMoveWithFrom('move', $to, $board, $from);

            $_SESSION['player'] = 1 - $_SESSION['player'];
            return true;
        } else {
            $_SESSION['error'] = "Invalid move: Slide not possible.";
            return false;
        }
    }

    private function recordMove($piece, $to, $board) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_to, state) VALUES (?, ?, ?, ?)');
            $state = json_encode($board);
            $stmt->bind_param('isss', $_SESSION['game_id'], $piece, $to, $state);
            $stmt->execute();
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }

    private function recordMoveWithFrom($type, $to, $board, $from) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_from, move_to, state) VALUES (?, ?, ?, ?, ?)');
            $state = json_encode($board);
            $stmt->bind_param('issss', $_SESSION['game_id'], $type, $from, $to, $state);
            $stmt->execute();
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }

    public function restart() {
        $_SESSION['board'] = [];
        $_SESSION['hand'] = [
            0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
            1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
        ];
        $_SESSION['player'] = 0;
        $_SESSION['turns'] = [0, 0];

        try {
            $db = Database::getConnection();
            $db->prepare('INSERT INTO games () VALUES ()')->execute();
            $_SESSION['game_id'] = $db->insert_id;
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }
}
