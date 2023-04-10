<div>
    <div class="nav-bar">
        <div class="brand-name">
            <p>Western Slope Daycare Management</p>
        </div>
        <div class="nav-buttons">
            <div class="provider-buttons">
                <?php foreach ($_data['providers'] as $provider): ?>
                    <a href="index.php?page=provider&provider_id=<?= $provider['provider_id'] ?>"><button>
                            <?= $provider['provider_name'] ?>
                        </button></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>