<?php


namespace App;

class ChessBoardInitializer
{
    const CLASSIC_CHESSBOARD = [
        'A1' => [Tower::class, 'white'],
        'B1' => [Knight::class, 'white'],
        'C1' => [Bishop::class, 'white'],
        'D1' => [Queen::class, 'white'],
        'E1' => [King::class, 'white'],
        'F1' => [Bishop::class, 'white'],
        'G1' => [Knight::class, 'white'],
        'H1' => [Tower::class, 'white'],
        'A2' => [Pawn::class, 'white'],
        'B2' => [Pawn::class, 'white'],
        'C2' => [Pawn::class, 'white'],
        'D2' => [Pawn::class, 'white'],
        'E2' => [Pawn::class, 'white'],
        'F2' => [Pawn::class, 'white'],
        'G2' => [Pawn::class, 'white'],
        'H2' => [Pawn::class, 'white'],
        'A8' => [Tower::class, 'black'],
        'B8' => [Knight::class, 'black'],
        'C8' => [Bishop::class, 'black'],
        'D8' => [Queen::class, 'black'],
        'E8' => [King::class, 'black'],
        'F8' => [Bishop::class, 'black'],
        'G8' => [Knight::class, 'black'],
        'H8' => [Tower::class, 'black'],
        'A7' => [Pawn::class, 'black'],
        'B7' => [Pawn::class, 'black'],
        'C7' => [Pawn::class, 'black'],
        'D7' => [Pawn::class, 'black'],
        'E7' => [Pawn::class, 'black'],
        'F7' => [Pawn::class, 'black'],
        'G7' => [Pawn::class, 'black'],
        'H7' => [Pawn::class, 'black'],

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