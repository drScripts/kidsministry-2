<?php

use App\Models\CabangModel;
use App\Models\ChildrenModel;
use App\Controllers\AbsensiController;
use App\Models\PembimbingsModel;

$absensiController = new AbsensiController();
$cabangModel = new CabangModel();
$region = $cabangModel->find(user()->toArray()['region'])['nama_cabang'];


$sundayDate = $absensiController->getDateName();
if (!in_groups('pusat')) {
    $childrenModel = new ChildrenModel();
    $pembimbingModel = new PembimbingsModel();


    $anakUltah = [];
    $pembimbingUltah = [];
    $childBirthDay = $childrenModel->birthDayChildren();
    $pembimbingBirthDay = $pembimbingModel->getBirthDay();

    foreach ($pembimbingBirthDay as $pembimbing) {
        if (date('m') == date('m', strtotime($pembimbing['pembimbing_tgl_lahir']))) {
            $pembimbingUltah[] = $pembimbing;
        }
    }

    foreach ($childBirthDay as $child) {
        if (date('m') == date('m', strtotime($child['tanggal_lahir']))) {
            $anakUltah[] = $child;
        }
    }
}

$cabangModel = new CabangModel();
$cabang = $cabangModel->getCabang(user()->toArray()['region'])['nama_cabang'];

?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <a href="javascript:void(0)" class="btn btn-info btn-icon-split">
                <span class="icon text-white">
                    <i class="fas fa-info-circle"></i>
                </span>
                <span class="text">Sunday Date : <?= $sundayDate; ?></span>
            </a>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow mx-1 d-md-block d-sm-block d-xs-block d-lg-none">
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="alert" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-info-circle"></i>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alert">
                <a class="dropdown-item text-center text-gray-500" href="javascript:void(0)">Sunday Date : <?= $sundayDate; ?></a>
            </div>
        </li>
        <?php if (!in_groups('pusat')) : ?>

            <?php if ($region == 'Kopo') : ?>
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="pembimbingUltah" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-universal-access"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter"><?= (count($pembimbingUltah) > 9 ? "9+" : count($pembimbingUltah)) ?></span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="pembimbingUltah">
                        <h6 class="dropdown-header">
                            Pembimbing Birthday Notification
                        </h6>
                        <?php if (empty($pembimbingUltah)) : ?>
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                                <div class="text-center">
                                    <span class="font-weight-bold text-center">Tidak Ada Guru Yang Ulang Tahun Bulan Ini!</span>
                                </div>
                            </a>
                        <?php else : ?>
                            <?php foreach ($pembimbingUltah as $birthDay) : ?>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-birthday-cake text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">
                                            <?= date('M d, Y', strtotime($birthDay['pembimbing_tgl_lahir'])); ?> </div>
                                        <span class="font-weight-bold"><?= $birthDay['name_pembimbing']; ?>, Ulang Tahun Bulan ini!</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>

                        <?php endif; ?>
                        <a class="dropdown-item text-center small text-gray-500" href="javascript:void(0)">End Of Alerts</a>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Nav Item - Alerts Birthday -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-birthday-cake fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter"><?= (count($anakUltah) > 9 ? "9+" : count($anakUltah)) ?></span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Birthday Notification
                    </h6>
                    <?php if (empty($anakUltah)) : ?>
                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                            <div class="text-center">
                                <span class="font-weight-bold text-center">Tidak Ada Yang Ulang Tahun Bulan Ini!</span>
                            </div>
                        </a>
                    <?php else : ?>
                        <?php foreach ($anakUltah as $birthDay) : ?>
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-birthday-cake text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">
                                        <?= date('M d, Y', strtotime($birthDay['tanggal_lahir'])); ?> </div>
                                    <span class="font-weight-bold"><?= $birthDay['children_name']; ?>, Ulang Tahun Bulan ini!</span>
                                </div>
                            </a>
                        <?php endforeach; ?>

                    <?php endif; ?>
                    <a class="dropdown-item text-center small text-gray-500" href="javascript:void(0)">End Of Alerts</a>
                </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>
        <?php endif; ?>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= user()->toArray()['username']; ?></span>
                <img class="img-profile rounded-circle" alt="user admin picture" src="<?= base_url(); ?>/assets/img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= base_url('/settings'); ?>">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>

            </div>
        </li>

    </ul>

</nav>