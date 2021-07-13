<?= $this->extend('dashboard/bootstraper'); ?>
<?= $this->section('content'); ?>



<?php
if ($validation->hasError('pembimbing') || $validation->hasError('children') || $validation->hasError('quiz') || $validation->hasError('video') || $validation->hasError('picture')) {
    echo "
    <script>
        demo.dangerNotification('top', 'right', '<b>Failed Add Absensi</b><br>Check Your Input');
    </script>
    ";
}

if ($validation->hasError('video')) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $validation->getError('video') . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
            </button>
         </div>';
    echo  "<script>
    Swal.fire({
    icon: 'error',
    title: 'Failed !',
    text: 'If The Error Because The Limit Of File Size Just Contact Me!', 
  })</script>";
}

if ($validation->hasError('picture')) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $validation->getError('picture') . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
            </button>
          </div>';
    echo  "<script>
    Swal.fire({
    icon: 'error',
    title: 'Failed !',
    text: 'If The Error Because The Limit Of File Size Just Forward The File To My WhatshApp!', 
  })</script>";
}

if (session()->getFlashData('success_add')) {
    echo "<script>
        Swal.fire({
        icon: 'success',
        title: 'Success !',
        text: '" . session()->getFlashData('success_add') . "', 
      })</script>";
}

date_default_timezone_set("Asia/Bangkok");

if (date('D') == 'Wed' || date('D') == 'Thu' || date('D') == 'Fri' || date('D') == 'Sat') {
    echo '<div class="alert alert-warning alert-with-icon" data-notify="container"> 
    <span data-notify="icon" class="tim-icons icon-alert-circle-exc"></span>
    <span data-notify="message">Data kemungkinan tidak akan masuk ke youtube namun tetap tersimpan</span>
  </div>';
} elseif (date('D') == 'Tue') {
    if (strtotime('now') > (strtotime('today') + 17 * 60 * 60 + 30 * 60)) {

        echo '<div class="alert alert-warning alert-with-icon" data-notify="container"> 
        <span data-notify="icon" class="tim-icons icon-alert-circle-exc"></span>
        <span data-notify="message">Data kemungkinan tidak akan masuk ke youtube namun tetap tersimpan</span>
      </div>';
    }
}


?>

