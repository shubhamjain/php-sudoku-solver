PHP Sudoku Solver
=================

A sudoku solver implemented in PHP. It uses a bruteforce back-tracking algorithim. This is a v1.0 of program.


How to Use
==========

The package consists of two main classes, a solver, `SudokuSolver`, and a genrator, `SudokuGenerator`. 

## Using Solver

To use this, you can initialize the SudokuSolver with a long string of numbers where 0 means an empty value. Calling `solve()` on object will try to solve the Sudoku by Backtracking algorithim. If it cannot be solved, `SudokuSolver::NOT_SOLVABLE` is returned.

```php
    include "SudokuSolver.php";
    $sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
    $sudoku->Solve();
    
    print $sudoku->OutputString();
```


