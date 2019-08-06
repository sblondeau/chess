<?php


namespace App;

class ChessBoardInitializer
{
    const CLASSIC_CHESSBOARD = [
        'A1' => [Tower::class, 'white'],
        'C1' => [Bishop::class, 'white'],
        'F1' => [Bishop::class, 'white'],
        'H1' => [Tower::class, 'white'],
        'A8' => [Tower::class, 'black'],
        'C8' => [Bishop::class, 'black'],
        'F8' => [Bishop::class, 'black'],
        'H8' => [Tower::class, 'black'],
    ];

    public static function initBoard(ChessBoard $chessboard): void
    {
        foreach ($chessboard::getColumns() as $column) {
            foreach ($chessboard::getRows() as $rowNb => $row) {
                $cases[$column][$row] = null;
            }
        }
        $chessboard->setCases($cases);
    }

    public static function initPieces(ChessBoard $chessboard, array $pieces) :void
    {
        foreach($pieces as $coord => $pieceType) {
            $piece = new $pieceType[0]($pieceType[1]);
            $chessboard->setPiece($coord, $piece);
        }
    }
}