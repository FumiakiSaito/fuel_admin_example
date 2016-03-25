<?php
/**
 *  MyValidationRules class Tests
 * @group validation
 */
class myvalidationrules_Test extends TestCase
{
	public function test_validation_no_tab_and_newline_valid()
	{
		$input = '�^�u�����s���܂܂Ȃ�������ł��B';
		$test = MyValidationRules::_validation_no_tab_and_newline($input);
		$expected = true;
		$this->assertEquals($expected, $test);
	}

	/**
	 * @dataProvider invalid_data_provider
	 */
	public function test_validation_no_tab_and_newline_invalid($input)
	{
		$test = MyValidationRules::_validation_no_tab_and_newline($input);
		$expected = false;
		$this->assertEquals($expected, $test);
	}

	public function invalid_data_provider()
	{
		return array(
			array("���s���܂�\n������ł��B"),
			array("���s���܂�\r������ł�"),
			array("���s���܂�\r\n������ł��B"),
			array("�^�u���܂�\t������ł��B"),
			array("���s��\r�^�u���܂�\t������\n�ł��B"),
		);
	}
}