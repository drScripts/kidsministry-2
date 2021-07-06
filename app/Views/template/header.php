<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="apple-touch-icon" href="<?= base_url('/assets/img/Artboard.png'); ?>">
  <link rel="icon" type="image/png" href="<?= base_url('/assets/img/Artboard.png'); ?>">

  <title>
    GBI PPL Absen <?= (isset($title) ? " | $title" : ' '); ?>
  </title>

  <!--     Fonts and icons     -->
  <link href="<?= base_url(); ?>/assets/fontawesome/css/all.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/jquery.dataTables.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/my-css.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/demo/demo.css'); ?>">

  <!-- javascript -->
  <!-- Bootstrap core -->
  <script src="<?= base_url(); ?>/assets/demo/demo.js"></script>
  <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
  <!--  Notifications Plugin    -->
  <script src="<?= base_url(); ?>/assets/js/bootstrap-notify.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="<?= base_url(); ?>/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="<?= base_url(); ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="<?= base_url('/assets/js/popper.min.js'); ?>"></script>
  <!-- Chart JS -->
  <script src="<?= base_url(); ?>/assets/js/chartjs.min.js"></script>

  <!-- sweet alert 2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <!-- Page level plugins -->
  <script src="<?= base_url(); ?>/assets/vendor/chart.js/Chart.min.js"></script>



  <!-- blocking inspect -->
  <!-- <script>
    // Disable inspect element
    $(document).bind("contextmenu", function(e) {
      e.preventDefault();
    });
    $(document).keydown(function(e) {
      if (e.which === 123) {
        return false;
      }

      if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
        return false;
      }
      if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
        return false;
      }
      if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
        return false;
      }
      if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
        return false;
      }
    });
  </script> -->
</head>

<body id="page-top" class="<?= strpos(current_url(true)->getPath(), 'login') !== false || strpos(current_url(true)->getPath(), 'register') !== false || strpos(current_url(true)->getPath(), 'forgot') !== false || strpos(current_url(true)->getPath(), 'reset') !== false ? 'bg-gradient-primary' : ''; ?>">