<?php

use Fuel\Core\TestCase;

/**
 *
 * @author Mikihiro
 * @group App
 * @copyright Test_Model_Sheet COPYRIGHT FUJITSU LIMITED 2014
 */
class Test_Model_Sheet extends TestCase
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
	 * validateのテスト
	 */
	public function test_validate()
	{
		$validate = Model_Sheet::validate('test-create');

		$name = get_class($validate);
		$this->assertEquals('Fuel\\Core\\Validation', $name);
	}
}