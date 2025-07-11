<?php
require 'config.php';
// Redirect to login if not logged in
$user_id = $_SESSION['user_id'];
if (!is_logged_in()) {
    redirect('signin.php?php');
}
// Get user details
// var_dump($_SESSION);
if( $_SESSION['user_gulo'] == true){
    $sql = "SELECT * FROM users WHERE id = ?";
}else{
    $sql = "SELECT * FROM admin WHERE id = ?";
}
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway - Pricing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <?php
    if (isset($_GET['error'])) {
        $error_message = htmlspecialchars($_GET['error']);
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '$error_message'
            });
        </script>";
    }
    ?>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="z-index: 999;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-credit-card me-2"></i>
                Payment Gateway
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <?php if($user_id == 0) {
                        $med = [];
                        for ($i = 1; $i <= 3; $i++) {
                            $sql = "SELECT number FROM method WHERE id = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$i]);
                            $med[] = $stmt->fetch()['number'];
                        }
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/users.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" 
                            onclick="addNumber('admin','<?=$med[0]?>','<?=$med[1]?>','<?=$med[2]?>');"
                            >Method</a>
                        </li>
                    <?php
                        }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Documentation</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-outline-light" href="signout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <!--    ============================     -->
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <p>You are logged in as <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        <!--    ============================     -->

        <?php
            // if api key id null, then show the api key generation form
            if($user_id != 0){
                include 'sec/device.php';
                include 'sec/plan.php';
            }elseif($user_id == 0) include 'sec/plan_admin.php';
            else echo '<script>window.location.href = "signin.php?inner";</script>';

        ?>
    </div>

    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-white mb-4">
                        <i class="fas fa-credit-card me-2"></i> Payment Gateway
                    </h5>
                    <p class="text-muted">Secure and reliable payment processing solutions for businesses of all sizes.</p>
                    <div class="mt-4">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Product</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">Features</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">API</a></li>
                        <li><a href="index.html" class="footer-link">Integrations</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">About</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Blog</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Careers</a></li>
                        <li><a href="#" class="footer-link">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p class="text-muted mb-4">Subscribe to our newsletter for the latest updates.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0">© 2023 Payment Gateway. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="footer-link me-3">Terms</a>
                    <a href="#" class="footer-link me-3">Privacy</a>
                    <a href="#" class="footer-link">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Copy API Key functionality
        function copyApiKey(index) {
            const apiKey = document.getElementById('apiKey_' + index);
            apiKey.select();
            apiKey.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            const copyBtn = document.querySelector('.copy-btn');
            copyBtn.classList.add('copied');
            setTimeout(() => {
                copyBtn.classList.remove('copied');
            }, 2000);
        }
        
        function pay(plan) {
            window.location.replace('buy_package.php?plan=' + plan);
        }
        async function addNumber(buyId, bkash, nagad, rocket) {
            // Parse existing values (assuming they're in format "number|type")
            const parseValue = (val) => {
                if (!val) return { number: '', type: 'personal' };
                const parts = val.split('|');
                return { number: parts[0] || '', type: parts[1] || 'personal' };
            };

            const bkashData = parseValue(bkash);
            const nagadData = parseValue(nagad);
            const rocketData = parseValue(rocket);

            const { value: formValues } = await Swal.fire({
                title: "Payment Methods",
                width: '400px',
                html: `
                    <div class="compact-payment-form">
                        <div class="compact-input-group">
                            <input id="swal-input1" type="number" class="compact-input" value="${bkashData.number}" placeholder="Bkash">
                            <select id="swal-select1" class="compact-select">
                                <option value="personal" ${bkashData.type === 'personal' ? 'selected' : ''}>Personal</option>
                                <option value="agent" ${bkashData.type === 'agent' ? 'selected' : ''}>Agent</option>
                                <option value="merchant" ${bkashData.type === 'merchant' ? 'selected' : ''}>Merchant</option>
                            </select>
                        </div>
                        
                        <div class="compact-input-group">
                            <input id="swal-input2" type="number" class="compact-input" value="${nagadData.number}" placeholder="Nagad">
                            <select id="swal-select2" class="compact-select">
                                <option value="personal" ${nagadData.type === 'personal' ? 'selected' : ''}>Personal</option>
                                <option value="agent" ${nagadData.type === 'agent' ? 'selected' : ''}>Agent</option>
                                <option value="merchant" ${nagadData.type === 'merchant' ? 'selected' : ''}>Merchant</option>
                            </select>
                        </div>
                        
                        <div class="compact-input-group">
                            <input id="swal-input3" type="number" class="compact-input" value="${rocketData.number}" placeholder="Rocket">
                            <select id="swal-select3" class="compact-select">
                                <option value="personal" ${rocketData.type === 'personal' ? 'selected' : ''}>Personal</option>
                                <option value="agent" ${rocketData.type === 'agent' ? 'selected' : ''}>Agent</option>
                                <option value="merchant" ${rocketData.type === 'merchant' ? 'selected' : ''}>Merchant</option>
                            </select>
                        </div>
                    </div>
                    
                    <style>
                        .compact-payment-form {
                            display: flex;
                            flex-direction: column;
                            gap: 0.5rem;
                        }
                        .compact-input-group {
                            display: flex;
                            gap: 0.3rem;
                        }
                        .compact-input, .compact-select {
                            padding: 0.3rem;
                            font-size: 0.85rem;
                            border: 1px solid #ddd;
                            border-radius: 3px;
                        }
                        .compact-input {flex: 2;}
                        .compact-select {flex: 1;}
                    </style>
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const values = [];
                    for (let i = 1; i < 4; i++) {
                        const number = document.getElementById(`swal-input${i}`).value.trim();
                        const type = document.getElementById(`swal-select${i}`).value;
                        values.push(`${number}|${type}`);
                    }
                    return values;
                }
            });

            if (formValues) {
                try {
                    const response = await fetch("sec/save_methods.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ buy_id: buyId, methods: formValues })
                    });

                    const result = await response.json();
                    if (result.success) {
                        toast("Saved successfully!");
                        window.location.reload();
                    } else {
                        throw new Error(result.message || "Error saving methods");
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
            Toast.fire({icon: type, title: message});
        }
    </script>
</body>
</html>