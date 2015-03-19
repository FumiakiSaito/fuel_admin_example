<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_Sudoku COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_Sudoku extends TestCase
{
	/**
	 * validateのテスト
	 */
	public function test_solve()
	{
		// 数値のみで9x9の個数分返ってくるか
		$ret = Model_Sudoku::solve(true);

		$this->assertEquals(9 * 9, count($ret));

		// オープンにする数
		$ret = Model_Sudoku::solve(false, 1);
		$this->_check_solve($ret, 17);

		$ret = Model_Sudoku::solve(false, 90);
		$this->_check_solve($ret, 80);
	}

	/**
	 * Model_Sudoku::solve(false)で作った内容をチェック
	 * @param array $ret
	 * @param number $open_numbers
	 */
	private function _check_solve(&$ret, $open_numbers)
	{
		$this->assertEquals(9, count($ret));

		$show_count = 0;
		foreach ($ret as $row => $cols)
		{
			$this->assertEquals(9, count($cols));

			foreach ($cols as $idx => $col)
			{
				if ($col['is_show'])
				{
					$show_count++;
				}
			}
		}

		$this->assertEquals($open_numbers, $show_count);
	}
}