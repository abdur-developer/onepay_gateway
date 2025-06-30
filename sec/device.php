<div class="card mb-5 p-4 border-0">
    <div class="card-body">
        <h5 class="card-title">Your Devices</h5>
        <p class="card-text">Manage the devices connected to your account.</p>
        <?php
            if(!empty($user['device']) && $user['device'] != null) {
                echo "<div class=\"input-group d-flex justify-content-center mb-3\">
                    <button class=\"btn btn-info\" onclick=\"downloadApk()\">Download App</button>
                </div>";
            }
        ?>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="apiKey_device" value="<?=htmlspecialchars($user['device'] ?? "Buy minimum a package")?>" readonly>
            <button class="btn btn-primary copy-btn" onclick="copyApiKey('device')">Copy</button>
        </div>
    </div>
</div>
<script>
    function downloadApk() {
        window.location.href = 'application/app.apk';
    }
</script>