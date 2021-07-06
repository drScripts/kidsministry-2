<?php

use App\Models\CabangModel;

$cabangModel = new CabangModel();
$cabang = $cabangModel->getCabang(user()->toArray()['region'])['nama_cabang'];


?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="javascript:void(0)">
        <div class="sidebar-brand-icon">
            <i class="fas fa-fw fa-church"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?php
                                                if (in_groups('pusat')) {
                                                    echo strtoupper('hq');
                                                } elseif (in_groups('superadmin')) {
                                                    echo strtoupper('sa');
                                                } else {
                                                    echo strtoupper('ad');
                                                }
                                                ?> <?php
                                                    if ($cabang == "Kopo") {
                                                        echo $cabang;
                                                    } else {
                                                        echo strtoupper($cabang);
                                                    }
                                                    ?></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= (current_url(true)->getPath() === '/' ? 'active' : ' '); ?>">
        <a class="nav-link" href="<?= base_url(); ?>">
            <i class="fas fa-chart-area"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Main Settings
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item <?= (strpos(current_url(true)->getPath(), 'children') !== false ? 'active' : ' '); ?>">
        <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-child"></i>
            <span>Childrens</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('/children') ?>">Data Childrens</a>
                <?php if (!in_groups('pusat')) : ?>
                    <h6 class="collapse-header">Data Management:</h6>
                    <a class="collapse-item" href="<?= base_url('children/add'); ?>">Add Childrens</a>
                    <a class="collapse-item" href="<?= base_url('/children/export'); ?>">Get Children Data</a>
                    <?php if (in_groups('superadmin')) : ?>
                        <a class="collapse-item" href="<?= base_url('/children/import'); ?>">Import Children Data</a>
                    <?php endif ?>
                <?php endif; ?>
                <h6 class="collapse-header">Children Trace:</h6>
                <a class="collapse-item" href="<?= base_url('/children/trace'); ?>">Absensi Children Trace</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item <?= (strpos(current_url(true)->getPath(), 'pembimbing') !== false ? 'active' : ' '); ?>">
        <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Pembimbing</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('/pembimbing'); ?>">Data Pembimbing</a>
                <?php if (!in_groups('pusat')) : ?>
                    <h6 class="collapse-header">Data Management:</h6>
                    <a class="collapse-item" href="<?= base_url('pembimbing/add'); ?>">Add Data Pembimbing</a>
                    <a class="collapse-item" href="<?= base_url('/pembimbing/export'); ?>">Get Data Pembimbing</a>
                <?php endif; ?>
            </div>
        </div>
    </li>

    <li class="nav-item <?= (strpos(current_url(true)->getPath(), 'absensi') !== false || strpos(current_url(true)->getPath(), 'history')  !== false ? 'active' : ' '); ?>">
        <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseAbsensi" aria-expanded="true" aria-controls="collapseAbsensi">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Absensi</span>
        </a>
        <div id="collapseAbsensi" class="collapse" aria-labelledby="headingAbsensi" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url('/absensi'); ?>">Data Absensi</a>
                <h6 class="collapse-header">Data Management:</h6>
                <?php if (!in_groups('pusat')) : ?>
                    <a class="collapse-item" href="<?= base_url('absensi/add'); ?>">Add Absensi</a>
                <?php else : ?>
                    <a class="collapse-item" href="<?= base_url('/pusat/tracking'); ?>">Tracking Absen</a>
                <?php endif; ?>
                <h6 class="collapse-header">History Absensi:</h6>
                <a class="collapse-item" href="<?= base_url('/history'); ?>">History Absensi</a>
            </div>
        </div>
    </li>

    <?php if (in_groups('admin') || in_groups('pusat')) : ?>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Children Ranking
        </div>
        <!-- Nav Item - Tables -->
        <li class="nav-item <?= (strpos(current_url(true)->getPath(), 'rank') !== false ? 'active' : ' '); ?>">
            <a class="nav-link" href="<?= base_url('/rank'); ?>">
                <i class="fas fa-fw fa-trophy"></i>
                <span>Ranking</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_groups('superadmin') || in_groups('pusat')) : ?>
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            User Management
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item <?= (strpos(current_url(true)->getPath(), 'team') !== false ? 'active' : ' '); ?>">
            <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-users"></i>
                <span>Admin</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="<?= base_url('/team'); ?>">User Data</a>
                </div>
            </div>
        </li>
    <?php endif; ?>
    <!-- Divider -->


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>