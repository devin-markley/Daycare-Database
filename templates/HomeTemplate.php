<?php foreach ($providers as $provider) { ?>
    <a href="index.php?page=provider&provider_id=<?= $provider['provider_id'] ?>"><button><?= $provider['provider_name'] ?></button></a>
<?php } ?>

