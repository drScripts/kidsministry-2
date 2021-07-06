<?= $this->extend('dashboard/bootstraper'); ?>
<?= $this->section('content'); ?>


<div class="container-fluid">
    <div class="d-flex justify-content-between">
        <div class="p-2 bd-highlight">
            <div class="row">
                <div class="col-md">

                </div>
            </div>
        </div>
        <?php if (in_groups('pusat')) : ?>
            <div class="p-2 bd-highlight">
                <div class="input-group mb-3">
                    <select id="cabang" class="form-control grey-fonts">
                        <option>Show All Region</option>
                        <?php foreach ($cabangs as $cabang) : ?>
                            <option><?= $cabang; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="card p-3 overflow-auto">
        <table class="table table-bordered" id="dtBasicExample">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Month</th>
                    <th class="text-center">Year</th>
                    <?php if (in_groups('pusat')) : ?>
                        <th class="text-center">Cabang</th>
                    <?php endif; ?>
                    <th class="text-center">Link</th>
                </tr>
            </thead>
            <tbody id="body-table-history">
                <?php $no = 1; ?>


                <?php if (!in_groups('pusat')) : ?>
                    <?php if (count($datas) == 0) : ?>
                        <tr>
                            <td colspan="8" class="text-center">Data Not Found</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($datas as $data) : ?>
                            <tr>
                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td class="text-center">
                                    <?= $data['month']; ?>
                                </td>

                                <td class="text-center">
                                    <?= $data['year']; ?>
                                </td>


                                <td class="td-actions text-center">
                                    <a href="<?= base_url("/export"); ?>/<?= $data['month'] . "/" . $data['year'] ?> " rel="tooltip" class="mr-3 btn btn-success btn-sm btn-round btn-icon">
                                        <i class="far fa-file-excel"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                <?php else : ?>

                    <?php if (count($absenHistory) == 0) : ?>
                        <tr>
                            <td colspan="8" class="text-center">Data Not Found</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($absenHistory as $histo) : ?>
                            <tr>
                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>

                                <td class="text-center">
                                    <?= explode('-', $histo)[0] ?>
                                </td>

                                <td class="text-center">
                                    <?= explode('-', $histo)[1] ?>
                                </td>

                                <td class="text-center">
                                    <?= explode('-', $histo)[2] ?>
                                </td>

                                <td class="td-actions text-center">
                                    <a href="<?= base_url("pusat/export"); ?>/<?= explode('-', $histo)[0] . "/" . explode('-', $histo)[1] . '/' . explode('-', $histo)[2] ?> " rel="tooltip" class="mr-3 btn btn-success btn-sm btn-round btn-icon">
                                        <i class="far fa-file-excel"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                <?php endif; ?>

            </tbody>

        </table>

        <div id="link-history">
        </div>
    </div>
</div>
<?php if (!in_groups('pusat')) : ?>
    <script src="<?= base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
    <script>
        $('#dtBasicExample').DataTable();
        $('#dtBasicExample_length').addClass('white-fonts');
        $('#dtBasicExample_filter label input').addClass('white-fonts');
        $('#dtBasicExample_filter').addClass('white-fonts');
        $('#dtBasicExample_info').addClass('white-fonts');
        $("#dtBasicExample_paginate").addClass('white-fonts');
    </script>
<?php else : ?>
    <script src="<?= base_url('/assets/js/logics/pusat.js'); ?>"></script>
    <script>
        pusat.pusatHistoryAbsensiPagination();
        pusat.pusatHistoryCabangOption();
    </script>
<?php endif ?>
<!-- pagination -->
<?= $this->endSection(); ?>