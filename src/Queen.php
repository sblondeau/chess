<?php


namespace App;


class Queen extends Piece
{
    /**
     * @var string
     */
    const NAME = 'queen';
    const SYMBOL = ['white'=>'♕', 'black'=>'♛'];

    use LineTrait;
    use DiagonalTrait;

    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color, self::SYMBOL[$color]);
    }

    public function authorizedCase(ChessBoard $chessBoard): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));

        return array_merge(
                $this->checkRow($chessBoard, $colStart, $rowStart),
                $this->checkColumn($chessBoard, $colStart, $rowStart),
                $this->checkDiagonals($chessBoard, $colStart, $rowStart)
            ) ?? [];
    }

}