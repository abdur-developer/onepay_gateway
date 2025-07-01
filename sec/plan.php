<!-- Pricing Plans -->
<div class="container py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Choose Your Plan</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Select the package that fits your needs and budget.
        </p>
    </div>

    <style>
        .pricing-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            background: white;
            height: 100%;
        }
        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .highlight {
            border-top: 4px solid var(--accent-color);
        }
        .badge-x {
            position: absolute;
            top: 15px;
            right: -32px;
            background-color: var(--accent-color);
            color: white;
            padding: 0.25rem 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            transform: rotate(45deg);
            transform-origin: center;
            width: 120px;
            text-align: center;
        }
        .price-display {
            font-size: 2rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--accent-color);
        }
        .feature-list li {
            margin: 0.4rem 0;
            padding-left: 1.5rem;
            position: relative;
            font-size: 0.9rem;
        }
        .domain-badge {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            display: inline-block;
            margin: 0.2rem;
        }
        .copy-btn.copied {
            background-color: #28a745 !important;
        }
    </style>

    <div class="row g-3">
        <?php
        $userId = $_SESSION['user_id'];
        function isAble($planId, $ck_row) {
            global $pdo, $userId;

            // If not bought, show "Get Started" button
            if (!$ck_row) {
                return "<button class='btn btn-sm btn-success w-100 py-2' onclick='pay(\"$planId\")'>
                            <i class='fas fa-bolt me-1'></i> Get Started
                        </button>";
            }

            // Get pack details
            $buyId = $ck_row['id'];
            $stmt = $pdo->prepare("
                SELECT bp.api, bp.domain_list, bp.bkash, bp.nagad, bp.rocket, p.web_limit 
                FROM buy_pack bp 
                JOIN plans p ON bp.pack_id = p.id 
                WHERE bp.pack_id = ? AND bp.user_id = ?
            ");
            $stmt->execute([$planId, $userId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Domain logic
            $output = '<div class="mb-2">';

            // Ensure $data['domain_list'] is properly initialized
            $domainListJson = json_encode(!empty($data['domain_list']) ? json_decode($data['domain_list'], true) : []);

            // Pass as JS array string
            $output .= "<button class='btn btn-sm btn-outline-primary w-100 mb-2' onclick='addDomain(\"$buyId\", \"{$data['web_limit']}\", $domainListJson)'>
                            <i class='fas fa-plus me-1'></i> Add Domain
                        </button>";
            $output .= '</div>';
            
            $output .= "<button class='btn btn-sm btn-outline-primary w-100 mb-2' onclick='addNumber(\"$buyId\", \"{$data['bkash']}\", \"{$data['nagad']}\", \"{$data['rocket']}\")'>
                            <i class='fas fa-wallet me-1'></i> Add Payment
                        </button>";
            
            // API key field
            $output .= "<div class='input-group input-group-sm mt-2'>
                            <input type='text' class='form-control form-control-sm' id='apiKey_{$planId}' value='" . htmlspecialchars($data['api']) . "' readonly>
                            <button class='btn btn-sm btn-primary copy-btn' onclick='copyApiKey(\"{$planId}\")'>
                                <i class='far fa-copy'></i>
                            </button>
                        </div>";

            return $output;
        }

        $sql = "SELECT * FROM plans WHERE status = 'active' ORDER BY price ASC";
        $result = $pdo->query($sql);
        if (!$result) {
            die("Query failed: " . $pdo->errorInfo()[2]);
        }
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
            // Check if user already bought the pack
            $stmt = $pdo->prepare("SELECT id, time FROM buy_pack WHERE pack_id = ? AND user_id = ?");
            $stmt->execute([$row['id'], $userId]);
            $ck_row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($ck_row){
                //check package validity expires
                $duration = $row['duration'];
                $time = $ck_row['time'];

                date_default_timezone_set('Asia/Dhaka');
                $start_date = date('Y-m-d', strtotime($time));
                $current_date = date('Y-m-d');
                $days_passed = (strtotime($current_date) - strtotime($start_date)) / 86400;

                if ($days_passed >= $duration) {
                    $pdo->query("DELETE FROM buy_pack WHERE id = '{$ck_row['id']}'");
                    $ck_row = null;
                }
            }
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card p-3 <?= $row['badgeName'] ? 'highlight' : '' ?>">
                    <?php if ($row['badgeName']): ?>
                        <span class="badge-x"><?=$row['badgeName']?></span>
                    <?php endif; ?>
                    <div class="card-body p-2 text-center">
                        <h5 class="card-title fw-bold mb-1"><?=$row['name']?></h5>
                        <p class="card-subtitle text-muted small mb-2"><?=$row['description']?></p>

                        <div class="price-display">৳<?=$row['price']?></div>
                        <p class="text-muted small mb-3">for <?=$row['duration']?> days</p>

                        <ul class="feature-list list-unstyled text-start mb-3 px-2">
                            <li><?= $row['web_limit'] ?> Domain Access</li>
                            <li class="disabled">Contains Ads</li>
                            <li>24/7 Support</li>
                        </ul>
                        
                        <?= isAble($row['id'], $ck_row) ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    function copyApiKey(planId) {
        const apiKey = document.getElementById(`apiKey_${planId}`);
        apiKey.select();
        document.execCommand('copy');
        
        const btn = apiKey.nextElementSibling;
        btn.classList.add('copied');
        btn.innerHTML = '<i class="fas fa-check"></i>';
        
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = '<i class="far fa-copy"></i>';
        }, 2000);
    }

    function isValidDomainOrURL(input) {
        let domain;
        // Try to parse as URL first
        try {
            // Add http:// if missing to help URL parser
            const urlInput = input.includes('://') ? input : `http://${input}`;
            const url = new URL(urlInput);
            domain = url.hostname;
        } catch (e) {
            // If URL parsing fails, treat as domain only
            domain = input;
        }
        
        // Remove port if present
        domain = domain.split(':')[0];
        
        // More permissive domain regex that handles most cases
        const domainRegex = /^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z0-9-]{1,63})*(\.[A-Za-z]{2,})?$/;
        
        // Special case for localhost
        if (domain === 'localhost') return true;
        
        // Check if domain is valid
        return domainRegex.test(domain);
    }

    async function addDomain(buyId, webLimit, domains) {
        let domainArr = [];
        try {
            if (!domains) {
                domainArr = [];
            } else if (typeof domains === "string") {
                domainArr = JSON.parse(domains);
            } else if (Array.isArray(domains)) {
                domainArr = domains;
            }
            if (!Array.isArray(domainArr)) domainArr = [];
        } catch (e) {
            domainArr = [];
        }

        const inputsHtml = Array.from({ length: webLimit }, (_, i) =>
            `<input id="swal-input${i}" type="text" class="swal2-input" placeholder="Domain ${i + 1}" value="${domainArr[i] ? domainArr[i] : ''}">`
        ).join("");

        const { value: formValues } = await Swal.fire({
            title: "Add Domains",
            html: inputsHtml,
            focusConfirm: false,
            preConfirm: () => {
                const values = [];
                for (let i = 0; i < webLimit; i++) {
                    const val = document.getElementById(`swal-input${i}`).value.trim();
                    if (val && !isValidDomainOrURL(val)) {
                        Swal.showValidationMessage(`Valid domain required for domain ${i + 1}`);
                        return false;
                    }
                    values.push(val);
                }
                return values;
            }
        });

        if (formValues) {
            try {
                const response = await fetch("sec/save_domains.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({buy_id: buyId, domains: formValues})
                });

                const result = await response.json();
                if (result.success) {
                    toast("Domains saved successfully!");
                    window.location.reload();
                } else {
                    throw new Error(result.message || "Unknown error");
                }
            } catch (error) {
                toast(error.message, "error");
            }
        }
    }
</script>