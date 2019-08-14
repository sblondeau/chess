<?php

use App\ChessBoard;
use App\ChessBoardInitializer;
use App\Game;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require '../vendor/autoload.php';


session_start();
$movesRecording = $_SESSION['movesRecording'] ?? new \App\MovesRecording();

if (isset($_GET['reset'])) {
    session_destroy();
}

$loader = new FilesystemLoader('../src/View');
$twig = new Environment($loader, [
    'cache' => false,
]);

$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

$chessboard = new ChessBoard($_SESSION['chessboard'] ?? ChessBoardInitializer::CLASSIC_CHESSBOARD);

$game = new Game($chessboard, true, $movesRecording);

try {
    if ($start && $end) {
        $game->gameMove($start, $end);
        unset($start);
        unset($end);
    }
    if (isset($_GET['roque'])) {
        $game->roque($type = $_GET['roque'], $game->getRound());
    }
    if (isset($_GET['promote'])) {
        $game->promote($game->getChessBoard()->getPiece($game->readyToPromote()), $_GET['promote']);
    }
} catch (LogicException $exception) {
    $error = $exception->getMessage();
    unset($start);
    unset($end);
}

$_SESSION['chessboard'] = ChessBoardInitializer::createInitFromPieces($chessboard->render());
$_SESSION['movesRecording'] = $game->getMovesRecording();

echo $twig->render('index.html.twig', [
        'error'          => $error ?? '',
        'start'          => $start ?? null,
        'movesRecording' => $_SESSION['movesRecording'],
        'chessboard'     => $chessboard->render(),
        'round'          => $game->getRound(),
        'promotion'          => $game->readyToPromote(),
    ]
);

