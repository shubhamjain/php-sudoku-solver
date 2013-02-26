PHP Sudoku Solver
=================

A sudoku solver implemented in PHP. It uses a brute force back-tracking algorithim. 

Future versions will include difficulty grading, performance improvements, nice template, a sudoku generator and more tests.

How to Use
==========

Using this library is simple. A simple program will work as

    include "SudokuSolver.php";
    $sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
    $sudoku->Solve();
    
    print $sudoku->OutputString();
