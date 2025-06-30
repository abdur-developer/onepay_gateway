<!-- Pricing Plans -->
 <div class="text-center mb-5">
    <h2 class="fw-bold mb-3">Choose Your Perfect Plan</h2>
    <p class="lead text-muted mx-auto" style="max-width: 600px;">
        Select the package that fits your needs and budget. Start with a free trial or go premium for full features.
    </p>
</div>
<style>
    .badge-x{
        position: absolute;
        top: 10px;
        right: -30px;
        background-color: var(--accent-color);
        color: white;
        padding: 0.25rem 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        transform: rotate(45deg);
        transform-origin: center;
    }
    li{
        margin: 0 0 -10px 0;
        padding: 0;
    }
</style>
<div class="row g-4">
    
    <?php
        function isAble($planId) {
            global $pdo;
            $sql = "SELECT id FROM buy_pack WHERE pack_id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$planId, $_SESSION['user_id']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($stmt->rowCount() == 0) {
                return "<button class='btn btn-success w-100' onclick='pay(\"$planId\")' >
                    <i class='fas fa-rocket me-1'></i> Get Started
                </button>";
            } else {
                $buy_id = $row['id'];
                $sql = "SELECT bp.api, bp.domain_list, p.web_limit FROM buy_pack bp JOIN plans p ON bp.pack_id = p.id WHERE bp.pack_id = ? AND bp.user_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$planId, $_SESSION['user_id']]);
                $api = $stmt->fetch(PDO::FETCH_ASSOC);

                $domains = json_decode($api['domain_list'], true);
                $domainInputs = '';
                if($domains === null || !is_array($domains)) {
                    $domainInputs.= "<div class='input-group d-flex justify-content-center mb-3'>
                        <button class='btn btn-primary' onclick='addDomain(\"$buy_id\", \"".$api['web_limit']."\")'>Add Domain</button>
                    </div>";
                }else{
                    foreach ($domains as $index => $domain) {
                        $domainInputs .= '<p class="m-0 p-0">'.htmlspecialchars($domain).'</p>';
                    }
                }

                return $domainInputs.'<div class="input-group mb-3 mt-2">
                    <input type="text" class="form-control" id="apiKey_'.$planId.'" value="'.htmlspecialchars($api['api']).'" readonly>
                    <button class="btn btn-primary copy-btn" onclick="copyApiKey(\''.$planId.'\')">Copy</button>
                </div>';
            }
        }
        $sql = "SELECT * FROM plans WHERE status = 'active' ORDER BY price ASC";
        $result = $pdo->query($sql);
        if (!$result) {
            die("Query failed: " . $pdo->errorInfo()[2]);
        }
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="col-lg-4">
                <div class="card p-4 highlight">
                    <span class="badge-x"><?=$row['badgeName']?></span>
                    <div class="card-body text-center">
                        <h4 class="card-title"><?=$row['name']?></h4>
                        <p class="card-subtitle text-muted mb-1"><?=$row['description']?></p>

                        <div class="price-display text-danger">৳<?=$row['price']?></div>
                        <p class="text-muted mb-1">for <?=$row['duration']?> Days</p>

                        <ul class="feature-list list-unstyled text-start mb-4">
                            <li><?= $row['web_limit'] ?> Domain Access</li>
                            <!-- <li>1 Device Connection</li> -->
                            <!-- <li>1000 Transactions Limit</li> -->
                            <li class="disabled">Contains Ads</li>
                            <li>24/7 Basic Support</li>
                        </ul>
                        <?= isAble($row['id']) ?>
                        
                    </div>
                </div>
            </div>
        <?php } ?>
</div>
<script>
    async function addDomain(buyId, webLimit) {
        const inputsHtml = Array.from({ length: webLimit }, (_, i) =>
            `<input id="swal-input${i}" class="swal2-input" placeholder="Domain ${i + 1}">`
        ).join("");

        const { value: formValues } = await Swal.fire({
            title: "Add Domains",
            html: inputsHtml,
            focusConfirm: false,
            preConfirm: () => {
                const values = [];
                for (let i = 0; i < webLimit; i++) {
                    const val = document.getElementById(`swal-input${i}`).value.trim();
                    if (!val) {
                        Swal.showValidationMessage(`Domain ${i + 1} is required`);
                        return false;
                    }
                    values.push(val);
                }
                return values;
            }
        });

        // Send to PHP using fetch
        if (formValues) {
            try {
                const response = await fetch("sec/save_domains.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        buy_id: buyId,
                        domains: formValues
                    })
                });

                const result = await response.json();
                if (result.success) {
                    toast("Domains saved successfully!");
                } else {
                    throw new Error(result.message || "Unknown error");
                }
            } catch (error) {
                toast(error.message, "error");
            }
        }
    }
    function toast(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({icon: type,title: message});
    }
</script>

