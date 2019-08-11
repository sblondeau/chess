<?php


namespace App;


class King extends Piece
{
    /**
     * @var string
     */
    const NAME = 'king';

    use LineTrait;
    use DiagonalTrait;

    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color);
        $this->color = $color;
    }

    public function authorizedCase(ChessBoard $chessBoard): array
    {
        $cases = $this->kingCaseIgnoringInCheck($chessBoard);
        return $cases ?? [];
    }


    /** Possibility to get "only" available cases around a king (ignoring forbidden case involving in "in check") to avoid recursive checking between the two king
     * i
     * @param ChessBoard $chessBoard
     * @return array
     */
    public function kingCaseIgnoringInCheck(ChessBoard $chessBoard)
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));
        $colStartNumber = array_search($colStart, ChessBoard::getColumns()) + 1;
        for ($i = -1; $i <= 1; $i++) {
            $col = $colStartNumber + $i;
            for ($j = -1; $j <= 1; $j++) {
                $cases[] = (ChessBoard::getColumns()[$col - 1] ?? '') . ($rowStart + $j);
            }
        }

        return $cases ?? [];
    }



}