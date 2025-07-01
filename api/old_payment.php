<?php
  $trx = $_GET['trx'] ?? null;
  if (!$trx) {
    header("Location: index.php", true, 303);
    exit;
  }
  require_once('dbcon.php');
  $sql = "SELECT * FROM payment WHERE trx = '$trx'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) == 0) {
    header("Location: index.php", true, 303);
    exit;
  }
  $row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Secure Payment Gateway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="payment-card">
          <!-- Header -->
          <div class="payment-header position-relative">
            <a onclick="confirmDeletion()" class="close-btn">
              <i class="fas fa-times"></i>
            </a>
            <img src="https://avatars.githubusercontent.com/u/118297000" class="merchant-logo" alt="Merchant Logo"/>
            <h4 class="mb-0 text-white">Abdur Rahman</h4>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs justify-content-center" id="tabMenu" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pay-tab" type="button" role="tab">
                  <i class="fas fa-credit-card me-1"></i> Pay
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#info-tab" type="button" role="tab">
                  <i class="fas fa-info-circle me-1"></i> Info
                </button>
              </li>
            </ul>
          </div>

          <!-- Tab Content -->
          <div class="tab-content">
            <!-- Pay Tab -->
            <div class="tab-pane fade show active" id="pay-tab" role="tabpanel">
              <h5 class="section-title">Choose Payment Method</h5>
              
              <div class="row g-4">
                <div class="col-md-4">
                  <div class="payment-method" onclick="selectPaymentMethod('bkash')">
                    <img src="https://logos-download.com/wp-content/uploads/2022/01/BKash_Logo_icon.png" class="method-icon" alt="Bkash"/>
                    <h6 class="method-name">bKash</h6>
                    <p class="method-type text-danger">Send Money</p>
                    <span class="badge bg-danger bg-opacity-10 text-danger badge-pill">Popular</span>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="payment-method" onclick="selectPaymentMethod('nagad')">
                    <img src="https://logos-download.com/wp-content/uploads/2022/01/Nagad_Logo_full-498x700.png" class="method-icon" alt="Nagad"/>
                    <h6 class="method-name">Nagad</h6>
                    <p class="method-type text-warning">Send Money</p>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="payment-method" onclick="selectPaymentMethod('rocket')">
                    <img src="https://play-lh.googleusercontent.com/sDY6YSDobbm_rX-aozinIX5tVYBSea1nAyXYI4TJlije2_AF5_5aG3iAS7nlrgo0lk8=w240-h480-rw" class="method-icon" alt="Rocket"/>
                    <h6 class="method-name">Rocket</h6>
                    <p class="method-type text-primary">Send Money</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Info Tab -->
            <div class="tab-pane fade" id="info-tab" role="tabpanel">
              <h5 class="section-title">Transaction Details</h5>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="info-card d-flex align-items-center">
                    <div class="info-icon bg-success bg-opacity-10 text-success">
                      <i class="fas fa-key"></i>
                    </div>
                    <div>
                      <h6 class="info-title mb-1"><?= $trx?></h6>
                      <p class="info-subtitle mb-0">Transaction ID</p>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="info-card d-flex align-items-center">
                    <div class="info-icon bg-primary bg-opacity-10 text-primary">
                      <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                      <h6 class="info-title mb-1"><?= $row['amount']?> BDT</h6>
                      <p class="info-subtitle mb-0">Amount</p>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="info-card d-flex align-items-center">
                    <div class="info-icon bg-danger bg-opacity-10 text-danger">
                      <i class="fas fa-file-invoice"></i>
                    </div>
                    <div>
                      <h6 class="info-title mb-1">0.00 BDT</h6>
                      <p class="info-subtitle mb-0">Charge</p>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="info-card d-flex align-items-center">
                    <div class="info-icon bg-success bg-opacity-10 text-success">
                      <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                      <h6 class="info-title mb-1"><?= $row['amount']?> BDT</h6>
                      <p class="info-subtitle mb-0">Total Amount</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="payment-footer text-center">
            <a href="#" class="pay-btn">
              <i class="fas fa-lock"></i> Pay <?= $row['amount']?> BDT
            </a>
            <p class="text-muted mt-3 mb-0 small">
              <i class="fas fa-shield-alt me-1"></i> Secure payment powered by SSL encryption
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function selectPaymentMethod(method) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = "process.php";

      [['trx', "<?= $trx ?>"], ['method', method]].forEach(([name, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
      });

      document.body.appendChild(form);
      form.submit();
    }
    
    function confirmDeletion() {
      if(confirm('Are you sure you want to cancel this payment?')) {
        window.location.replace("<?= $row['cancel_url'].'?my_data='.$row['my_data']?>");
      }
    }


  </script>
</body>
</html>