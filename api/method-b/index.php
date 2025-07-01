<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Onepay Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f8f8;
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 50px;
    }
    .top-banner {
      background-image: url('https://onepays.news/checkoutdata/onepay/img/topbg.6b8f4100.png');
      background-size: cover;
      background-position: center;
      padding: 40px 20px 50px;
      text-align: center;
      display: flex;
    }
    .top-banner img {
      max-width: 40px;
      border-radius: 50%;
    }
    .switch-text {
      text-align: center;
      font-size: 14px;
      color: #f48b00;
      margin-top: 10px;
      margin-bottom: 10px;
    }
    .alert-danger {
      background-color: #ffe6e6;
      border: 1px solid #ff9999;
      color: #b30000;
      font-size: 14px;
      font-weight: 600;
    }
    .info-box {
      background-color: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px;
      margin-top: 20px;
    }
    .info-box div {
      margin-bottom: 10px;
    }
    .info-box b {
      display: inline-block;
      min-width: 70px;
      color: #000;
    }
    .countdown {
      color: #000;
      font-weight: bold;
    }
    label.form-label {
      font-size: 15px;
      color: #d20000;
    }
    input.form-control {
      border-radius: 8px;
      font-size: 15px;
      height: 45px;
    }
    .submit-btn {
      background-color: #888;
      color: white;
      font-weight: bold;
      border: none;
      padding: 12px;
      border-radius: 30px;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
    }
    .submit-btn:hover {
      background-color: #555;
    }
    .status-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 500px;
        z-index: 1000;
    }
  </style>
