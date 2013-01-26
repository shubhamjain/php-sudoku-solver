<?php

    class SolverTester extends PHPUnit_Framework_TestCase
    {
        /*
         * @covers SudokuSolver::Solve()
         */
        public function testCoreSolve()
        {
            include "../SudokuSolver.php";

            $sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
            $sudoku->Solve();

            $this->assertEquals($sudoku->OutputString(), "143628579572139468986754231391542786468917352725863914237481695619275843854396127");
        }
    }