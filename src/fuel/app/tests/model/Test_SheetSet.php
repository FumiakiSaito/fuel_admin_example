<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_SheetSet COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_SheetSet extends TestCase
{
	private $_sheet_id = null;
	private $_sheet_schedule_id = null;

	/**
	 * セットアップ
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		// DBに仮データを入れる

		// quest_sheets
		$sheet = Model_Sheet::forge(array(
			'level' => 1,
			'name' => '問題名',
			'rows' => 9,
			'cols' => 9,
			'created_at' => time(),
			'updated_at' => time()
		));

		$sheet->save();

		$this->_sheet_id = $sheet->id;


		// quest_schedules
		$schedule = Model_SheetSchedule::forge(array(
			'opened_at' => date('Y-m-d H:i:s'),
			'closed_at' => date('Y-m-d H:i:s', time() + 86400),
			'created_at' => time(),
			'updated_at' => time()
		));

		$schedule->save();

		$this->_sheet_schedule_id = $schedule->id;

		// quest_set
		$set = Model_SheetSet::forge(array(
			'sheet_schedule_id' => $this->_sheet_schedule_id,
			'level' => 1,
			'sheet_id' => $this->_sheet_id
		));

		$set->save();
	}

	/**
	 * 終了処理
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		// DBから仮データを消す
		// quest_set
		$set = Model_SheetSet::find(array($this->_sheet_schedule_id, 1));
		if ($set)
		{
			$set->delete();
		}

		// quest_sheets
		$sheet = Model_Sheet::find(array($this->_sheet_id));
		if ($sheet)
		{
			$sheet->delete();
		}

		// quest_schedules
		$schedule = Model_SheetSchedule::find(array($this->_sheet_schedule_id));
		if ($schedule)
		{
			$schedule->delete();
		}
	}

	/**
	 * delete_by_sheet_schedule_idのテスト
	 */
	public function test_delete_by_sheet_schedule_id()
	{
		$ret = Model_SheetSet::delete_by_sheet_schedule_id($this->_sheet_schedule_id);

		$this->assertTrue($ret);
	}
}