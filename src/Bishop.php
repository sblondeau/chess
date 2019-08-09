<?php


namespace App;


class Bishop extends Piece
{
    /**
     * @var string
     */
    const NAME = 'bishop';

    use DiagonalTrait;

    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color);
        $this->color = $color;
    }

    public function authorizedCase(ChessBoard $chessBoard, MovesRecording $movesRecording): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));
        $cases = $this->checkDiagonals($chessBoard, $colStart, $rowStart);

        return $cases;
    }

}