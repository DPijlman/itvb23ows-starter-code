<?php
namespace App\classes;

use App\database\Database;
use Exception;

class HiveGame {
    public function play($piece, $to) {
        $board = $_SESSION['board'];
        $player = $_SESSION['player'];

        if (empty($board)) {
            $board[$to] = [[$player, $piece]];
            $_SESSION['board'] = $board;
            
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_to, state) VALUES (?, ?, ?, ?)');
                $state = json_encode($board);
                $stmt->bind_param('isss', $_SESSION['game_id'], $piece, $to, $state);
                $stmt->execute();
            } catch (Exception $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
                return;
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

                try {
                    $db = Database::getConnection();
                    $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_to, state) VALUES (?, ?, ?, ?)');
                    $state = json_encode($board);
                    $stmt->bind_param('isss', $_SESSION['game_id'], $piece, $to, $state);
                    $stmt->execute();
                } catch (Exception $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                    return;
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
        if (Util::slide($board, $from, $to)) {
            $board[$to][] = array_pop($board[$from]);
            $_SESSION['board'] = $board;

            try {
                $db = Database::getConnection();
                $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_from, move_to, state) VALUES (?, ?, ?, ?, ?)');
                $state = json_encode($board);
                $stmt->bind_param('issss', $_SESSION['game_id'], 'move', $from, $to, $state);
                $stmt->execute();
            } catch (Exception $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
                return;
            }

            $_SESSION['player'] = 1 - $_SESSION['player'];
        } else {
            $_SESSION['error'] = "Invalid move: Slide not possible.";
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
            $db->prepare('INSERT INTO games () VALUES ()')->execute();
            $_SESSION['game_id'] = $db->insert_id;
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    }
}
