<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Controller_Api_V1_QuestSheet COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Controller_Api_V1_QuestSheet extends TestCase
{
	private static $_quest_sheet_id = null;
	private static $_quest_sheet_id2 = null;

	private static $_quest_schedule_id = null;
	private static $_quest_schedule_id2 = null;

	/**
	 * セットアップ
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public static function setUpBeforeClass()
	{
		// DBに仮データを入れる
		// sheets
		$sheet = Model_QuestSheet::forge(array(
			'level' => 1,
			'name' => '問題名',
			'rows' => 9,
			'cols' => 9,
			'created_at' => time(),
			'updated_at' => time()
		));

		$sheet->save();

		self::$_quest_sheet_id = $sheet->id;


		// sheet_columns
		for ($row = 1; $row <= 9; $row++)
		{
			for ($col = 1; $col <= 9; $col++)
			{
				$column = Model_QuestSheetColumn::forge(array(
					'quest_sheet_id' => $sheet->id,
					'row' => $row,
					'col' => $col,
					'num' => 1,
					'is_show' => 1
				));

				$column->save();
			}
		}

		// quest_schedules
		$schedule = Model_QuestSchedule::forge(array(
				'opened_at' => date('Y-m-d H:i:s'),
				'closed_at' => date('Y-m-d H:i:s', time() + 86400),
				'created_at' => time(),
				'updated_at' => time()
		));

		$schedule->save();

		self::$_quest_schedule_id = $schedule->id;

		// quest_set
		$set = Model_QuestSet::forge(array(
				'quest_schedule_id' => self::$_quest_schedule_id,
				'level' => 1,
				'quest_sheet_id' => $sheet->id
		));

		$set->save();

		// sheets
		$sheet = Model_QuestSheet::forge(array(
				'level' => 1,
				'name' => '問題名2',
				'rows' => 9,
				'cols' => 9,
				'created_at' => time(),
				'updated_at' => time()
		));

		$sheet->save();

		self::$_quest_sheet_id2 = $sheet->id;

		// quest_schedules
		$schedule = Model_QuestSchedule::forge(array(
				'opened_at' => date('Y-m-d H:i:s', time() + 86400),
				'closed_at' => date('Y-m-d H:i:s', time() + 172800),
				'created_at' => time(),
				'updated_at' => time()
		));

		$schedule->save();

		self::$_quest_schedule_id2 = $schedule->id;

		// quest_set
		$set = Model_QuestSet::forge(array(
				'quest_schedule_id' => self::$_quest_schedule_id2,
				'level' => 1,
				'quest_sheet_id' => $sheet->id
		));

		$set->save();

	}

	/**
	 * 終了処理
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	public static function tearDownAfterClass()
	{
		// DBから仮データを消す
		// quest_schedules
		//$query = Model_QuestSchedule::query()->where('id', '=', self::$_quest_schedule_id);
		//$query->delete();

		// set
		$set = Model_QuestSet::find(array(self::$_quest_schedule_id, 1));
		$set->delete();

		$set = Model_QuestSet::find(array(self::$_quest_schedule_id2, 1));
		$set->delete();

		// sheet_columns
		$query = Model_QuestSheetColumn::query()->where('quest_sheet_id', '=', self::$_quest_sheet_id);
		$query->delete();

		// sheets
		$sheet = Model_QuestSheet::find(array(self::$_quest_sheet_id));
		$sheet->delete();

		$sheet = Model_QuestSheet::find(array(self::$_quest_sheet_id2));
		$sheet->delete();

		// quest_schedules
		$schedule = Model_QuestSchedule::find(array(self::$_quest_schedule_id));
		$schedule->delete();

		// quest_schedules
		$schedule = Model_QuestSchedule::find(array(self::$_quest_schedule_id2));
		$schedule->delete();
	}

	/**
	 * get_available_listのテスト
	 */
	public function test_get_list()
	{
		// ERR102
		$_GET = array('user_token' => __CLASS__);
		$schedule = Model_QuestSchedule::find(array(self::$_quest_schedule_id));
		$latest_opened_at = $schedule->opened_at;
		$schedule->opened_at = date('Y-m-d H:i:s', time() + 43200);
		$schedule->save();

		$res = Request::forge('api/v1/quest-sheet/list')
			->execute()
			->response();

		$body_json = json_decode($res->body, true);
		$this->assertEquals('ERR102', $body_json['error_code'], $res->body);

		$schedule->opened_at = $latest_opened_at;
		$schedule->save();

		// ERR101
		$_GET = array();
		$res = Request::forge('api/v1/quest-sheet/list')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR100', $body_json['error_code']);

		// 正常系
		$_GET = array('user_token' => __CLASS__);
		$res = Request::forge('api/v1/quest-sheet/list')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('問題名', $body_json['sheets'][0]['name']);
	}

	/**
	 * get_detailのテスト
	 */
	public function test_get_detail()
	{
		// 正常系
		$res = Request::forge('api/v1/quest-sheet/detail/' . self::$_quest_sheet_id)
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('問題名', $body_json['sheet']['name']);

		// 異常系(1)
		$res = Request::forge('api/v1/quest-sheet/detail/' . self::$_quest_sheet_id2)
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR102', $body_json['error_code']);

		// 異常系(2)
		$res = Request::forge('api/v1/quest-sheet/detail/' . (self::$_quest_sheet_id * 2))
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR101', $body_json['error_code']);
	}

	public function test_post_try()
	{
		// 異常系
		// ERR102
		$schedule = Model_QuestSchedule::find(array(self::$_quest_schedule_id));
		$latest_opened_at = $schedule->opened_at;
		$schedule->opened_at = date('Y-m-d H:i:s', time() + 43200);
		$schedule->save();

		$_POST = array(
				'user_token' => __CLASS__,
				'quest_sheet_id' => self::$_quest_sheet_id
		);
		$res = Request::forge('api/v1/quest-sheet/try')
			->set_method('POST')
			->execute()
			->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR102', $body_json['error_code']);

		$schedule->opened_at = $latest_opened_at;
		$schedule->save();

		// ERR101
		$_POST = array('user_token' => __CLASS__);
		$res = Request::forge('api/v1/quest-sheet/try')
				->set_method('POST')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR100', $body_json['error_code']);

		// ERR101(1)
		$_POST['user_token'] = __CLASS__;
		$_POST['quest_sheet_id'] = self::$_quest_sheet_id * 2;

		$res = Request::forge('api/v1/quest-sheet/try')
				->set_method('POST')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR101', $body_json['error_code']);

		// ERR101(2)
		$_POST['user_token'] = __CLASS__;
		$_POST['quest_sheet_id'] = self::$_quest_sheet_id2;

		$res = Request::forge('api/v1/quest-sheet/try')
				->set_method('POST')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR101', $body_json['error_code']);

		// 正常系
		// OK
		$_POST['user_token'] = __CLASS__;
		$_POST['quest_sheet_id'] = self::$_quest_sheet_id;

		$res = Request::forge('api/v1/quest-sheet/try')
				->set_method('POST')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);
		$this->assertEquals('OK', $body_json['result']);

		// ERR103
		$_POST['user_token'] = __CLASS__;
		$_POST['quest_sheet_id'] = self::$_quest_sheet_id;

		$res = Request::forge('api/v1/quest-sheet/try')
				->set_method('POST')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR103', $body_json['error_code']);
	}
}