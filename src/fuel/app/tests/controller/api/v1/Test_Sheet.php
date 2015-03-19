<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Controller_Api_V1_Sheet COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Controller_Api_V1_Sheet extends TestCase
{
	private static $_sheet_id = null;
	private static $_sheet_id2 = null;

	private static $_sheet_schedule_id = null;
	private static $_sheet_schedule_id2 = null;

	/**
	 * セットアップ
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public static function setUpBeforeClass()
	{
		// DBに仮データを入れる
		// sheets
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

		self::$_sheet_id = $sheet->id;


		// sheet_columns
		for ($row = 1; $row <= 9; $row++)
		{
			for ($col = 1; $col <= 9; $col++)
			{
				$column = Model_SheetColumn::forge(array(
					'sheet_id' => self::$_sheet_id,
					'row' => $row,
					'col' => $col,
					'num' => 1,
					'is_show' => 1
				));

				$column->save();
			}
		}

		// sheet_schedules
		$schedule = Model_SheetSchedule::forge(array(
				'opened_at' => date('Y-m-d H:i:s'),
				'closed_at' => date('Y-m-d H:i:s', time() + 86400),
				'created_at' => time(),
				'updated_at' => time()
		));

		$schedule->save();

		self::$_sheet_schedule_id = $schedule->id;

		// sheet_set
		$set = Model_SheetSet::forge(array(
				'sheet_schedule_id' => self::$_sheet_schedule_id,
				'level' => 1,
				'sheet_id' => $sheet->id
		));

		$set->save();



		// sheets
		$sheet = Model_Sheet::forge(array(
				'level' => 1,
				'name' => '問題名2',
				'rows' => 9,
				'cols' => 9,
				'opened_at' => date('Y-m-d H:i:s'),
				'closed_at' => date('Y-m-d H:i:s', time() + 86400),
				'created_at' => time(),
				'updated_at' => time()
		));

		$sheet->save();

		self::$_sheet_id2 = $sheet->id;

		// sheet_schedules
		$schedule = Model_SheetSchedule::forge(array(
				'opened_at' => date('Y-m-d H:i:s', time() + 86400),
				'closed_at' => date('Y-m-d H:i:s', time() + 172800),
				'created_at' => time(),
				'updated_at' => time()
		));

		$schedule->save();

		self::$_sheet_schedule_id2 = $schedule->id;

		// sheet_set
		$set = Model_SheetSet::forge(array(
				'sheet_schedule_id' => self::$_sheet_schedule_id2,
				'level' => 1,
				'sheet_id' => $sheet->id
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
		// set
		$set = Model_SheetSet::find(array(self::$_sheet_schedule_id, 1));
		$set->delete();

		$set = Model_SheetSet::find(array(self::$_sheet_schedule_id2, 1));
		$set->delete();

		// sheet_columns
		$query = Model_SheetColumn::query()->where('sheet_id', '=', self::$_sheet_id);
		$query->delete();

		// sheets
		$sheet = Model_Sheet::find(array(self::$_sheet_id));
		$sheet->delete();

		$sheet = Model_Sheet::find(array(self::$_sheet_id2));
		$sheet->delete();

		// sheet_schedules
		$schedule = Model_SheetSchedule::find(array(self::$_sheet_schedule_id));
		$schedule->delete();

		$schedule = Model_SheetSchedule::find(array(self::$_sheet_schedule_id2));
		$schedule->delete();
	}

	/**
	 * get_available_listのテスト
	 */
	public function test_get_list()
	{
		// ERR102
		$_GET = array('user_token' => __CLASS__);
		$schedule = Model_SheetSchedule::find(array(self::$_sheet_schedule_id));
		$latest_opened_at = $schedule->opened_at;
		$schedule->opened_at = date('Y-m-d H:i:s', time() + 43200);
		$schedule->save();

		$res = Request::forge('api/v1/sheet/list')
			->execute()
			->response();

		$body_json = json_decode($res->body, true);
		$this->assertEquals('ERR102', $body_json['error_code'], $res->body);

		$schedule->opened_at = $latest_opened_at;
		$schedule->save();

		// 正常系
		$_GET = array('user_token' => __CLASS__);
		$res = Request::forge('api/v1/sheet/list')
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
		// 異常系
		// ERR102
		$schedule = Model_SheetSchedule::find(array(self::$_sheet_schedule_id));
		$latest_opened_at = $schedule->opened_at;
		$schedule->opened_at = date('Y-m-d H:i:s', time() + 43200);
		$schedule->save();

		$res = Request::forge('api/v1/sheet/detail/' . self::$_sheet_id)
			->execute()
			->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR102', $body_json['error_code']);

		$schedule->opened_at = $latest_opened_at;
		$schedule->save();


		// 正常系
		$res = Request::forge('api/v1/sheet/detail/' . self::$_sheet_id)
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('問題名', $body_json['sheet']['name']);

		// 異常系(1)
		$res = Request::forge('api/v1/sheet/detail/' . self::$_sheet_id2)
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR101', $body_json['error_code']);

		// 異常系(2)
		$res = Request::forge('api/v1/sheet/detail/' . (self::$_sheet_id * 2))
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertEquals('ERR101', $body_json['error_code'], 'Request ' . 'api/v1/sheet/detail/' . (self::$_sheet_id * 2));
	}

	/**
	 * get_generateのテスト
	 */
	public function test_get_generate()
	{
		$res = Request::forge('api/v1/sheet/generate')
				->execute()
				->response();

		$body_json = json_decode($res->body, true);

		$this->assertArrayHasKey('contents', $body_json);
	}
}