</head>
<body>
  <div class="top-banner">
    <img src="https://onepays.news/checkoutdata/onepay/img/pixlogo.2432dd36.png" alt="Onepay Logo">
    <h3 class="text-light mx-2">Onepay</h3>
  </div>

  <div class="status-message"></div>
  <div class="container">
    <div class="switch-text">
      অ্যাকাউন্টটি ফ্রিজ, স্যুইচ করতে ক্লিক করুন &gt;
    </div>
    <style>
      .alert-dekhao img {
        width: 25px;
        height: 25px;
        vertical-align: middle;
      }
      .alert-dekhao span {
        font-weight: 700;
      }
      .info-bal img {
        width: 20px;
        height: 20px;
        margin-left: 10px;
      }
      .zoom-loop {
            animation: zoomInOut 2s infinite ease-in-out;
        }

        @keyframes zoomInOut {
        0%, 100% {
            transform: scale(1);   /* Normal size */
        }
        50% {
            transform: scale(1.2); /* Zoomed in */
        }
        }
    </style>
    <div class="alert-dekhao">
      <img src="https://uxwing.com/wp-content/themes/uxwing/download/signs-and-symbols/exclamation-warning-round-red-icon.svg" alt="">
      <span>সতর্কতা</span>
    </div>
    <div style="font-weight: 600; color: #f55941" class="zoom-loop">
      অনুগ্রহ করে একই ওয়ালেট দিয়ে অর্থপ্রদান করুন এবং ব্যবহৃত এজেন্টে সঠিক TxnID পূরণ করুন <br>
      অনুগ্রহ করে নিচে দেওয়া অ্যাকাউন্ট নম্বরে অর্থপ্রদান করুন
    </div>
    <div class="row info-bal mt-3">
      <div class="col-6 text-end">
        <div>মানিব্যাগঃ</div>
        <div>হিসাবঃ</div>
        <div>পরিমাণঃ</div>
        <div>কাউন্টডাউনঃ</div>
      </div>
      <div class="col-6">        
        <div><span class="text-dark"><?=$_POST['method']?></span></div>
        <div onclick="copyKro(this)">
          <a href="#" class=""><?=$method['number']?></a>
          <img src="https://cdn-icons-png.flaticon.com/128/54/54702.png"/>
        </div>
        <div onclick="copyKro(this)">
          <span class="text-success"><?=$row['amount']?></span> 
          <img src="https://cdn-icons-png.flaticon.com/128/54/54702.png"/>
        </div>
        <div><span class="countdown" id="timer">05:00</span></div>
        <script>
          function copyKro(element) {
              const target = element.querySelector('a, span');
              if (!target) {
                  alert('কপি করার জন্য কোনো তথ্য পাওয়া যায়নি।');
                  return;
              }
              const textToCopy = target.textContent;              
              if (!textToCopy) return;
              const parts = textToCopy.split('|');
              const x = { number: parts[0] || '', type: parts[1] || 'personal' };

              navigator.clipboard.writeText(x['number']);
              alert('কপি করা হয়েছে: ' + x['number']);
          }
        </script>
      </div>
    </div>

    <form class="mt-3" method="POST" id="paymentForm">
        <label for="trxid" class="form-label">সঠিক TxnID পূরণ করুন</label>
        <input type="text" name="manual_trx_id" class="form-control mb-3"  id="trxid" placeholder="৮-সংখ্যার TxnID" required>
        <input type="hidden" name="original-trx" id="original-trx" value="<?= isset($_POST['trx']) ? $_POST['trx'] : '' ?>">
        <input type="hidden" name="method" id="method" value="<?=$_POST['method']?>">
        <div class="submit-btn" id="go_payment_btn">নিশ্চিত করুন</div>
    </form>
  </div>

  <script>
    // Verify payment function
    async function verifyPayment() {
        const trxid = document.getElementById('trxid').value.trim();
        const originalTrx = document.getElementById('original-trx').value.trim();
        const submitBtn = document.getElementById('go_payment_btn');

        if (!trxid) {
            showStatusMessage('অনুগ্রহ করে আপনার লেনদেন আইডি লিখুন', 'error');
            document.getElementById('trxid').focus();
            return;
        }

        if (!originalTrx) {
            showStatusMessage('মূল লেনদেন রেফারেন্স অনুপস্থিত', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> যাচাই করা হচ্ছে...';

        try {
            const response = await fetch('check.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'input-trx': trxid,
                    'post-trx': originalTrx
                })
            });

            if (!response.ok) {
                throw new Error(`সার্ভার রেসপন্স: ${response.status}`);
            }

            const data = await response.text();

            if (data === 'success') {
                showStatusMessage('পেমেন্ট সফলভাবে যাচাই করা হয়েছে! রিডাইরেক্ট হচ্ছে...', 'success');
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'complete.php';

                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'trx';
                inp.value = originalTrx;
                form.appendChild(inp);

                document.body.appendChild(form);
                form.submit();
            } else if (data === 'invalid') {
                showStatusMessage('অবৈধ লেনদেন আইডি। অনুগ্রহ করে চেক করে আবার চেষ্টা করুন।', 'error');
            } else if (data === 'exists') {
                showStatusMessage('এই লেনদেন আইডি ইতিমধ্যে ব্যবহার করা হয়েছে।', 'error');
            } else {
                showStatusMessage('সার্ভার থেকে অপ্রত্যাশিত রেসপন্স। আবার চেষ্টা করুন।', 'error');
            }
        } catch (error) {
            console.error('ত্রুটি:', error);
            showStatusMessage(`পেমেন্ট যাচাই করতে ব্যর্থ: ${error.message}`, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'নিশ্চিত করুন';
        }
    }
// Show status message
        function showStatusMessage(message, type) {
            const statusMessage = document.querySelector('.status-message');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                <strong>${type === 'success' ? 'সফল!' : 'ত্রুটি!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Clear existing alerts
            while (statusMessage.firstChild) {
                statusMessage.removeChild(statusMessage.firstChild);
            }
            
            statusMessage.appendChild(alertDiv);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        }
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Attach event listeners
        document.getElementById('go_payment_btn').addEventListener('click', verifyPayment);
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            verifyPayment();
        });
    });
    // =====================================
    let time = 300;
    const timerDisplay = document.getElementById('timer');

    const updateTimer = () => {
      const minutes = String(Math.floor(time / 60)).padStart(2, '0');
      const seconds = String(time % 60).padStart(2, '0');
      timerDisplay.textContent = `${minutes}:${seconds}`;
      if (time > 0) {
        time--;
      } else {
        clearInterval(countdownInterval);
        timerDisplay.textContent = 'সময় শেষ';
      }
    };

    const countdownInterval = setInterval(updateTimer, 1000);
    updateTimer();
  </script>
</body>
</html>