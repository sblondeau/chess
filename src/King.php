<?php


namespace App;


class King extends Piece
{
    /**
     * @var string
     */
    const NAME = 'king';
    const SYMBOL = ['white'=>'♔', 'black'=>'♚'];

    use LineTrait;
    use DiagonalTrait;

    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color, self::SYMBOL[$color]);
    }

    public function authorizedCase(ChessBoard $chessBoard): array
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