<div class="container-fluid">
    <h1 data-aos="fade-right" data-aos-duration="500" data-aos-delay="300">Add Absensi</h1>
    <form action="<?= base_url('/absensi/insert'); ?>" method="POST" enctype="multipart/form-data">

        <?= csrf_field(); ?>

        <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="500">
            <div class="d-block">
                <input type="checkbox" id="costume-date">
                <label for="costume-date" class="white-fonts">Costume Date</label>
            </div>
            <div id="costume-date-row">
                <label for="override-date" class="white-fonts">Pick Costum Date</label>
                <select name="costume_date" id="override-date" class="form-control grey-fonts" disabled>
                    <option value="">Select Date</option>
                    <?php foreach ($date as $key => $d) : ?>
                        <optgroup label="Bulan <?= $key; ?>">
                            <?php foreach ($d as $dates) : ?>
                                <option>
                                    <?= $dates; ?>
                                </option>
                            <?php endforeach ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>


        <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="500">
            <label for="pembimbing-select" class="white-fonts">Pembimbing Name</label>
            <select name="pembimbing" id="pembimbing-select" class="form-control grey-fonts <?= ($validation->hasError('pembimbing')) ? 'is-invalid' : ''; ?>" required>
                <option value="">Select Pembimbing Name</option>
                <?php foreach ($pembimbings as $pembimbing) : ?>
                    <option value="<?= $pembimbing['id_pembimbing']; ?>">
                        <?= $pembimbing['name_pembimbing']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback ">
                <?= $validation->getError('pembimbing'); ?>
            </div>
        </div>

        <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="700">
            <label for="children-select" class="white-fonts ">Children Name</label>
            <select name="children" id="children-select" class="form-control grey-fonts <?= ($validation->hasError('children')) ? 'is-invalid' : ''; ?>" required>

            </select>
            <div class="invalid-feedback ">
                <?= $validation->getError('children'); ?>
            </div>
        </div>

        <?php if ($quiz) : ?>
            <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="900" id="quiz-field">
                <label for="quiz-select" class="white-fonts">Children Quiz</label>
                <select name="quiz" id="quiz-select" class="form-control grey-fonts <?= ($validation->hasError('quiz')) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select Children Quiz</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <div class="invalid-feedback ">
                    <?= $validation->getError('quiz'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($zoom) : ?>
            <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="900" id="zoom-field">
                <label for="zoom-select" class="white-fonts">Children Zoom</label>
                <select name="zoom" id="zoom-select" class="form-control grey-fonts <?= ($validation->hasError('zoom')) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select Children Zoom</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('zoom'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($komsel) : ?>
            <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" data-aos-delay="900" id="komsel-field">
                <label for="komsel-select" class="white-fonts">Children komsel</label>
                <select name="komsel" id="komsel-select" class="form-control grey-fonts <?= ($validation->hasError('komsel')) ? 'is-invalid' : ''; ?>" required>
                    <option value="">Select Children komsel</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <div class="invalid-feedback">
                    <?= $validation->getError('komsel'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($aba) : ?>
            <div class="form-group form-check" data-aos="fade-right" data-aos-duration="500" id="aba-field">
                <label for="zoom-select" class="white-fonts">Children ABA</label>
                <div class="container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="1" value="1" checked>
                                    <label class="form-check-label white-fonts" for="1">
                                        1
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="2" value="2">
                                    <label class="form-check-label white-fonts" for="2">
                                        2
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="3" value="3">
                                    <label class="form-check-label white-fonts" for="3">
                                        3
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="4" value="4">
                                    <label class="form-check-label white-fonts" for="4">
                                        4
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="5" value="5">
                                    <label class="form-check-label white-fonts" for="5">
                                        5
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="6" value="6">
                                    <label class="form-check-label white-fonts" for="6">
                                        6
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aba" id="7" value="7">
                                    <label class="form-check-label white-fonts" for="7">
                                        7
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback">
                    <?= $validation->getError('aba'); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-center mt-5">
            <div class="row text-center">
                <div class="col " data-aos="fade-right" data-aos-duration="500" id="img-field">
                    <div class="form-group">
                        <label for="images">
                            <div class="card text-center " id='image' style="width: 31.5rem;">
                                <div class="card-body ">
                                    <img src="https://kesagami.com/wp-content/plugins/complete-gallery-manager/images/gallery_icon@2x.png" width="200px" height="200px" class="" alt="add picture">
                                    <input type="file" hidden id="images" accept="image/*" name='picture'>
                                    <h4 class="white-fonts mt-3 mb-2" id="image-name">Pick An Image</h4>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col " data-aos="fade-left" data-aos-duration="500" id="vid-field">
                    <div class="form-group">
                        <label for="videos">
                            <div class="card " id="video" style="width: 31.5rem;">
                                <div class="card-body ">
                                    <img src="https://www.freeiconspng.com/uploads/movie-icon-3.png" width="200px" height="200px" alt="add picture" class="">
                                    <input type="file" hidden id="videos" accept="video/*" name="video">
                                    <h4 class="white-fonts mt-3 mb-2" id="video-name">Pick An Video</h4>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary mt-3 mb-3" id="submit-buttons">Submit</button>
        </div>
    </form>
</div>
<?php if (!$update) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'The Google Api Token is Expired! Please Contact The Admin',
            confirmButtonText: 'Refresh',
            showCancelButton: true,
            allowOutsideClick: false,
            cancelButtonText: 'Back',
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            } else {
                window.history.back();
                return false;
            }
        });
    </script>
<?php endif; ?>

<script src="<?= base_url('/assets/js/logics/absensi.js'); ?>"></script>

<?php if ($region_name == 'Kopo') : ?>
    <script src="<?= base_url('/assets/js/logics/costume-absensi-kelas.js'); ?>"></script>
<?php endif; ?>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
                $('#image').css('background-image', 'url(' + e.target.result + ')');
                $('#image').css('background-size', 'contain');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#images").change(function() {
        readURL(this);
        const file = $(this).val();
        var filename = file.split(/[\\/]/g).pop().split('.')[0];
        $('#image-name').html(filename);
    });

    $('#videos').on('change', function() {
        const file = $(this).val();
        var filename = file.split(/[\\/]/g).pop().split('.')[0];
        $('#video-name').html(filename);
    });
</script>
<?= $this->endSection(); ?>