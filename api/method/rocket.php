<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rocket Payment Gateway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="next.css">
  <style>
    :root {
      --primary-color: #f600b9;
      --secondary-color: #e41beb;
      --dark-color: #2d2d2d;
      --light-color: #f8f9fa;
      --success-color: #28a745;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="payment-card animation-pulse">
          <!-- Header -->
          <div class="payment-header">
            <div class="logo-container">
              <img src="https://www.dutchbanglabank.com/img/mlogo.png" alt="Rocket Logo" class="logo" style="border-radius: 30%"/>
            </div>
            <h4>Secure Payment with Rocket</h4>
          </div>

          <!-- Payment Instructions -->
          <div class="payment-body">
            <div class="step">
              <div class="step-number">1</div>
              <div class="step-content">
                <strong>Dial *322#</strong> on your mobile phone
                <div class="text-muted small mt-1">Open your Rocket mobile menu</div>
              </div>
            </div>
            
            <div class="step">
              <div class="step-number">2</div>
              <div class="step-content">
                Select <strong>Send Money</strong> option
                <div class="text-muted small mt-1">From the Rocket menu options</div>
              </div>
            </div>
            
            
            <div class="step">
              <div class="step-number">3</div>
              <div class="step-content">
                Enter the recipient Rocket number
                <div class="copy-section">
                  <span class="copy-text" id="rocket-number"><?=$method['number']?></span>
                  <button class="copy-btn" onclick="copyToClipboard('rocket-number','<?=$method['number']?>')">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
              </div>
            </div>
            
            <div class="step">
              <div class="step-number">4</div>
              <div class="step-content">
                Enter the payment amount
                <div class="copy-section">
                  <span class="copy-text" id="amount"><?=$row['amount']?> BDT</span>
                  <button class="copy-btn" onclick="copyToClipboard('amount', '<?=$row['amount']?>')">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
              </div>
            </div>
            
            <div class="step">
              <div class="step-number">5</div>
              <div class="step-content">
                Enter your <strong>Rocket PIN</strong> to confirm
                <div class="text-muted small mt-1">Keep your PIN secure</div>
              </div>
            </div>
            
            <div class="step">
              <div class="step-number">6</div>
              <div class="step-content">
                You'll receive a <strong>transaction confirmation</strong>
                <div class="text-muted small mt-1">Save this for your records</div>
              </div>
            </div>

            <!-- Transaction ID Input -->
            <div class="mt-4">
              <label for="trxid" class="form-label">Enter Your Transaction ID</label>
              <input type="text" class="form-control" id="trxid" placeholder="TRX123456789"/>
              <input type="hidden" id="original-trx" value="<?= htmlspecialchars($row['trx']) ?>">
              <div class="form-text">Please enter the 10-12 digit transaction ID you received</div>
            </div>

            <!-- Submit Button -->
            <button class="submit-btn" onclick="verifyPayment()">
              <i class="fas fa-paper-plane me-2"></i> <span class="btn-text">Verify Payment</span>
              <span class="btn-spinner"></span>
            </button>
            
            <div class="note">
              <i class="fas fa-lock me-2"></i> Your payment is secured with Nagad
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="status-message"></div>
  <div class="spinner-container" id="loading-spinner">
    <div class="loading-spinner"></div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="method/script.js"></script>

  <script>
    function verifyPayment() {
      "use strict";

      const trxid = document.getElementById('trxid')?.value?.trim();
      const originalTrx = document.getElementById('original-trx')?.value?.trim();
      const submitBtn = document.querySelector('.submit-btn');
      const loadingSpinner = document.getElementById('loading-spinner');
      const statusMessage = document.querySelector('.status-message');

      if (!trxid) {
        showStatusMessage('Please enter your transaction ID', 'error');
        document.getElementById('trxid').focus();
        return;
      }

      if (!originalTrx) {
        showStatusMessage('Original transaction reference is missing', 'error');
        return;
      }

      submitBtn.classList.add('loading');
      loadingSpinner.style.display = 'flex';

      const params = new URLSearchParams();
      params.append('input-trx', trxid);
      params.append('post-trx', originalTrx);

      fetch('check.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: params.toString(),
        credentials: 'same-origin'
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`Server responded with status: ${response.status}`);
        }
        return response.text();
      })
      .then(data => {
        if (data === 'success') {
          showStatusMessage('Payment verified successfully! Redirecting...', 'success');
          setTimeout(() => {
            window.location.replace('success.php?trx='+ originalTrx);
          }, 2000);
        } else if (data === 'invalid') {
          showStatusMessage('Invalid transaction ID. Please check and try again.', 'error');
        } else if (data === 'exists') {
          showStatusMessage('This transaction ID has already been used.', 'error');
        } else {
          showStatusMessage('Unexpected response from server. Please try again.', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showStatusMessage(`Failed to verify payment: ${error.message}`, 'error');
      })
      .finally(() => {
        submitBtn.classList.remove('loading');
        loadingSpinner.style.display = 'none';
      });
    }

    function showStatusMessage(message, type) {
      const statusMessage = document.querySelector('.status-message');
      const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
      
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
      alertDiv.role = 'alert';
      alertDiv.innerHTML = `
        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      
      // বিদ্যমান অ্যালার্ট মুছে ফেলুন
      while (statusMessage.firstChild) {
        statusMessage.removeChild(statusMessage.firstChild);
      }
      
      statusMessage.appendChild(alertDiv);
      
      // স্বয়ংক্রিয়ভাবে অ্যালার্ট বন্ধ হয়ে যাবে
      setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
      }, 5000);
    }
  </script>
</body>
</html>