<?= $this->extend('dashboard/bootstraper'); ?>
<?= $this->section('content'); ?>


<?php
if ($validation->hasError('pembimbing_name')) {
    echo "
    <script>
        demo.dangerNotification('top', 'right', '<b>Failed Update Pembimbing</b><br>Check Your Input');
    </script>
    ";
};

?>
<div class="container-fluid">
    <h1 data-aos="fade-right" data-aos-duration="500" data-aos-delay="300">Edit Pembimbing</h1>
    <form class="mt-3" action="<?= base_url('pembimbing/update') . '/' . $id; ?>" method="POST">
        <?= csrf_field(); ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="500">
            <label for="pemimbing-name" class="white-fonts">Pembimbing Name</label>
            <input type="text" name="pembimbing_name" class="<?= ($validation->hasError('pembimbing_name')) ? 'is-invalid' : ''; ?> form-control" placeholder="Input Pembimbing Name" id="children-name" value="<?= (old('pembimbing_name')) ? old('pembimbing_name') : $pembimbing['name_pembimbing']; ?>" />

            <div class="invalid-feedback ">
                <?= $validation->getError('pembimbing_name'); ?>
            </div>
        </div>
        <div class="form-group form-check">
            <button type="submit" class="btn btn-primary mt-3" data-aos="fade-right" data-aos-duration="500" data-aos-delay="700">Submit</button>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>