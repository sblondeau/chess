<?php


namespace App;


class Tower extends Piece
{
    /**
     * @var string
     */
    const NAME = 'tower';

    use LineTrait;

    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color);
        $this->color = $color;
    }

    public function authorizedCase(ChessBoard $chessBoard): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));

        return array_merge(
            $this->checkRow($chessBoard, $colStart, $rowStart),
            $this->checkColumn($chessBoard, $colStart, $rowStart)
            ) ?? [];
    }

}