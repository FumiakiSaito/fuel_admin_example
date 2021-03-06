<?php

class Model_Staff extends \Orm\Model
{
	protected static $_table_name = 'staff';

	protected static $_properties = array(
		'id',
		'num',
		'name',
		'sex',
		'division_id',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => true,
		),
	);

}
