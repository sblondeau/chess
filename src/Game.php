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
        $this->chessBoard->setMovesRecording($this->movesRecording);
    }

    // TODO
    // promotion
    // refacto colors with constants
    // render


    public function gameMove(string $start, string $end): self
    {
        $piece = $this->chessBoard->getPiece($start);
        if (!$piece instanceof Piece) {
            throw new \LogicException('No piece at coordinate ' . $start);
        }

        $this->player = $piece->getColor();

        if ($this->chessBoard->searchKing($this->player) && $this->checkIfInCheckAfterMove($piece, $start, $end)) {
            throw new \LogicException('Forbidden move (king in check)');
        }
        $this->chessBoard->addPiece($end, $piece);
        $this->chessBoard->setPiece($start, null);

        $this->movesRecording->record($piece, $start, $end);

        return $this;
    }

    private function checkIfInCheckAfterMove(Piece $piece, string $start, string $end) :bool
    {
        $currentEndPiece = $this->chessBoard->getPiece($end);
        $this->chessBoard->setPiece($end, $piece);
        $this->chessBoard->setPiece($start, null);
        $isInCheck = $this->isInCheckAfterMove();
        $this->chessBoard->setPiece($start, $piece);
        $this->chessBoard->setPiece($end, $currentEndPiece);
        return $isInCheck;
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
                        $cases = $piece->kingCaseIgnoringInCheck($this->chessBoard);
                    } else {
                        $cases = $piece->authorizedCase($this->chessBoard);
                    }
                    $allAuthorizedCases = array_merge($allAuthorizedCases, $cases);
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

    /**
     * @param ChessBoard $chessBoard
     * @return Game
     */
    public function setChessBoard(ChessBoard $chessBoard): Game
    {
        $this->chessBoard = $chessBoard;

        return $this;
    }

    public function roque(string $type = 'big', string $color = 'white') :void
    {
        $this->player = $color;
        $towerColumnStart = 'A';
        $towerColumnEnd = 'D';
        $kingColumnEnd = 'C';
        $row = 1;
        if ($type == 'small') {
            $towerColumnStart = 'H';
            $towerColumnEnd = 'F';
            $kingColumnEnd = 'G';
        }
        if ($this->player === 'black') {
            $row = 8;
        }
        $king = $this->chessBoard->getPiece('E' . $row);
        $tower = $this->chessBoard->getPiece($towerColumnStart . $row);

        if (!$king instanceof King || $king->getColor() !== $this->player ||
        !$tower instanceof Tower || $tower->getColor() !== $this->player) {
            throw new \LogicException('Pieces not in place for a roque');
        }

        foreach($this->movesRecording->getMoves() as $move) {
            if (in_array($move[0], [$king, $tower])) {
                throw new \LogicException('King or Tower has already been moved, roque impossible');
            }
        }

        $freeColumns = range ($towerColumnEnd, $kingColumnEnd);
        $notFreeCases = array_filter($freeColumns, function($col) use ($row, $king) {
            return !$this->chessBoard->isEmptyCase($col.$row) ||
                $this->checkIfInCheckAfterMove($king, 'E'.$row, $col.$row);
        });

        if (empty($notFreeCases)) {

            $this->chessBoard->setPiece($kingColumnEnd . $row, $king);
            $this->chessBoard->setPiece('E' . $row, null);
            $this->chessBoard->setPiece($towerColumnEnd . $row, $tower);
            $this->chessBoard->setPiece($towerColumnStart . $row, null);
        } else {
            throw new \LogicException('Roque impossible');
        }
    }


}