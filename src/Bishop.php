<?php


namespace App;


class Bishop extends Piece
{
    /**
     * @var string
     */
    const NAME = 'bishop';
    const SYMBOL = ['white'=>'♗', 'black'=>'♝'];

    use DiagonalTrait;

    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color, self::SYMBOL[$color]);
    }

    public function authorizedCase(ChessBoard $chessBoard): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));
        $cases = $this->checkDiagonals($chessBoard, $colStart, $rowStart);

        return $cases;
    }

}