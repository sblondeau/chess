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

    public function __construct(array $pieces = ChessBoardInitializer::CLASSIC_CHESSBOARD)
    {
        ChessBoardInitializer::initBoard($this);
        ChessBoardInitializer::initPieces($this, $pieces);
    }

    public static function caseExists(string $coords): bool
    {
        return preg_match('/^([' . self::COLUMN_START . '-' . self::COLUMN_END . '])([' . self::ROW_START . '-' . self::ROW_END . '])$/',
            $coords);
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
        return $this->getPiece($destination) === null ||
            ($this->getPiece($destination) instanceof Piece &&
                $this->getPiece($destination)->getColor() !== $piece->getColor()
            );
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

    public function setPiece(string $coords, Piece $piece)
    {
        [$col, $row] = self::checkCoordinate($coords);
        $this->cases[$col][$row] = $piece;
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

    public static function getColumns(): array
    {
        return range(self::COLUMN_START, self::COLUMN_END);
    }

    public static function getRows(): array
    {
        return range(self::ROW_START, self::ROW_END);
    }


}