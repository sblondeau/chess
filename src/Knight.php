<?php


namespace App;


class Knight extends Piece
{
    /**
     * @var string
     */
    const NAME = 'knight';


    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color);
        $this->color = $color;
    }

    public function authorizedCase(ChessBoard $chessBoard): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));

        return [];
    }

}