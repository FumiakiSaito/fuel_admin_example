<?php
/**
 * 社員管理コントローラー
 *
 * @author
 * @since
 */
class Controller_Admin_Staff extends Controller_Admin
{
	private $_divisions;

	public function before()
	{
		parent::before();
		$this->template->title = '社員管理';

		// 部署データ設定
		$divisions = Model_Division::find('all');
		foreach ($divisions as $division)
		{
			$divisions_data[$division->id] = $division->name;
		}
		$this->_divisions = $divisions_data;
	}

	/**
	 * 一覧
	 */
	public function action_index()
	{
		$data = null;
		$data['staffs'] = Model_Staff::find('all');

		$data['display_title'] = '社員一覧';
		$this->template->content = View::forge('admin/staff/index', $data);
	}

	/**
	 * 新規登録ページ
	 */
	public function action_add()
	{
		$data = null;
		$data['display_title'] = '社員登録';

		$data['divisions'] = $this->_divisions;
		$this->template->content = View::forge('admin/staff/add', $data);
	}

	/**
	 * 登録確認
	 */
	public function action_confirm()
	{
		$val = $this->forge_validation();

		$data = null;
		$data['divisions'] = $this->_divisions;

		if ($val->run())
		{
			$data['display_title'] = '社員登録';
			$data['input'] = $val->validated();
			$this->template->content = View::forge('admin/staff/confirm', $data);
		}
		else
		{
			$data['display_title'] = '社員登録';
			$this->template->content = View::forge('admin/staff/add', $data);
			$this->template->content->set_safe('html_error', $val->show_errors());
		}
	}

	public function forge_validation()
	{
		$val = Validation::forge();

		$val->add('num', '社員番号')
			->add_rule('trim')
			->add_rule('required')
			->add_rule('max_length', 4);

		$val->add('name', '名前')
			->add_rule('trim')
			->add_rule('required')
			->add_rule('max_length', 20);

		$val->add('sex', '性別');

		$val->add('division_id', '所属部署');

		return $val;
	}

	/**
	 * 新規登録処理
	 */
	public function action_send()
	{
		if (!Security::check_token())
		{
			return 'ページ遷移が正しくありません';
		}

		$data['display_title'] = '社員登録';

		$val = $this->forge_validation();
		if (!$val->run())
		{
			$this->template->content = View::forge('admin/staff/add', $data);
			$this->template->content->set_safe('html_error', $val->show_errors());
		}

		$staff = Model_Staff::forge(array(
			'num'          => Input::post('num'),
			'name'         => Input::post('name'),
			'sex'          => Input::post('sex'),
			'division_id' => Input::post('division_id'),
		));

		if ($staff->save())
		{
			Session::set_flash('success', e('登録しました!'));
		}
		else
		{
			Session::set_flash('error', e('エラーが発生しました'));
		}
		Response::redirect('admin/staff/index');
	}
}