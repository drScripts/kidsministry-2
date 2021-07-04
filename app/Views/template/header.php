<!--
=========================================================
* * Black Dashboard - v1.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/black-dashboard
* Copyright 2019 Creative Tim (https://www.creative-tim.com)


* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" href="<?= base_url('/assets/img/Artboard.png'); ?>">
  <link rel="icon" type="image/png" href="<?= base_url('/assets/img/Artboard.png'); ?>">
  <title>
    GBI PPL Absen <?= (isset($title) ? " | $title" : ' '); ?>
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="<?= base_url(); ?>/assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?= base_url(); ?>/assets/css/black-dashboard.min.css?v=1.0.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="<?= base_url(); ?>/assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/my-css.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/jquery.dataTables.min.css'); ?>">

  <script src="<?= base_url(); ?>/assets/js/core/jquery.min.js"></script>
  <script src="<?= base_url(); ?>/assets/js/core/popper.min.js"></script>
  <!--   Core JS Files   -->
  <script src="<?= base_url(); ?>/assets/js/core/bootstrap.min.js"></script>
  <script src="<?= base_url(); ?>/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <!-- Chart JS -->
  <script src="<?= base_url(); ?>/assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="<?= base_url(); ?>/assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?= base_url(); ?>/assets/js/black-dashboard.min.js?v=1.0.0"></script><!-- Black Dashboard DEMO methods, don't include it in your project! -->
  <script src="<?= base_url(); ?>/assets/demo/demo.js"></script>
  <script src="https://kit.fontawesome.com/01059e4e8b.js" crossorigin="anonymous"></script>
  <!-- sweet alert 2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script data-ad-client="ca-pub-9996873752925387" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <!-- blocking inspect -->
   <script>
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
  </script>
</head>

<body class="">