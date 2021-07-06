<?= $this->extend('dashboard/bootstraper'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 data-aos="fade-right" data-aos-duration="500" data-aos-delay="300" class="text-capitalize">Details <?= $absensis['children_name']; ?></h1>


    <h4 class="white-fonts">Children Name : <?= $absensis['children_name']; ?></h4>
    <h4 class="white-fonts">Pembimbing : <?= $absensis['name_pembimbing']; ?></h4>
    <h4 class="white-fonts">Cabang : <?= $absensis['nama_cabang']; ?></h4>
    <h4 class="white-fonts">Absen Video : <?= $absensis['video']; ?></h4>
    <h4 class="white-fonts">Absen Foto : <?= $absensis['image']; ?></h4>
    <?php if ($quiz) : ?>
        <h4 class="white-fonts">Absen Quiz : <?php if ($absensis['quis'] == '-') {
                                                    echo 'Tidak Ada';
                                                } else {
                                                    echo $absensis['quis'];
                                                } ?></h4>
    <?php endif; ?>
    <?php if ($zoom) : ?>
        <h4 class="white-fonts">Absen Zoom : <?php if ($absensis['zooms'] == '-') {
                                                    echo 'Tidak Ada';
                                                } else {
                                                    echo $absensis['zooms'];
                                                } ?></h4>
    <?php endif; ?>
    <?php if ($aba) : ?>
        <h4 class="white-fonts">Absen ABA : <?php if ($absensis['abas'] == '-') {
                                                echo 'Tidak Ada';
                                            } else {
                                                echo $absensis['abas'];
                                            } ?></h4>
    <?php endif; ?>
    <?php if ($komsel) : ?>
        <h4 class="white-fonts">Absen Komsel : <?php if ($absensis['komsels'] == '-') {
                                                    echo 'Tidak Ada';
                                                } else {
                                                    echo $absensis['komsels'];
                                                } ?></h4>
    <?php endif; ?>
    <h4 class="white-fonts">Absen Untuk : <?= $absensis['sunday_date']; ?></h4>
    <h4 class="white-fonts">Di Absen Oleh : <?= $absensis['username']; ?></h4>
    <h4 class="white-fonts">Di Absen Pada Tanggal : <?= date('d M Y', strtotime($absensis['absensiCreatedAt'])) ?></h4>
    <?php if ($absensis['absensiCreatedAt'] != $absensis['absensiUpdatedAt']) : ?>
        <?php if ($absensis['absensiUpdateBy'] != null) : ?>
            <h4 class="white-fonts">Di Update Oleh : <?= $absensis['absensiUpdateBy']; ?></h4>
        <?php else : ?>
            <h4 class="white-fonts">Di Update Oleh : <?= $absensis['username']; ?></h4>
        <?php endif; ?>
        <h4 class="white-fonts">Di Update Pada : <?= date('d M Y', strtotime($absensis['absensiUpdatedAt'])) ?></h4>
    <?php endif; ?>

</div>
<?= $this->endSection(); ?>