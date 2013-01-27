<?php

    class SolverTester extends PHPUnit_Framework_TestCase
    {

        public function SetUp()
        {
            include_once "../SudokuSolver.php";
        }

        /*
         * @covers SudokuSolver::Solve()
         */
        public function testCoreSolve()
        {

            $sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
            $sudoku->Solve();

            $this->assertEquals($sudoku->OutputString(), "143628579572139468986754231391542786468917352725863914237481695619275843854396127");
        }

        /*
        * @covers SudokuSolver::Solve()
        */
        public function testCanDetectUnsolvable()
        {
            $sudoku = new SudokuSolver("203000509002109400000704000300502006060000050700803004000401000009205800804000107");

            $this->assertEquals($sudoku->Solve(), SudokuSolver::NOT_SOLVABLE);
        }

        /*
        * @covers SudokuSolver::HasUnique()
        */
        public function testHasUnique()
        {
            $sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
            $sudoku->solve();

            $this->assertEquals($sudoku->HasUnique(), TRUE);
        }
    }