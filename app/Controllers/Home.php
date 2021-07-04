<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\ChildrenModel;
use App\Models\UserModel;

class Home extends BaseController
{

	protected $absensiModel;
	protected $childrenModel;
	protected $userModel;

	public function __construct()
	{
		$this->absensiModel = new AbsensiModel();
		$this->childrenModel = new ChildrenModel();
		$this->userModel = new UserModel();
	}

	public function dashboard()
	{
		$date = date('Y');
		$month = [];
		if (!in_groups('pusat')) {
		    $notify = user()->toArray()['notify_birthday'];
			$child_birthday = $this->childrenModel->birthDayChildren();
			$ultah = [];
			foreach ($child_birthday as $birth) {
				if (date('m') == date('m', strtotime($birth['tanggal_lahir']))) {
					$ultah[] = $birth;
				}
			}
			$datas = $this->absensiModel->join('pembimbings', "pembimbings.id_pembimbing = absensis.pembimbing_id")
				->where('region_pembimbing', user()->toArray()['region'])
				->where('year', $date)
				->get()
				->getResultArray();

			foreach ($datas as $data) {
				$month[] = $data['month'];
			}

			$data = [
				'month' 	=> array_unique($month),
				'title' 	=> 'Home Dashboard',
				'notif_birthday'		=> $notify,
				'children_birthday'		=> $ultah,
			];
		} else {
			$datas = $this->absensiModel->join('pembimbings', "pembimbings.id_pembimbing = absensis.pembimbing_id")
				->join('cabang', "cabang.id_cabang = pembimbings.region_pembimbing")
				->where('year', $date)
				->findAll();

			$cabangs = [];

			foreach ($datas as $data) {
				$month[] = $data['month'];
			}

			foreach ($datas as $data) {
				$cabangs[] = $data['nama_cabang'];
			}

			$data = [
				'month' 	=> array_reverse(array_unique($month)),
				'title' 	=> 'Home Dashboard',
				'cabangs'	=> array_unique($cabangs),
			];
		}

		return view('dashboard/index', $data);
	}

	public function getChartWeek($month)
	{

		$date = date('Y');

		$datas = $this->absensiModel->join('pembimbings', "pembimbings.id_pembimbing = absensis.pembimbing_id")->where('region_pembimbing', user()->toArray()['region'])->where('year', $date)->where('month', $month)->get()->getResultArray();


		$week = [];

		foreach ($datas as $w) {
			$week[] = $w['sunday_date'];
		}

		$fixed_week = array_unique($week);

		$data = [];

		foreach ($fixed_week as $f) {
			$data_temp = $this->absensiModel->join('pembimbings', "pembimbings.id_pembimbing = absensis.pembimbing_id")->where('region_pembimbing', user()->toArray()['region'])->where('sunday_date', $f)->countAllResults();

			if ($data_temp != 0) {
				$data[] = [
					'week'	 => $f,
					'jumlah' => $data_temp,
				];
			}
		}

		return json_encode($data);
	}

	public function getMonth($cabang)
	{
		$data = $this->absensiModel->getAllMonth($cabang)->findAll();
		$month = [];
		foreach ($data as $d) {
			$month[] = $d['month'];
		}

		$month = array_reverse(array_unique($month));

		return json_encode($month);
	}
}
