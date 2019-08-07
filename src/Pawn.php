<?php


namespace App;


class Pawn extends Piece
{
    /**
     * @var string
     */
    const NAME = 'pawn';


    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color);
        $this->color = $color;
    }

    public function authorizedCase(ChessBoard $chessBoard): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));
        $colStartNumber = array_search($colStart, ChessBoard::getColumns()) + 1;
        $colors = ['white' => 1, 'black' => -1];

        $simpleMoveCase = $colStart . ($rowStart + $colors[$this->getColor()]);
        if ($chessBoard->isEmptyCase($simpleMoveCase)) {
            $cases[] = $simpleMoveCase;
        }

        $doubleMoveCase = $colStart . ($rowStart + $colors[$this->getColor()] * 2);
        if (
            ($rowStart === 2 || $rowStart === 7) &&
            $chessBoard->isEmptyCase($simpleMoveCase) &&
            $chessBoard->isEmptyCase($doubleMoveCase)
        ) {
            $cases[] = $doubleMoveCase;
        }

        for ($i = -1; $i <= 1; $i += 2) {
            $diagonalCaseCoords = $chessBoard::getColumns()[$colStartNumber -1 + $i] . ($rowStart + $colors[$this->getColor()]);
            $diagonalPiece = $chessBoard->getPiece($diagonalCaseCoords);
            if ($diagonalPiece instanceof Piece && $colors[$diagonalPiece->getColor()] !== $this->getColor()) {
                $cases[] = $diagonalCaseCoords;
            }
        }

        return $cases ?? [];
    }

}