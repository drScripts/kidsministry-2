<?= $this->renderSection('content'); ?>


<!-- Custom scripts for all pages-->
<script src="<?= base_url(); ?>/assets/js/sb-admin-2.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

<?php if (current_url(true)->getPath() != '/') : ?>
  <script>
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    $(".sidebar").hasClass("toggled");
    $(".sidebar .collapse").collapse("hide");
  </script>
<?php endif; ?>
</body>

</html>