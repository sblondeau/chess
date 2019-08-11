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
        $colStartNumber = array_search($colStart, ChessBoard::getColumns()) + 1;

        for ($i = -2; $i <= 2; $i++) {
            if ($i !== 0) {
                $row = $rowStart + $i;

                for ($multiplier = -1; $multiplier <= 1; $multiplier += 2) {
                    $col = $colStartNumber + (3 - abs($i)) * $multiplier;
                    $cases[] = (ChessBoard::getColumns()[$col - 1] ?? '') . $row;
                }
            }
        }

        return $cases ?? [];
    }

}