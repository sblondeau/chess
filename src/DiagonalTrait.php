<?php


namespace App;


Trait DiagonalTrait
{
    private function checkDiagonals(ChessBoard $chessBoard, $colStart, $rowStart)
    {
        for ($multiplier = -1; $multiplier < 2; $multiplier += 2) {
            for ($increment = -1; $increment < 2; $increment += 2) {
                $col = array_search($colStart, ChessBoard::getColumns()) + 1 + $increment;
                $row = $rowStart + $increment * $multiplier;

                while (($col >= ChessBoard::ROW_START && $col <= ChessBoard::ROW_END) &&
                    ($row >= ChessBoard::ROW_START && $row <= ChessBoard::ROW_END)) {
                    $case = ChessBoard::getColumns()[$col - 1] . $row;
                    if ($chessBoard->isEmptyCase($case)) {
                        $cases[] = $case;
                    } elseif ($chessBoard->getPiece($case)->getColor() !== $this->getColor()) {
                        $cases[] = $case;
                        break;
                    } else {
                        break;
                    }
                    $col += $increment;
                    $row += $increment * $multiplier;
                }
            }
        }

        return $cases ?? [];
    }


}