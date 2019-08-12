<?php


namespace App;

class Game
{
    /**
     * @var ChessBoard
     */
    private $chessBoard;
    /**
     * @var MovesRecording
     */
    private $movesRecording;
    private $player;
    private $strictPlayerSwitch;

    public function __construct(ChessBoard $chessBoard, bool $strictPlayerSwitch = false, ?MovesRecording $movesRecording = null)
    {
        $this->chessBoard = $chessBoard;
        if ($movesRecording === null) {
            $movesRecording  = new MovesRecording();
        }
        $this->movesRecording = $movesRecording;
        $this->player = 'white';
        $this->strictPlayerSwitch = $strictPlayerSwitch;
        $this->getChessBoard()->setMovesRecording($this->movesRecording);
    }

    // TODO
    // refacto colors with constants
    // render
    // mat/pat


    /** Move a piece (if it exists and if move is possible for this piece) from start coord to end coord
     * @param string $start
     * @param string $end
     * @return Game
     */
    public function gameMove(string $start, string $end): self
    {
        $piece = $this->getChessBoard()->getPiece($start);
        if (!$piece instanceof Piece) {
            throw new \LogicException('No piece at coordinate ' . $start);
        }

        $this->player = $piece->getColor();
        if ($this->movesRecording->last() && $this->strictPlayerSwitch && $this->movesRecording->last()[0]->getColor() === $piece->getColor()) {
            throw new \LogicException('It is not ' . $this->player . ' round');
        }

        if ($this->getChessBoard()->searchKing($this->player) && $this->checkIfInCheckAfterMove($piece, $start, $end)) {
            throw new \LogicException('Forbidden move (king in check)');
        }

        $this->getChessBoard()->addPiece($end, $piece);
        $this->getChessBoard()->setPiece($start, null);

        $this->movesRecording->record($piece, $start, $end);

        return $this;
    }

    /** Simulate if King of current player is in check after a potential move
     * It move the piece, check the king in check or not, and replace piece at the first test
     * If not in check, other checking about authorized case are made and piece if finally (or not) move to the end case
     * @param Piece $piece
     * @param string $start
     * @param string $end
     * @return bool
     */
    private function checkIfInCheckAfterMove(Piece $piece, string $start, string $end): bool
    {
        $currentEndPiece = $this->getChessBoard()->getPiece($end);
        $this->getChessBoard()->setPiece($end, $piece);
        $this->getChessBoard()->setPiece($start, null);
        $isInCheck = $this->isInCheckAfterMove();
        $this->getChessBoard()->setPiece($start, $piece);
        $this->getChessBoard()->setPiece($end, $currentEndPiece);

        return $isInCheck;
    }

    /** Get authorized cases of all pieces of inversed color. Return an array of case,
     * then we check if king of current player want to move in one of these not autorized cases
     * @param ChessBoard $chessBoard
     * @param MovesRecording $movesRecording
     * @return array
     */
    private function isInCheckAfterMove(): bool
    {
        $kingCoords = $this->getChessBoard()->searchKing($this->player);

        $allAuthorizedCases = [];
        for ($col = ChessBoard::ROW_START; $col <= ChessBoard::ROW_END; $col++) {
            for ($row = ChessBoard::ROW_START; $row <= ChessBoard::ROW_END; $row++) {
                $colLetter = ChessBoard::getColumns()[$col - 1];
                $piece = $this->getChessBoard()->getCases()[$colLetter][$row];
                if ($piece instanceof Piece && $piece->getColor() !== $this->player) {
                    $allAuthorizedCases = array_merge($allAuthorizedCases, $piece->authorizedCase($this->getChessBoard()));
                }
            }
        }

        return in_array($kingCoords, $allAuthorizedCases);
    }

    /**
     * @return ChessBoard
     */
    public function getChessBoard(): ChessBoard
    {
        return $this->chessBoard;
    }


    /** Roque special move between King and Tower (big or small)
     * @param string $type
     * @param string $color
     */
    public function roque(string $type = 'big', string $color = 'white'): void
    {
        $this->player = $color;
        $towerColumnStart = 'A';
        $towerColumnEnd = 'D';
        $kingColumnEnd = 'C';
        $row = ChessBoard::ROW_START;
        if ($type == 'small') {
            $towerColumnStart = 'H';
            $towerColumnEnd = 'F';
            $kingColumnEnd = 'G';
        }
        if ($this->player === 'black') {
            $row = ChessBoard::ROW_END;
        }
        $king = $this->getChessBoard()->getPiece('E' . $row);
        $tower = $this->getChessBoard()->getPiece($towerColumnStart . $row);

        if (!$king instanceof King || $king->getColor() !== $this->player ||
            !$tower instanceof Tower || $tower->getColor() !== $this->player) {
            throw new \LogicException('Pieces not in place for a roque');
        }

        // roque only possible if king or tower never move before
        foreach ($this->movesRecording->getMoves() as $move) {
            if (in_array($move[0], [$king, $tower])) {
                throw new \LogicException('King or Tower has already been moved, roque impossible');
            }
        }

        // roque only possible if case between start positions of tower and king are not "in check"
        $freeColumns = range($towerColumnEnd, $kingColumnEnd);
        $notFreeCases = array_filter($freeColumns, function ($col) use ($row, $king) {
            return !$this->getChessBoard()->isEmptyCase($col . $row) ||
                $this->checkIfInCheckAfterMove($king, 'E' . $row, $col . $row);
        });

        if (empty($notFreeCases)) {
            $this->getChessBoard()->setPiece($kingColumnEnd . $row, $king);
            $this->getChessBoard()->setPiece('E' . $row, null);
            $this->getChessBoard()->setPiece($towerColumnEnd . $row, $tower);
            $this->getChessBoard()->setPiece($towerColumnStart . $row, null);

            $this->movesRecording->record($king, 'E' . $row, $kingColumnEnd . $row);
        } else {
            throw new \LogicException('Roque impossible');
        }
    }

    /** Promote a pawn who reach opposite row in another piece
     * @param Pawn $pawn
     * @param string $pieceType
     */
    public function promote(Pawn $pawn, string $pieceType)
    {
        if (!in_array($pieceType, [Queen::class, Bishop::class, Tower::class, Knight::class])) {
            throw new \LogicException('Wrong piece for promotion');
        }
        $case = $this->getChessBoard()->searchPiece($pawn);
        $row = substr($case, 1, 1);
        if ($pawn->getColor() === 'white' && $row == 8 || $pawn->getColor() == 'black' && $row == 1) {
            $piece = new $pieceType($pawn->getColor());
            $this->getChessBoard()->setPiece($case, $piece);

            $this->movesRecording->record($piece, $case, $case);

        } else {
            throw new \LogicException('Impossible to promote this pawn');
        }
    }

    /**
     * @return MovesRecording
     */
    public function getMovesRecording(): MovesRecording
    {
        return $this->movesRecording;
    }


}