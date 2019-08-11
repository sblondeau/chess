<?php


namespace App;


class ChessBoard
{
    const COLORS = ['white', 'black'];
    const COLUMN_START = 'A';
    const COLUMN_END = 'H';
    const ROW_START = 1;
    const ROW_END = 8;

    private $cases;
    private $movesRecording;

    public function __construct(array $pieces = ChessBoardInitializer::CLASSIC_CHESSBOARD)
    {
        ChessBoardInitializer::initBoard($this);
        ChessBoardInitializer::initPieces($this, $pieces);
    }

    public function setMovesRecording(MovesRecording $movesRecording) {
        $this->movesRecording = $movesRecording;
    }

    /**
     * @return mixed
     */
    public function getMovesRecording()
    {
        return $this->movesRecording;
    }


    public static function getNextCase(string $coords, string $direction): ?string
    {
        if (!in_array($direction, ['up', 'down', 'left', 'right'])) {
            throw new \LogicException('This direction is not allowed, accepted directions are "up", "down", "left" and "right"');
        }
        [$col, $row] = self::checkCoordinate($coords);
        $colStartNumber = array_search($col, ChessBoard::getColumns()) + 1;

        if ($direction === 'up') {
            $row++;
        }
        if ($direction === 'down') {
            $row--;
        }
        if ($direction === 'left') {
            $colStartNumber--;
        }
        if ($direction === 'right') {
            $colStartNumber++;
        }
        $col = ChessBoard::getColumns()[$colStartNumber - 1] ?? '';

        if (
        preg_match('/^([' . self::COLUMN_START . '-' . self::COLUMN_END . '])([' . self::ROW_START . '-' . self::ROW_END . '])$/',
            $col.$row, $matches)
        ) {
            $col = $matches[1];
            $row = (int)$matches[2];

            $nextCaseCoords = $col.$row;
        }

        return $nextCaseCoords ?? null;
    }

    public function addPiece(string $coords, Piece $piece): self
    {
        if (!$piece->isMoveValid($this, $coords) || !$this->isFreeCase($piece, $coords)) {
            throw new \LogicException('Wrong move');
        }

        $this->setPiece($coords, $piece);

        return $this;
    }

    public function isFreeCase(Piece $piece, string $destination): bool
    {
        return self::caseExists($destination) && ($this->getPiece($destination) === null ||
                ($this->getPiece($destination) instanceof Piece &&
                    $this->getPiece($destination)->getColor() !== $piece->getColor()
                ));
    }

    public static function caseExists(string $coords): bool
    {
        return preg_match('/^([' . self::COLUMN_START . '-' . self::COLUMN_END . '])([' . self::ROW_START . '-' . self::ROW_END . '])$/',
            $coords);
    }

    public function getPiece(string $coords): ?Piece
    {
        [$col, $row] = self::checkCoordinate($coords);

        return $this->getCases()[$col][$row];
    }

    public static function checkCoordinate(?string $coords)
    {
        if (
        preg_match('/^([' . self::COLUMN_START . '-' . self::COLUMN_END . '])([' . self::ROW_START . '-' . self::ROW_END . '])$/',
            $coords, $matches)
        ) {
            $col = $matches[1];
            $row = (int)$matches[2];
        } else {
            throw new \LogicException('This coordinate does\'nt exist');
        }

        return [$col, $row];
    }

    /**
     * @return mixed
     */
    public function getCases(): array
    {
        return $this->cases;
    }

    /**
     * @param mixed $cases
     * @return ChessBoard
     */
    public function setCases(array $cases): self
    {
        $this->cases = $cases;

        return $this;
    }

    public function setPiece(string $coords, ?Piece $piece)
    {
        [$col, $row] = self::checkCoordinate($coords);
        $this->cases[$col][$row] = $piece;
    }

    public function isEmptyCase(string $coords): bool
    {
        return self::caseExists($coords) && $this->getPiece($coords) === null;
    }

    public function searchPiece(Piece $piece): ?string
    {
        foreach (self::getColumns() as $column) {
            foreach (self::getRows() as $row) {
                if ($piece === $this->getPiece($column . $row)) {
                    $coords = $column . $row;
                }
            }
        }

        return $coords ?? null;
    }

    public function searchKing(string $color) :?string
    {
        foreach (self::getColumns() as $column) {
            foreach (self::getRows() as $row) {
                if ($this->getPiece($column . $row) instanceof King &&
                    $this->getPiece($column . $row)->getColor() === $color) {
                    $kingCoords = $column . $row;
                }
            }
        }

        return $kingCoords ?? null;
    }

    public static function getColumns(): array
    {
        return range(self::COLUMN_START, self::COLUMN_END);
    }

    public static function getRows(): array
    {
        return range(self::ROW_START, self::ROW_END);
    }


}