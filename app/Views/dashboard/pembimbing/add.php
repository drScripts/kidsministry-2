<?= $this->extend('dashboard/bootstraper'); ?>
<?= $this->section('content'); ?>



<?php
if ($validation->hasError('pembimbing_name') || $validation->hasError('pembimbing_tgllahir')) {
    echo "
    <script>
        demo.dangerNotification('top', 'right', '<b>Failed Add Pembimbing</b><br>Check Your Input');
    </script>
    ";
};

?>
<div class="container-fluid">
    <h1 data-aos="fade-right" data-aos-duration="500" data-aos-delay="500">Add Pembimbing</h1>
    <form action="<?= base_url('/pembimbing/insert'); ?>" method="POST">
        <?= csrf_field(); ?>
        <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="700">
            <label for="pembimbing" class="white-fonts">Pembimbing Name</label>
            <input type="text" name="pembimbing_name" class="<?= ($validation->hasError('pembimbing_name')) ? 'is-invalid' : ''; ?> form-control" placeholder="Input Children Name" id="pembimbing" value="<?= old('pembimbing_name'); ?>" />
            <div class="invalid-feedback ">
                <?= $validation->getError('pembimbing_name'); ?>
            </div>
        </div>
        <?php if ($region == 'Kopo') : ?>
            <div class="row">
                <div class="col-5">
                    <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="700">
                        <label for="tglLahir" class="white-fonts">Pembimbing Tanggal Lahir</label>
                        <input type="date" name="pembimbing_tgllahir" class="<?= ($validation->hasError('pembimbing_tgllahir')) ? 'is-invalid' : ''; ?> form-control" id="tglLahir" value="<?= old('pembimbing_tgllahir'); ?>" />
                        <div class="invalid-feedback ">
                            <?= $validation->getError('pembimbing_tgllahir'); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group form-check">
            <button type="submit" class="btn btn-primary mt-3" data-aos="fade-right" data-aos-duration="500" data-aos-delay="900">Submit</button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>