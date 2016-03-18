<?php
/**
 * 社員管理コントローラー
 *
 * @author
 * @since
 */
class Controller_Admin_Staff extends Controller_Admin
{
	public function action_index()
	{
		$data = null;
		$data['staffs'] = Model_Staff::find('all');

		$data['display_title'] = '社員一覧';
		$this->template->title = '社員管理';
		$this->template->content = View::forge('admin/staff/index', $data);
	}
}