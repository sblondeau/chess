<?php


namespace App;


use SebastianBergmann\CodeCoverage\TestFixture\C;

class Game
{
    private $chessBoard;
    private $movesRecording;
    private $player;

    public function __construct(ChessBoard $chessBoard)
    {
        $this->chessBoard = $chessBoard;
        $this->movesRecording = new MovesRecording();
        $this->player = 'white';
        // TODO $this->chessBoard->setMoveRecording($this->moveRecording);
    }

    // TODO
    // roque, promotion

    public function gameMove(string $start, string $end) :self
    {
        $piece = $this->chessBoard->getPiece($start);
        if(!$piece instanceof Piece) {
            throw new \LogicException('No piece at coordinate ' . $start);
        }

        $this->player = $piece->getColor();

        if($this->chessBoard->searchKing($this->player) && $this->checkIfInCheckAfterMove($piece, $start, $end)) {
            throw new \LogicException('Forbidden move (king in check)');
        }
        $this->chessBoard->addPiece($end, $piece, $this->movesRecording);
        $this->chessBoard->setPiece($start, null);

        $this->movesRecording->record($piece, $start, $end);

        return $this;
    }

    /**
     * @return ChessBoard
     */
    public function getChessBoard(): ChessBoard
    {
        return $this->chessBoard;
    }

    /**
     * @param ChessBoard $chessBoard
     * @return Game
     */
    public function setChessBoard(ChessBoard $chessBoard): Game
    {
        $this->chessBoard = $chessBoard;

        return $this;
    }

    /** Get authorized cases of all pieces of inversed color (but for opposite king, just the case around to avoir infinite loop)
     * @param ChessBoard $chessBoard
     * @param MovesRecording $movesRecording
     * @return array
     */
    private function isInCheckAfterMove(): bool
    {
        $kingCoords = $this->chessBoard->searchKing($this->player);

        $allAuthorizedCases = [];
        for ($col = ChessBoard::ROW_START; $col <= ChessBoard::ROW_END; $col++) {
            for ($row = ChessBoard::ROW_START; $row <= ChessBoard::ROW_END; $row++) {
                $colLetter = ChessBoard::getColumns()[$col - 1];
                $piece = $this->chessBoard->getCases()[$colLetter][$row];
                if ($piece instanceof Piece && $piece->getColor() !== $this->player) {
                    if ($piece instanceof King) {
                        $allAuthorizedCases = array_merge($allAuthorizedCases, $piece->kingCaseIgnoringInCheck($this->chessBoard));
                    } else {
                        $allAuthorizedCases = array_merge($allAuthorizedCases, $piece->authorizedCase($this->chessBoard, $this->movesRecording));
                    }
                }
            }
        }

        return in_array($kingCoords, $allAuthorizedCases);
    }

    private function checkIfInCheckAfterMove(Piece $piece, string $start, string $end)
    {
        $currentEndPiece = $this->chessBoard->getPiece($end);
        $this->chessBoard->setPiece($end, $piece);
        $this->chessBoard->setPiece($start, null);
        $isInCheck = $this->isInCheckAfterMove();
        $this->chessBoard->setPiece($start, $piece);
        $this->chessBoard->setPiece($end, $currentEndPiece);

        return $isInCheck;
    }

}