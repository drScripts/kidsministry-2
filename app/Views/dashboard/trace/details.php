<?= $this->extend('dashboard/bootstraper'); ?>
<?= $this->section('content'); ?>


<div class="container-fluid">
    <h2 class="mb-3">Detail's Record <?= $children_name; ?></h2>
    <table class="table table-bordered" id="dtBasicExample" data-aos="zoom-in" data-aos-duration="500" data-aos-delay="900">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Children Name</th>
                <th>Name Pembimbing</th>
                <th class="text-center">Sunday Date</th>
            </tr>
        </thead>
        <tbody id="body-table">
            <?php
            $no = 1;
            foreach ($data as $d) : ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= $d['children_name']; ?></td>
                    <td><?= $d['name_pembimbing']; ?></td>
                    <td class="text-center"><?= $d['sunday_date']; ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<script src="<?= base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script>
    $('#dtBasicExample').DataTable();
    $('#dtBasicExample_length').addClass('white-fonts');
    $('#dtBasicExample_filter label input').addClass('white-fonts');
    $('#dtBasicExample_filter').addClass('white-fonts');
    $('#dtBasicExample_info').addClass('white-fonts');
    $("#dtBasicExample_paginate").addClass('white-fonts');
</script>
<?= $this->endSection(); ?>