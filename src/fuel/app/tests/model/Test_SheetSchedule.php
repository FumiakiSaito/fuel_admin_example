<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_SheetSchedule COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_SheetSchedule extends TestCase
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

		// sheet_sheets
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


		// sheet_schedules
		$schedule = Model_SheetSchedule::forge(array(
			'opened_at' => date('Y-m-d H:i:s'),
			'closed_at' => date('Y-m-d H:i:s', time() + 86400),
			'created_at' => time(),
			'updated_at' => time()
		));

		$schedule->save();

		$this->_sheet_schedule_id = $schedule->id;

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
		// sheet_set
		$set = Model_SheetSet::find(array($this->_sheet_schedule_id, 1));
		if ($set)
		{
			$set->delete();
		}

		// sheets
		$sheet = Model_Sheet::find(array($this->_sheet_id));
		if ($sheet)
		{
			$sheet->delete();
		}

		// sheet_schedules
		$schedule = Model_SheetSchedule::find(array($this->_sheet_schedule_id));
		if ($schedule)
		{
			$schedule->delete();
		}
	}

	/**
	 * get_availableのテスト
	 */
	public function test_validate()
	{
		$ret = Model_SheetSchedule::validate('create-sheet_schedule-test');

		$this->assertInstanceOf('Fuel\\Core\\Validation', $ret);

		unset($ret);
	}

	/**
	 * get_availableのテスト
	 */
	public function test_get_available()
	{
		$schedule = Model_SheetSchedule::get_available();

		$this->assertNotEmpty($schedule);
		$this->assertEquals($this->_sheet_schedule_id, $schedule->id);
	}
}