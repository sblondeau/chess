# ChessBoard    

This projet allow to move chess pieces on a chessboard (with simple 2d/3d view option) by clicking on the pieces.

The main project is a PHP collection of classes which check if a move if possible, according to piece type, position, turn, etc.

## Installation
- composer install
- php -S localhost:8000 -t public
- open index.php for a simple game.

It is also possible to start a game with predeterminated positions, or to display many chessboard on a same page.

## Usage
```php
// by default, array ChessBoardInitializer::CLASSIC_CHESSBOARD is given (to start a classical game)
$chessboard = new ChessBoard(ChessBoardInitializer::CLASSIC_CHESSBOARD);

// Record the previous moves. Needed for "en passant" catch of for "roque" moves.
// the object should not be instantiated between each round, but stored (session, db...). 
$movesRecording = new \App\MovesRecording();

// the second option (false by default) force that moves can not be twice from the same player.
// useful to desactivate it if you just want to move pieces on the chessboard without the "two player" constraint. However, should be set to true for a "real" game.
$game = new Game($chessboard, true, $movesRecording);

try {
// $start and $end are case coordinate string, e.g. 'A7', 'G5', etc.
     $game->gameMove($start, $end);
} catch (LogicException $exception) {
     $error = $exception->getMessage();
}
```

Start with another pieces configuration
```php
$anotherChessboard = [
    'E1' => [King::class, 'white'],
    'F1' => [Pawn::class, 'black'],
    'H7' => [Pawn::class, 'black'],
    'E8' => [King::class, 'black'],
    'D4' => [Pawn::class, 'white'],
    'H5' => [Bishop::class, 'white'],
    'G6' => [Pawn::class, 'black'],
];

$game = new Game($anotherChessboard);
```

### special moves
```php
// $type = 'small' or 'big'
// $player = 'white' or 'black'
$game->roque($type, $player);

// $pawn instance of Pawn
// $piece : class name of piece to promote in. (Queen::class, Bishop::class, ...)  
$game->promote($pawn, $piece);
```