<?= $this->include('template/header'); ?>

<div id="wrapper">
  <?= $this->include('template/sidebar_new'); ?>

  <div id="content-wrapper" class="d-flex flex-column">
    <!-- Navbar -->
    <!-- End Navbar -->
    <!-- content -->
    <div id="content">
      <?= $this->include('template/navbar_new'); ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-chart" data-aos="zoom-in" data-aos-duration="500" data-aos-delay="300">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6 text-left">
                    <h5 class="card-category" data-aos="fade-left" data-aos-duration="500" data-aos-delay="600">Total
                      Absensi </h5>
                    <h2 class="card-title" data-aos="fade-left" data-aos-duration="500" data-aos-delay="700">Absensi Tahun
                      <?= date("Y"); ?>
                    </h2>
                  </div>
                  <?php if (in_groups('pusat')) : ?>
                    <div class="col-sm-6" data-aos="fade-left" data-aos-duration="500" data-aos-delay="800">
                      <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                        <label id="1" class="ml-2">
                          <select id="cabang" class="form-control card-select">
                            <option>See All Result</option>
                            <?php foreach ($cabangs as $cabang) : ?>
                              <option><?= $cabang; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </label>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="card-body" data-aos="zoom-in" data-aos-duration="500" data-aos-delay="900">
                <div class="chart-area" id="canvas1">
                  <canvas id="chartBig1"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <div class="card card-chart" data-aos="zoom-in" data-aos-duration="500" data-aos-delay="300">
              <div class="card-header ">
                <div class="row">
                  <div class="col-sm-6 text-left">
                    <h5 class="card-category" data-aos="fade-left" data-aos-duration="500" data-aos-delay="600">Total Absensi</h5>
                    <h2 class="card-title" data-aos="fade-left" data-aos-duration="500" data-aos-delay="700">Mingguan</h2>
                  </div>
                  <div class="col-sm-6" data-aos="fade-left" data-aos-duration="500" data-aos-delay="800">
                    <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                      <?php if (in_groups('pusat')) : ?>
                        <label id="1" class="ml-2">
                          <select id="cabang1" class="form-control card-select">
                            <option>See All Result</option>
                            <?php foreach ($cabangs as $cabang) : ?>
                              <option><?= $cabang; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </label>
                      <?php endif; ?>
                      <?php if (!in_groups('pusat')) : ?>
                        <label id="0" class="ml-2">
                          <select id="kelas" class="form-control card-select">
                            <option>All Kelas</option>
                            <?php foreach ($kelas as $c) : ?>
                              <option><?= $c; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </label>
                      <?php endif; ?>
                      <label id="0" class="ml-2">
                        <select id="month" class="form-control card-select">
                          <?php foreach ($month as $m) : ?>
                            <option><?= $m; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" data-aos="zoom-in" data-aos-duration="500" data-aos-delay="900">
                <div class="chart-area" id="canvas2">
                  <canvas id="chartBig2"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- content end -->
      </div>
    </div>
    <?= $this->include('template/footer'); ?>
  </div>
</div>
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="<?= base_url('/logout') . '/' . user()->toArray()['id']; ?>">Logout</a>
      </div>
    </div>
  </div>
</div>
<?php if (!in_groups('pusat')) : ?>
  <?php if ($notif_birthday == 0 && count($children_birthday) != 0) {
    echo "
      <script>
        Swal.fire({
          title: 'Ada " . count($children_birthday) . " Anak Yang Ulang Tahun Bulan Ini !',
          width: 700,
          padding: '3em',
          allowOutsideClick: false,
          imageUrl: 'https://i.gifer.com/3TsW.gif',
          backdrop: `
            rgba(0,0,123,0.4)
            url('" . base_url('assets/img/confe.gif') . "')
            left top
          `
        }).then((result) => {
          if(result.isConfirmed){
            $.ajax({
              url: '/settings/notifyBirthday',
              type: 'POST',
              data: {
                  'status': $notif_birthday,
              },
              headers: {
                  'X-Requested-With': 'XMLHttpRequest',
              },
              dataType: 'json',
              success: function(data) {
                  if (data.success) {
                      demo.successNotification('top', 'left', '<b>Notification !</b><br> ' + data.success);
                  } else {
                      demo.successNotification('top', 'left', '<b>Notification !</b><br> ' + data.failed);
                  }
              }
          });
      } else {
          }
        });
      </script>
      ";
  } ?>

  <?php if (count($pembimbingUltah) != 0 && $region == 'Kopo') : ?>
    <script>
      Swal.fire({
        title: 'Ada <?= count($pembimbingUltah); ?> Guru Yang Ulang Tahun Bulan Ini !',
        width: 700,
        padding: '3em',
        allowOutsideClick: false,
        imageUrl: 'https://i.gifer.com/3TsW.gif',
        backdrop: `
            rgba(0,0,123,0.4)
            url('" . base_url('assets/img/confe.gif') . "')
            left top
          `
      });
    </script>
  <?php endif; ?>
  <script src="<?= base_url('assets/js/logics/index.js'); ?>"></script>
<?php else : ?>
  <script src="<?= base_url('/assets/js/logics/pusat.js'); ?>"></script>
  <script>
    pusat.initAllCountly();
    $('#cabang').on('change', function() {
      $('#canvas1').html('');
      $('#canvas1').html(`
        <canvas id="chartBig1"></canvas>
        `);

      pusat.initAllCountly($(this).val());
    });


    let cabang = $('#cabang1').val();
    let month = $('#month').val();

    pusat.initChartMonthly(month, cabang);

    $('#cabang1').on('change', function() {
      $('#canvas2').html('');
      $('#canvas2').html(`
        <canvas id="chartBig2"></canvas>
        `);
      let cabang = $(this).val();

      pusat.updateMonthCabang($(this).val());


    });

    $('#month').on('change', function() {
      $('#canvas2').html('');
      $('#canvas2').html(`
        <canvas id="chartBig2"></canvas>
        `);

      let cabang = $('#cabang1').val();
      let month = $(this).val();

      pusat.initChartMonthly(month, cabang);
    });
  </script>
<?php endif; ?>

<?= $this->include('template/footers'); ?>