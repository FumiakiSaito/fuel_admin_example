<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_SheetColumn COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_SheetColumn extends TestCase
{
	private $_sheet_id = null;

	/**
	 * セットアップ
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		// DBに仮データを入れる
		$sheet = Model_Sheet::forge(array(
			'level' => 1,
			'name' => '問題名',
			'rows' => 9,
			'cols' => 9,
			'opened_at' => date('Y-m-d H:i:s'),
			'closed_at' => date('Y-m-d H:i:s', time() + 86400),
			'created_at' => time(),
			'updated_at' => time()
		));

		$sheet->save();

		$this->_sheet_id = $sheet->id;
	}

	/**
	 * 終了処理
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		// DBから仮データを消す
		$sheet = Model_Sheet::find(array($this->_sheet_id));

		$sheet->delete();
	}

	/**
	 * saveOneSheetのテスト
	 */
	public function test_saveOneSheet()
	{
		$column = Model_SheetColumn::forge(array('sheet_id' => $this->_sheet_id));

		$sheet_nums = array();
		$sheet_shows = array();
		for ($row = 1; $row < 10; $row++)
		{
			for ($col = 1; $col < 10; $col++)
			{
				$sheet_nums[$row][$col] = $row;
				$sheet_shows[$row][$col] = 1;
			}
		}

		$ret = $column->saveOneSheet($sheet_nums, $sheet_shows);

		$this->assertEquals(81, $ret[1]);
	}
}