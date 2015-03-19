<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_QuestSheet COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_QuestSheet extends TestCase
{
// 	private $_quest_sheet_id = null;
// 	private $_quest_schedule_id = null;

// 	/**
// 	 * セットアップ
// 	 * @see PHPUnit_Framework_TestCase::setUp()
// 	 */
// 	public function setUp()
// 	{
// 		// DBに仮データを入れる

// 		// quest_sheets
// 		$sheet = Model_QuestSheet::forge(array(
// 			'level' => 1,
// 			'name' => '問題名',
// 			'rows' => 9,
// 			'cols' => 9,
// 			'created_at' => time(),
// 			'updated_at' => time()
// 		));

// 		$sheet->save();

// 		$this->_quest_sheet_id = $sheet->id;


// 		// quest_schedules
// 		$schedule = Model_QuestSchedule::forge(array(
// 			//'quest_sheet_id' => $sheet->id,
// 			'opened_at' => date('Y-m-d H:i:s'),
// 			'closed_at' => date('Y-m-d H:i:s', time() + 86400),
// 			'created_at' => time(),
// 			'updated_at' => time()
// 		));

// 		$schedule->save();

// 		$this->_quest_schedule_id = $schedule->id;

// 		// quest_set
// 		$set = Model_QuestSet::forge(array(
// 			'quest_schedule_id' => $this->_quest_schedule_id,
// 			'level' => 1,
// 			'quest_sheet_id' => $this->_quest_sheet_id
// 		));

// 		$set->save();
// 	}

// 	/**
// 	 * 終了処理
// 	 * @see PHPUnit_Framework_TestCase::tearDown()
// 	 */
// 	public function tearDown()
// 	{
// 		// DBから仮データを消す
// 		// quest_set
// 		$set = Model_QuestSet::find(array($this->_quest_schedule_id, 1));
// 		$set->delete();

// 		// quest_sheets
// 		$sheet = Model_QuestSheet::find(array($this->_quest_sheet_id));
// 		$sheet->delete();

// 		// quest_schedules
// 		$schedule = Model_QuestSchedule::find(array($this->_quest_schedule_id));
// 		$schedule->delete();
// 	}

	/**
	 * get_availableのテスト
	 */
	public function test_validate()
	{
		$ret = Model_QuestSheet::validate('create');

		$this->assertInstanceOf('Fuel\\Core\\Validation', $ret);
	}

	public function test_saveOneSheet()
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

		// シートデータ
		$column = Model_QuestSheetColumn::forge(array('quest_sheet_id' => $sheet->id));
		$this->assertInstanceOf('Model_QUestSheetColumn', $column);

		if ($column)
		{
			// データ作成
			$data = Model_Sudoku::solve(false);

			$sheet_nums = array();
			$sheet_shows = array();

			foreach ($data as $row => $cols)
			{
				foreach ($cols as $col => $val)
				{
					$sheet_nums[$row + 1][$col + 1] = $val['num'];
					$sheet_shows[$row + 1][$col + 1] = $val['is_show'] ? 1 : 0;
				}
			}

			// 登録
			$ret = $column->saveOneSheet($sheet_nums, $sheet_shows);
			$this->assertEquals(81, $ret[1]);
		}

		// 削除
		$ret = $sheet->delete();
		$this->assertTrue($ret->is_new());
	}
}