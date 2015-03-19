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
		// Viewに渡すデータ
		$data = null;

		// ここをDBから取得した値を設定する
		$data['staffs'] = 'dummy';

		$data['display_title'] = '社員一覧';
		$this->template->title = '社員管理';
		$this->template->content = View::forge('admin/staff/index', $data);

	}
}