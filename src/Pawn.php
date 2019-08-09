<?php


namespace App;


class Pawn extends Piece
{
    /**
     * @var string
     */
    const NAME = 'pawn';
    const COLORS = ['white' => 1, 'black' => -1];


    public function __construct(string $color)
    {
        parent::__construct(self::NAME, $color);
        $this->color = $color;
    }

    public function authorizedCase(ChessBoard $chessBoard, MovesRecording $movesRecording): array
    {
        [$colStart, $rowStart] = $chessBoard::checkCoordinate($chessBoard->searchPiece($this));
        $colStartNumber = array_search($colStart, ChessBoard::getColumns()) + 1;

        $simpleMoveCase = $colStart . ($rowStart + self::COLORS[$this->getColor()]);
        if ($chessBoard->isEmptyCase($simpleMoveCase)) {
            $cases[] = $simpleMoveCase;
        }

        $doubleMoveCase = $colStart . ($rowStart + self::COLORS[$this->getColor()] * 2);
        if (
            ($rowStart === 2 || $rowStart === 7) &&
            $chessBoard->isEmptyCase($simpleMoveCase) &&
            $chessBoard->isEmptyCase($doubleMoveCase)
        ) {
            $cases[] = $doubleMoveCase;
        }

        $diagonalCases = $this->diagonalCatch($chessBoard, $colStartNumber, $rowStart, $movesRecording);

        $enPassantCase = $this->enPassant($chessBoard, $colStart . $rowStart, $movesRecording);

        $cases = array_merge($cases ?? [], $diagonalCases, $enPassantCase);

        return $cases ?? [];
    }

    private function diagonalCatch(ChessBoard $chessBoard, int $colStartNumber, int $rowStart, MovesRecording $movesRecording)
    {
        for ($i = -1; $i <= 1; $i += 2) {
            $diagonalCaseCoords = (ChessBoard::getColumns()[$colStartNumber - 1 + $i] ?? '') . ($rowStart + self::COLORS[$this->getColor()]);
            if (ChessBoard::caseExists($diagonalCaseCoords)) {
                $diagonalPiece = $chessBoard->getPiece($diagonalCaseCoords);
                if ($diagonalPiece instanceof Piece && self::COLORS[$diagonalPiece->getColor()] !== $this->getColor()) {
                    $cases[] = $diagonalCaseCoords;
                }
            }
        }

        return $cases ?? [];
    }

    private function enPassant(ChessBoard $chessBoard, string $case, MovesRecording $movesRecording)
    {
        $lastMove = $movesRecording->last();
        if ($lastMove && $lastMove[0] instanceof Pawn) {
            $rightCase = ChessBoard::getNextCase($lastMove[2], 'right');
            $leftCase = ChessBoard::getNextCase($lastMove[2], 'left');
            $rightCasePawn = $leftCasePawn = null;
            if ($rightCase !== null) {
                $rightCasePawn = $chessBoard->getPiece($rightCase);
            }
            if ($leftCase !== null) {
                $leftCasePawn = $chessBoard->getPiece($leftCase);
            }

            $lastMoveStartRow = substr($lastMove[1], 1, 1);
            $lastMoveEndRow = substr($lastMove[2], 1, 1);
            // if the last move is a move of two case of a Pawn
            // and there is a Pawn===$this in the left or right case
            // so, catch "en passant" is possible
            // (no need to check color or position of $this and last move because color of last move !== $this)
            if (abs($lastMoveStartRow - $lastMoveEndRow) === 2 &&
                ($rightCasePawn === $this || $leftCasePawn === $this)
            ) {
                $row = $lastMoveStartRow - (($lastMoveStartRow - $lastMoveEndRow) / 2);
                $col = substr($lastMove[2], 0, 1);
                $cases[] = $col . $row;
            }
        }

        return $cases ?? [];
    }
}