<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bkash Payment Gateway</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="next.css">
  <style>
    :root {
      --primary-color: #e2136e;
      --secondary-color: #ff7bac;
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
              <img src="https://www.bkash.com/images/bkash_grey_thumbnail.svg" alt="Bkash Logo" class="logo"/>
            </div>
            <h4>Secure Payment with bKash</h4>
          </div>

          <!-- Payment Instructions -->
          <div class="payment-body">
            <div class="step">
              <div class="step-number">1</div>
              <div class="step-content">
                <strong>Dial *247#</strong> on your mobile phone
                <div class="text-muted small mt-1">Open your bKash mobile menu</div>
              </div>
            </div>
            
            <div class="step">
              <div class="step-number">2</div>
              <div class="step-content">
                Select <strong>Send Money</strong> option
                <div class="text-muted small mt-1">From the bKash menu options</div>
              </div>
            </div>
            
            <div class="step">
              <div class="step-number">3</div>
              <div class="step-content">
                Enter the recipient bKash number
                <div class="copy-section">
                  <span class="copy-text" id="bkash-number"><?=$method['number']?></span>
                  <button class="copy-btn" onclick="copyToClipboard('bkash-number','<?=$method['number']?>')">
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
                Enter your <strong>bKash PIN</strong> to confirm
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
              <div class="form-text">Please enter the 10-12 digit transaction ID you received</div>
            </div>

            <!-- Submit Button -->
            <button class="submit-btn">
              <i class="fas fa-paper-plane me-2"></i> Verify Payment
            </button>
            
            <div class="note">
              <i class="fas fa-lock me-2"></i> Your payment is secured with bKash
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="method/script.js"></script>
</body>
</html>