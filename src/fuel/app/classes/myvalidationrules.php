<?php
class MyValidationRules
{
	// ���s�R�[�h��^�u���܂܂�Ă��Ȃ����̌��؃��[��
	public static function _validation_no_tab_and_newline($value)
	{
		if (preg_match('/\A[^\r\n\t]*\z/u', $value) === 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}