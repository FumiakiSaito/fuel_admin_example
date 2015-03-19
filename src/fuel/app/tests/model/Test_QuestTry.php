<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_QuestTry COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_QuestTry extends TestCase
{
	private $_quest_sheet_id = null;
	private $_quest_schedule_id = null;

	private $_user_id = __CLASS__;

	/**
	 * セットアップ
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		// DBに仮データを入れる

		// quest_sheets
		$sheet = Model_QuestSheet::forge(array(
			'level' => 1,
			'name' => '問題名',
			'rows' => 9,
			'cols' => 9,
			'created_at' => time(),
			'updated_at' => time()
		));

		$sheet->save();

		$this->_quest_sheet_id = $sheet->id;


		// quest_schedules
		$schedule = Model_QuestSchedule::forge(array(
			'quest_sheet_id' => $sheet->id,
			'opened_at' => date('Y-m-d H:i:s'),
			'closed_at' => date('Y-m-d H:i:s', time() + 86400),
			'created_at' => time(),
			'updated_at' => time()
		));

		$schedule->save();

		$this->_quest_schedule_id = $schedule->id;
	}

	/**
	 * 終了処理
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public function tearDown()
	{
		// DBから仮データを消す
		// quest_try
		$try = Model_QuestTry::find(array($this->_quest_sheet_id, $this->_user_id));
		if ($try)
		{
			$try->delete();
		}

		// quest_sheets
		$sheet = Model_QuestSheet::find(array($this->_quest_sheet_id));

		$sheet->delete();

		// quest_schedules
		$schedule = Model_QuestSchedule::find(array($this->_quest_schedule_id));

		$schedule->delete();
	}

	/**
	 * is_today_triedのテスト
	 */
	public function test_is_today_tried()
	{
		$tried = Model_QuestTry::is_today_tried($this->_quest_sheet_id, $this->_user_id);
		$this->assertFalse($tried);

		// プレイしたことにする
		$try = Model_QuestTry::forge(array(
			'quest_sheet_id' => $this->_quest_sheet_id,
			'user_id' => $this->_user_id,
			'tried_at' => date('Y-m-d H:i:s')
		));

		$try->save();

		$tried = Model_QuestTry::is_today_tried($this->_quest_sheet_id, $this->_user_id);
		$this->assertTrue($tried);
	}
}