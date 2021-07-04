<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\CabangModel;
use App\Models\ChildrenModel;
use App\Models\ClassModel;
use App\Models\GoogleTokenModel;
use App\Models\PembimbingsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AbsensiController extends BaseController
{

    protected $absensiModel;
    protected $childrenModel;
    protected $pembimbingModel;
    protected $googleToken;
    protected $cabangModel;
    protected $classModel;
    protected $quiz;
    protected $zoom;
    protected $aba;
    protected $komsel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->childrenModel = new ChildrenModel();
        $this->pembimbingModel = new PembimbingsModel();
        $this->googleToken = new GoogleTokenModel();
        $this->cabangModel = new CabangModel();
        $this->classModel = new ClassModel();
        $getRegionDate = $this->cabangModel->find(user()->toArray()['region']);
        $this->quiz =  $getRegionDate['quiz'];
        $this->zoom = $getRegionDate['zoom'];
        $this->komsel = $getRegionDate['komsel'];
        $this->aba = $getRegionDate['aba'];
    }

    public function index()
    {
        // mengambil penghitungan data

        $sunday_date_controller = $this->getDateName();
        $sunday_date_model = $this->absensiModel->getDateName();
        if (!in_groups('pusat')) {
            $current_page = $this->request->getVar('page_absensi') ? $this->request->getVar('page_absensi') : 1;

            $absensis = $this->absensiModel->getAllDataFetch()->paginate(7, 'absensi');
            $pager = $this->absensiModel->pager;


            $data = [
                'title'         => 'Absensi',
                'absensis'      => $absensis,
                'pager'         => $pager,
                'current_page'  => $current_page,
                'quiz'          => boolval($this->quiz),
                'zoom'          => boolval($this->zoom),
                'aba'           => boolval($this->aba),
                'komsel'        => boolval($this->komsel),
            ];

            if (in_groups('superadmin')) {
                $data['sunday_date_model']          = $sunday_date_model;
                $data['sunday_date_controller']     = $sunday_date_controller;
            }
            return view('dashboard/absensi/index', $data);
        } else {
            $current_page = $this->request->getVar('page_absensi') ? $this->request->getVar('page_absensi') : 1;
            $date = explode(" ", $this->getDateName());
            $year = $date[2];
            $month = $date[1];

            $cabang = [];
            $sunday_date = [];

            $data = $this->absensiModel
                ->join('childrens', 'childrens.id_children = absensis.children_id')
                ->join('pembimbings', 'pembimbings.id_pembimbing = absensis.pembimbing_id')
                ->join('cabang', 'cabang.id_cabang = pembimbings.region_pembimbing')
                ->where('month', $month)
                ->where('year', $year)
                ->select('children_name,sunday_date,nama_cabang')
                ->findAll();

            $data_anak = $this->absensiModel
                ->join('childrens', 'childrens.id_children = absensis.children_id')
                ->join('pembimbings', 'pembimbings.id_pembimbing = absensis.pembimbing_id')
                ->join('cabang', 'cabang.id_cabang = pembimbings.region_pembimbing')
                ->where('month', $month)->where('year', $year)
                ->orderBy('absensis.created_at', 'DESC')
                ->select('id_absensi,children_name,sunday_date,nama_cabang')
                ->paginate(7, 'absensi');


            foreach ($data as $d) {
                $cabang[] = $d['nama_cabang'];
                $sunday_date[] = $d['sunday_date'];
            }
            $cabang = array_unique($cabang);
            $sunday_date = array_unique($sunday_date);

            $data = [
                'title'         => 'Absensi',
                'absensis'      => $data_anak,
                'pager'         => $this->absensiModel->pager,
                'current_page'  => $current_page,
                'cabangs'       => $cabang,
                'sunday_dates'  => $sunday_date,
                'quiz'          => $this->quiz,
                'zoom'          => boolval($this->zoom),
                'sunday_date_model'         => $sunday_date_model,
                'sunday_date_controller'    => $sunday_date_controller,
            ];
            return view('dashboard/absensi/index', $data);
        }
    }

    public function addAbsensi()
    {
        if (session()->getFlashData('data')) {
            $this->absensiModel->save(session()->getFlashData('data'));
        }
        $canUpdate = true;
        try {
            $token = $this->googleToken->first();
            if ($token == null && user()->toArray()['email'] != 'nathanael.vd@gmail.com') {
                $canUpdate = false;
            } else {
                $api = new GoogleApiServices();
            }
        } catch (\Throwable $th) {
            if (user()->toArray()['email'] == 'nathanael.vd@gmail.com') {
                $id = $this->googleToken->first()['token_id'];

                $delete = $this->googleToken->delete($id);

                if ($delete) {
                    return redirect()->to('/absensi/add');
                }
            } else {
                $canUpdate = false;
            }
        }

        $pembimbings = $this->pembimbingModel->where('region_pembimbing', user()->toArray()['region'])->get()->getResultArray();

        $data = [
            'title'         => 'Add Absensi',
            'validation'    => \Config\Services::validation(),
            'pembimbings'   => $pembimbings,
            'quiz'          => boolval($this->quiz),
            'zoom'          => boolval($this->zoom),
            'komsel'        => boolval($this->komsel),
            'aba'           => boolval($this->aba),
            'update'        => $canUpdate,
        ];

        return view('dashboard/absensi/add', $data);
    }
    
    public function validator()
    {
        $validator = [
            'pembimbing' => [
                'rules'     => 'required|integer',
                'errors'    => [
                    'required'  => 'Please Select The Pembimbing Name !',
                    'integer'   => 'The Pembimbing Must Be Integer',
                ],
            ],
            'children' => [
                'rules'     => 'required|integer',
                'errors'    => [
                    'required'  => 'Please Select The Children Name !',
                    'integer'   => 'The Children Must Be Integer',
                ],
            ],
            'picture'    => [
                'rules'     => 'mime_in[picture,image/gif,image/jpg,image/jpeg,image/png,image/svg+xml]|is_image[picture]|max_size[picture,50000]',
                'errors'    => [
                    'mime_in'   => 'Picture Must With Mime Type Of Images !',
                    'is_image'  => 'Picture Must Be A Picture File !',
                    'max_size'  => 'Picture Size Must Less Than 50000MB !'
                ],
            ],
            'video'    => [
                'rules'     => 'mime_in[video,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime]|max_size[video,50000]',
                'errors'    => [
                    'mime_in'   => 'Video Must With Mime Type Of Videos',
                    'max_size'  => 'Video Size Must Less Than 50MB',
                ],
            ],
        ];


        if (boolval($this->quiz)) {
            $validator['quiz'] =  [
                'rules'     => 'required|string|max_length[5]',
                'errors'    => [
                    'required'      => 'Please Select The Children Quiz !',
                    'string'        => 'Quiz Must Be String !',
                    'max_length'    => 'Quiz Max Is 5 !',
                ],
            ];
        }
        if (boolval($this->zoom)) {
            $validator['zoom'] = [
                'rules'     => 'required|string|max_length[5]',
                'errors'    => [
                    'required'      => 'Please Select The Children Zoom !',
                    'string'        => 'Quiz Must Be String !',
                    'max_length'    => 'Quiz Max Is 5 !',
                ],
            ];
        }

        if (boolval($this->aba)) {
            $validator['aba'] = [
                'rules'     => 'required|max_length[1]',
                'errors'    => [
                    'required'      => 'Please Select The Children Zoom !',
                    'max_length'    => 'Quiz Max Is 1 !',
                ],
            ];
        }

        if (boolval($this->komsel)) {
            $validator['komsel'] = [
                'rules'     => 'required|string|max_length[5]',
                'errors'    => [
                    'required'      => 'Please Select The Children Zoom !',
                    'string'        => 'Quiz Must Be String !',
                    'max_length'    => 'Quiz Max Is 5 !',
                ],
            ];
        }
        return $validator;
    }

    public function insert()
    {
        $api = new GoogleApiServices(); 
        $validator = $this->validator();
        
        $validate = $this->validate($validator);
        if (!$validate) {
            return redirect()->to('/absensi/add')->withInput();
        }

        $date_file_name = $this->getDateName();
        $bulan = explode(' ', $date_file_name)[1];

        //// search PPl Kids Name
        $pplParentId = $api->searchPplKidsFolder();

        //// search grouping folder name
        $group = $api->search_group_date_folder('Foto Video Anak - Bulan ' . $bulan, $pplParentId);

        //// search date folder 
        $dateFolderId = $api->search_parents_date_folder($date_file_name, $group);

        //// search region folder
        $regionName = $this->cabangModel->find(user()->toArray()['region'])['nama_cabang'];
        $regionFolderId = $api->search_parents_folder($regionName, $dateFolderId);

        //// search Besar Folder
        $besarId = $api->search_parents_folder('Besar', $regionFolderId);
        $kecilId = $api->search_parents_folder('Kecil', $regionFolderId);
        $teenId = $api->search_parents_folder('Teens', $regionFolderId);

        ///// search Foto Folder Besar
        $fotoIdBesar = $api->search_parents_folder('Foto', $besarId);
        $fotoIdKecil = $api->search_parents_folder('Foto', $kecilId);
        $fotoIdTeen = $api->search_parents_folder('Foto', $teenId);

        //// search Video Folder
        $videoIdBesar = $api->search_parents_folder('Video', $besarId);
        $videoIdKecil = $api->search_parents_folder('Video', $kecilId);
        $videoIdTeen = $api->search_parents_folder('Video', $teenId);



        //// get all request
        $pembimbingId = $this->request->getVar('pembimbing');
        $childrenId = $this->request->getVar('children');
        
        $quiz = '';
        $zoom = '';
        $aba = '';
        $komsel = '';
        if (boolval($this->quiz)) {
            $quiz = $this->request->getVar('quiz');
        } else {
            $quiz = '-';
        }

        if (boolval($this->zoom)) {
            $zoom = $this->request->getVar('zoom');
        } else {
            $zoom = '-';
        }
        
        if (boolval($this->aba)) {
            $aba = $this->request->getVar('aba');
        } else {
            $aba = '-';
        }

        if (boolval($this->komsel)) {
            $komsel = $this->request->getVar('komsel');
        } else {
            $komsel = '-';
        }

        $videoFile = $this->request->getFile('video');
        $pictureFile = $this->request->getFile('picture');


        //// get Children name by id
        $children = $this->childrenModel->getSingleChildren($childrenId);
        $childrenName = $children['children_name'];
        $kelas = $children['nama_kelas'];


        //// extension the file
        $pictExt = null;
        $videoExt = null;

        //// status to database
        $pict = null;
        $video = null;

        $pictId = '-';
        $videoIds =  '-';
        if ($pictureFile->getName() != "") {
            $pictExt = $pictureFile->getClientExtension();
            $pict = 'yes';
            
            if($kelas == 'Pratama' && $regionName == 'Kopo'){
                $pictId = $api->push_file($childrenName, $fotoIdBesar, $pictExt, $pictureFile);
            }elseif ($kelas == 'Balita' || $kelas == 'Batita' || $kelas == 'Pratama' || $kelas == 'Daud' || $kelas == 'Samuel' || $kelas == 'Balita/Pratama') {
                $pictId = $api->push_file($childrenName, $fotoIdKecil, $pictExt, $pictureFile);
            } elseif ($kelas == 'Teens') {
                $pictId = $api->push_file($childrenName, $fotoIdTeen, $pictExt, $pictureFile);
            } else {
                $pictId = $api->push_file($childrenName, $fotoIdBesar, $pictExt, $pictureFile);
            }
        } else {
            $pict = 'no';
        }

        if ($videoFile->getName() != "") {
            $videoExt = $videoFile->getClientExtension();
            $video = 'yes';
            if($kelas == 'Pratama' && $regionName == 'Kopo'){
                 $videoIds = $api->push_file($childrenName, $videoIdBesar, $videoExt, $videoFile);
            }
            elseif ($kelas == 'Balita' || $kelas == 'Batita' || $kelas == 'Pratama' || $kelas == 'Pratama' || $kelas == 'Daud' || $kelas == 'Samuel' || $kelas == 'Balita/Pratama') {
                $videoIds = $api->push_file($childrenName, $videoIdKecil, $videoExt, $videoFile);
            } elseif ($kelas == 'Teens') {
                $videoIds = $api->push_file($childrenName, $videoIdTeen, $videoExt, $videoFile);
            } else {
                $videoIds = $api->push_file($childrenName, $videoIdBesar, $videoExt, $videoFile);
            }
        } else {
            $video = 'no';
        }
        
        $date_file_name = $this->getDateName();
        $fileNames = explode(" ", $date_file_name);
        $month = $fileNames[1];
        $year = $fileNames[2];
        
        $array = [
            'children_id'   => $childrenId,
            'pembimbing_id' => $pembimbingId,
            'video'         => $video,
            'image'         => $pict,
            'quiz'          => $quiz,
            'zoom'          => $zoom,
            'aba'           => $aba,
            'komsel'        => $komsel,
            'month'         => $month,
            'year'          => $year,
            'sunday_date'   => $date_file_name,
            'id_foto'       => $pictId,
            'id_video'      => $videoIds,
            'created_by'    => user()->toArray()['id'],
        ];

        session()->setFlashData('data', $array);
            
                session()->setFlashData('success_add', "Successfully Add Absensi Of $childrenName !");
    
                return redirect()->to('/absensi/add');
        
        
    }

    public function delete($id)
    {

        try {
            $api = new GoogleApiServices();
            $data = $this->absensiModel->find($id);
            if ($data['id_foto'] != '-') {
                $api->delteFile($data['id_foto']);
            }

            if ($data['id_video'] != '-') {
                $api->delteFile($data['id_video']);
            }
            $this->absensiModel->update($id, [
                'deleted_by' => user()->toArray()['id'],
            ]);
            $this->absensiModel->delete($id);

            session()->setFlashData('success_deleted', 'Absensi Successfully Deleted');
            return redirect()->to('/absensi');
        } catch (\Throwable $th) {
            $this->absensiModel->update($id, [
                'deleted_by' => user()->toArray()['id'],
            ]);
            $this->absensiModel->delete($id);

            session()->setFlashData('success_deleted', 'Absensi Successfully Deleted');
            return redirect()->to('/absensi');
        }
    }

    public function edit($id)
    {
        $canUpdate = true;
        try {
            $token = $this->googleToken->first();
            if ($token == null && user()->toArray()['email'] != 'nathanael.vd@gmail.com') {
                $canUpdate = false;
            } else {
                $api = new GoogleApiServices();
            }
        } catch (\Throwable $th) {
            if (user()->toArray()['email'] == 'nathanael.vd@gmail.com') {
                $id = $this->googleToken->first()['token_id'];

                $delete = $this->googleToken->delete($id);

                if ($delete) {
                    return redirect()->to("/absensi/edit/$id");
                }
            } else {
                $canUpdate = false;
            }
        }

        $data_absensi = $this->absensiModel->getSingleData($id);
        $data_foto = [
            'name'  => null,
        ];

        $data_video = [
            'name'  => null,
        ];

        if ($data_absensi['absensi']['id_foto'] != '-') {
            try {
                $data_foto =    $api->cobagetThumbnailLink($data_absensi['absensi']['id_foto']);
            } catch (\Throwable $th) {
                $data_video = [
                    'name'  => null,
                ];
            }
        }
        if ($data_absensi['absensi']['id_video'] != '-') {
            try {
                $data_video =   $api->cobagetThumbnailLink($data_absensi['absensi']['id_video']);
            } catch (\Throwable $th) {
                $data_foto = [
                    'name'  => null,
                ];
            }
        }

        $data = [
            'title'     => 'Edit Absensi',
            'data'      => $data_absensi,
            'id'        => $id,
            'quiz'      => boolval($this->quiz),
            'zoom'      => boolval($this->zoom),
            'aba'       => boolval($this->aba),
            'komsel'    => boolval($this->komsel),
            'dataFoto'  => $data_foto,
            'dataVideo' => $data_video,
            'update'    => $canUpdate,
        ];

        return view('dashboard/absensi/edit', $data);
    }

    public function update($id)
    {
         $data = [];

       
        $dataAnak = $this->absensiModel->join('childrens', 'childrens.id_children = absensis.children_id')->join('pembimbings', 'pembimbings.id_pembimbing = absensis.pembimbing_id')->join('kelas', 'kelas.id_class = childrens.role')->find($id);

        $data_region = $this->cabangModel->find($this->region);

        $data = [];
        if ($this->request->getFile('foto')->getName() != "" || $this->request->getFile('video')->getName() != "") {
            $api = new GoogleApiServices();

            //// search PPl Kids Name
            $pplParentId = $api->searchPplKidsFolder();

            //// search grouping folder name
            $group = $api->search_group_date_folder('Foto Video Anak - Bulan ' . $dataAnak['month'], $pplParentId);

            //// search date folder 
            $dateFolderId = $api->search_parents_date_folder($dataAnak['sunday_date'], $group);


            //// search region folder 
            $regionFolderId = $api->search_parents_folder($data_region['nama_cabang'], $dateFolderId);

            //// search Besar Folder
            $besarId = $api->search_parents_folder('Besar', $regionFolderId);
            $kecilId = $api->search_parents_folder('Kecil', $regionFolderId);
            $teenId = $api->search_parents_folder('Teens', $regionFolderId);

            ///// search Foto Folder Besar
            $fotoIdBesar = $api->search_parents_folder('Foto', $besarId);
            $fotoIdKecil = $api->search_parents_folder('Foto', $kecilId);
            $fotoIdTeen = $api->search_parents_folder('Foto', $teenId);

            //// search Video Folder
            $videoIdBesar = $api->search_parents_folder('Video', $besarId);
            $videoIdKecil = $api->search_parents_folder('Video', $kecilId);
            $videoIdTeen = $api->search_parents_folder('Video', $teenId);

            $kelas = $dataAnak['nama_kelas'];

            if ($this->request->getFile('foto')->getName() != "") {
                if ($dataAnak['id_foto'] != '-') {
                    try {
                        $api->forceDelete($dataAnak['id_foto']);
                    } catch (\Throwable $th) {
                    }
                }
                $foto = $this->request->getFile('foto');
                $pictExt = $foto->getClientExtension();
                $data['image'] = 'yes';

                if ($kelas == 'Pratama' && $data_region['nama_cabang'] == 'Kopo') {
                    $data['id_foto'] = $api->push_file($dataAnak['children_name'], $fotoIdBesar, $pictExt, $foto);
                } elseif ($kelas == 'Balita' || $kelas == 'Batita' || $kelas == 'Pratama' || $kelas == 'Daud' || $kelas == 'Samuel' || $kelas == 'Balita/Pratama') {
                    $data['id_foto'] = $api->push_file($dataAnak['children_name'], $fotoIdKecil, $pictExt, $foto);
                } elseif ($kelas == 'Teens') {
                    $data['id_foto'] = $api->push_file($dataAnak['children_name'], $fotoIdTeen, $pictExt, $foto);
                } else {
                    $data['id_foto'] = $api->push_file($dataAnak['children_name'], $fotoIdBesar, $pictExt, $foto);
                }
            }

            if ($this->request->getFile('video')->getName() != "") {
                if ($dataAnak['id_video'] != '-') {
                    try {
                        $api->forceDelete($dataAnak['id_video']);
                    } catch (\Throwable $th) {
                    }
                }
                $video = $this->request->getFile('video');
                $data['video'] = 'yes';
                $vidExt = $video->getClientExtension();

                if ($kelas == 'Pratama' && $data_region['nama_cabang'] == 'Kopo') {
                    $data['id_video'] = $api->push_file($dataAnak['children_name'], $videoIdBesar, $vidExt, $video);
                } elseif ($kelas == 'Balita' || $kelas == 'Batita' || $kelas == 'Pratama' || $kelas == 'Pratama' || $kelas == 'Daud' || $kelas == 'Samuel' || $kelas == 'Balita/Pratama') {
                    $data['id_video'] = $api->push_file($dataAnak['children_name'], $videoIdKecil, $vidExt, $video);
                } elseif ($kelas == 'Teens') {
                    $data['id_video'] = $api->push_file($dataAnak['children_name'], $videoIdTeen, $vidExt, $video);
                } else {
                    $data['id_video'] = $api->push_file($dataAnak['children_name'], $videoIdBesar, $vidExt, $video);
                }
            }
        }

        if ($this->request->getVar('zoom')) {
            $data['zoom']   = $this->request->getVar('zoom');
        }

        if ($this->request->getVar('quiz')) {
            $data['quiz']   = $this->request->getVar('quiz');
        }

        if ($this->request->getVar('aba')) {
            $data['aba']    = $this->request->getVar('aba');
        }

        if ($this->request->getVar('komsel')) {
            $data['komsel'] = $this->request->getVar('komsel');
        }

        $data['updated_by'] = user()->toArray()['id'];

        $this->absensiModel->update($id, $data);
        session()->setFlashData('success_update', 'Absensi Successfully Updated');
        return redirect()->to('/absensi');
    }

    public function searchData()
    {
        $data = $this->absensiModel->searchData();
        $cabang = $this->cabangModel->where('id_cabang', $this->region)->select('quiz,zoom,aba,komsel')->first();
        $data['settings'] = $cabang;

        return json_encode($data);
    }

    public function getAbsensiByPembimbing($id)
    {
        $children =  $this->childrenModel->where('id_pembimbing', $id)->get()->getResultArray();
        return json_encode($children);
    }

    public function getDateName()
    {
        date_default_timezone_set("Asia/Bangkok");
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $sunday = strtotime('this sunday');
        $date = date('d', $sunday);
        $date = $date *= 1;
        $sunday_month = date('m', $sunday);
        $sunday_month = $sunday_month *= 1;
        $sunday_month = $bulan[$sunday_month];
        $sunday_year = date('Y', $sunday);
        $sunday = "$date $sunday_month $sunday_year";
        // today date
        $now = strtotime('now');
        $date_now = date('d', $now);
        $date_now = $date_now *= 1;
        $now_month = date('m', $now);
        $now_month = $now_month *= 1;
        $now_month = $bulan[$now_month];
        $now_year = date('Y', $now);
        $now_date = "$date_now $now_month $now_year";

        // thuesday date
        $tuesday = strtotime('next tuesday');
        $tuesday_date = date('d', $tuesday);
        $tuesday_date = $tuesday_date *= 1;
        $tuesday_month = date('m', $tuesday);
        $tuesday_month = $tuesday_month *= 1;
        $tuesday_month = $bulan[$tuesday_month];
        $this_tuesday = date('Y', $tuesday);
        $this_tuesday = "$tuesday_date $this_tuesday $this_tuesday";

        // last date
        $last_sunday = strtotime('last sunday');
        $last_date = date('d', $last_sunday);
        $last_date = $last_date *= 1;
        $last_month = date('m', $last_sunday);
        $last_month = $last_month *= 1;
        $last_month = $bulan[$last_month];
        $last_sundays = date('Y', $last_sunday);
        $las_sundays = "$last_date $last_month $last_sundays";

        $file_name = '';
        if ($tuesday >= $now) {
            if ($date == $date_now) {
                $file_name = $now_date;
            } elseif ($date_now <= $date && $date_now <= $tuesday_date) {
                $file_name = $las_sundays;
            } elseif ($date_now == 31 && $las_sundays == 30 || $las_sundays == 31 || $date < $last_date) {
                $file_name = $las_sundays;
            } else {
                $file_name = $sunday;
            }
        }


        return $file_name;
    }

    public function history()
    {

        if (!in_groups('pusat')) {
            $dataHistory = $this->absensiModel->history()->get()->getResultArray();
            $all_tahun = [];

            foreach ($dataHistory as $history) {
                $all_tahun[] = $history['year'];
            }

            $tahun = array_unique($all_tahun);

            $dataMonth = [];

            foreach ($tahun as $thn) {
                $data_temp = [];
                foreach ($dataHistory as $histo) {
                    if ($histo['year'] == $thn) {
                        $data_temp[] = $histo['month'];
                    }
                }

                $months = array_unique($data_temp);
                foreach ($months as $month) {
                    $dataMonth[] = [
                        'year'  => $thn,
                        'month' => $month,
                    ];
                }
            }


            $data = [
                'title'         => 'Absensi History',
                'datas'         => $dataMonth,
                'years'         => $tahun,
            ];
        } else {
            $dataAbsensi = [];
            $cabang = [];
            $data = $this->absensiModel->join('pembimbings', 'pembimbings.id_pembimbing = absensis.pembimbing_id')
                ->join('cabang', 'cabang.id_cabang = pembimbings.region_pembimbing')
                ->findAll();
            foreach ($data as $d) {
                $dataAbsensi[] = $d['month'] . '-' . $d['year'] . '-' . $d['nama_cabang'];
                $cabang[] = $d['nama_cabang'];
            }

            $dataAbsensi = array_unique($dataAbsensi);
            $cabang = array_unique($cabang);

            $data = [
                'title'         => 'History Absensi',
                'absenHistory'  => $dataAbsensi,
                'cabangs'       => $cabang,
            ];
        }

        return view('dashboard/absensi/history', $data);
    }

    public function searchHistory($params)
    {
        $dataHistory = $this->absensiModel->history()->where('year', $params)->get()->getResultArray();

        $dataMonth = [];

        foreach ($dataHistory as $data) {
            $dataMonth[] = $data['month'];
        }

        $months = array_unique($dataMonth);

        $data = [];

        foreach ($months as $month) {
            $data[] = [
                'year'  => $params,
                'month' => $month,
            ];
        }

        return json_encode($data);
    }

    public function searchAll()
    {
        $dataHistory = $this->absensiModel->history()->get()->getResultArray();
        $all_tahun = [];

        foreach ($dataHistory as $history) {
            $all_tahun[] = $history['year'];
        }

        $tahun = array_unique($all_tahun);

        $dataMonth = [];

        foreach ($tahun as $thn) {
            $data_temp = [];
            foreach ($dataHistory as $histo) {
                if ($histo['year'] == $thn) {
                    $data_temp[] = $histo['month'];
                }
            }

            $months = array_unique($data_temp);
            foreach ($months as $month) {
                $dataMonth[] = [
                    'year'  => $thn,
                    'month' => $month,
                ];
            }
        }

        return json_encode($dataMonth);
    }

    public function export($month, $year)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $data = $this->absensiModel->join('pembimbings', "pembimbings.id_pembimbing = absensis.pembimbing_id")
            ->join('childrens', "childrens.id_children = absensis.children_id")
            ->join('kelas', 'kelas.id_class = childrens.role')
            ->where('region_pembimbing', user()->toArray()['region'])
            ->where('month', $month)
            ->where('year', $year)
            ->get()->getResultArray();

        $tanggal_awal = $data[0]['sunday_date'];
        $data_semua = [];
        $data_beda = [];
        $beda_akhir = [];

        foreach ($data as $absen) {
            if ($absen['sunday_date'] != $tanggal_awal) {
                $data_beda[] = $absen['sunday_date'];
            }

            if ($absen['sunday_date'] == $tanggal_awal) {

               $data_baru = [
                    'Nama Anak'       => $absen['children_name'],
                    'Code Anak'       => $absen['code'],
                    'Kelas'           => $absen['nama_kelas'],
                    'Nama Pembimbing' => $absen['name_pembimbing'],
                    'Absen Foto'      => $absen['image'],
                    'Absen Video'     => $absen['video'],
                    'Children Quiz'   => $absen['quiz'],
                    'Children Zoom'   => $absen['zoom'],
                    'Children ABA'    => $absen['aba'],
                    'Children Komsel' => $absen['komsel'],
                    'Tanggal Minggu'  => $absen['sunday_date'],
                ];
                $data_semua[] = $data_baru;
            }
        }

        $beda_akhir = array_unique($data_beda);

        foreach ($beda_akhir as $tanggal) {
            $data_baru = [
                'Nama Anak'       => ' ',
                'Code Anak'       => ' ',
                'Kelas'           => ' ',
                'Nama Pembimbing' => ' ',
                'Absen Foto'      => ' ',
                'Absen Video'     => ' ',
                'Children Quiz'   => ' ',
                'Children Zoom'   => ' ',
                'Children ABA'    => ' ',
                'Children Komsel' => ' ',
                'Tanggal Minggu'  => ' ',
            ];
            $data_semua[] = $data_baru;

            $datas = $this->absensiModel
                ->join('pembimbings', "pembimbings.id_pembimbing = absensis.pembimbing_id")
                ->join('childrens', "childrens.id_children = absensis.children_id")
                ->join('kelas', 'kelas.id_class = childrens.role')
                ->where('region_pembimbing', user()->toArray()['region'])
                ->where('sunday_date', $tanggal)
                ->get()->getResultArray();

            foreach ($datas as $data) {
                 $data_baru = [
                    'Nama Anak'       => $data['children_name'],
                    'Code Anak'       => $data['code'],
                    'Kelas'           => $data['nama_kelas'],
                    'Nama Pembimbing' => $data['name_pembimbing'],
                    'Absen Foto'      => $data['image'],
                    'Absen Video'     => $data['video'],
                    'Children Quiz'   => $data['quiz'],
                    'Children Zoom'   => $data['zoom'],
                    'Children ABA'    => $data['aba'],
                    'Children Komsel' => $data['komsel'],
                    'Tanggal Minggu'  => $data['sunday_date'],
                ];
                $data_semua[] = $data_baru;
            }
        }

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Anak');
        $sheet->setCellValue('C1', 'Code Anak');
        $sheet->setCellValue('D1', 'Kelas');
        $sheet->setCellValue('E1', 'Nama Pembimbing');
        $sheet->setCellValue('F1', 'Absen Foto');
        $sheet->setCellValue('G1', 'Absen Video');
        $sheet->setCellValue('H1', 'Children Quiz');
        $sheet->setCellValue('I1', 'Children Zoom');
        $sheet->setCellValue('J1', 'Children ABA');
        $sheet->setCellValue('K1', 'Children Komsel');
        $sheet->setCellValue('L1', 'Tanggal Minggu');


        $no = 1;
        $index = 2;
        foreach ($data_semua as $data) {
            if ($data['Nama Anak'] != " ") {
                $sheet->setCellValue('A' . $index, $no++);
            } else {
                $sheet->setCellValue('A' . $index, ' ');
            }
            $sheet->setCellValue('B' . $index, $data['Nama Anak']);
            $sheet->setCellValue('C' . $index, $data['Code Anak']);
            $sheet->setCellValue('D' . $index, $data['Kelas']);
            $sheet->setCellValue('E' . $index, $data['Nama Pembimbing']);
            $sheet->setCellValue('F' . $index, $data['Absen Foto']);
            $sheet->setCellValue('G' . $index, $data['Absen Video']);
            $sheet->setCellValue('H' . $index, $data['Children Quiz']);
            $sheet->setCellValue('I' . $index, $data['Children Zoom']);
            $sheet->setCellValue('J' . $index, $data['Children ABA']);
            $sheet->setCellValue('K' . $index, $data['Children Komsel']);
            $sheet->setCellValue('L' . $index, $data['Tanggal Minggu']);
            $index++;
        }
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(20);

        $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(9);

        $writter = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $getRegionName = $this->cabangModel->getCabang(user()->toArray()['region'])['nama_cabang'];

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Absensi ' . $getRegionName . ' ' . $month . ' ' . $year . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writter->save('php://output');
        die;
    }

    public function chartAbsensi()
    {
        $data = $this->absensiModel->chartAbsensi();
        return json_encode($data);
    }

    public function checkDate()
    {
        $absensi_controller = $this->getDateName();
        $absensi_model = $this->absensiModel->getDateName();

        $data = [
            'title' => 'Check Date Name',
            'absensiController' => $absensi_controller,
            'absensiModel'      => $absensi_model,
        ];

        return view('dashboard/absensi/check', $data);
    }
}
