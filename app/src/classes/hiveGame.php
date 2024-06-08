<?php
namespace App\classes;

use App\database\Database;
use Exception;

class HiveGame {
    public function play($piece, $to) {
        $board = $_SESSION['board'];
        $player = $_SESSION['player'];
        $hand = &$_SESSION['hand'];

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

    public function move($from, $to) {
        $board = $_SESSION['board'];
        error_log("Move Debug: Attempting to move from $from to $to.");
        if (Util::slide($board, $from, $to)) {
            if (!isset($board[$to])) {
                $board[$to] = [];
            }
            $board[$to][] = array_pop($board[$from]);
            $_SESSION['board'] = $board;

            $this->recordMoveWithFrom('move', $to, $board, $from);

            $_SESSION['player'] = 1 - $_SESSION['player'];
            $_SESSION['message'] = "Move successful.";
        } else {
            $_SESSION['error'] = "Invalid move: Slide not possible.";
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

        try {
            $db = Database::getConnection();
            $db->query('DELETE FROM moves WHERE game_id = ' . $_SESSION['game_id']);
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }
}
