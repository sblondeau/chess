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

        $cases[] = $colStart . ($rowStart+1);
        return $cases ?? [];
    }

}