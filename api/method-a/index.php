<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Helvetica, sans-serif;
        }
        .input-form {
            border: 1px solid #8d8ecb;
            border-radius: 26px;
            padding: 0 23px;
            min-height: 48px;
            line-height: 48px;
            width: 100%;
        }
        .input-form:focus {
            border: 1px solid #8d8ecb;
            background: transparent;
        }
        .main-btn {
            margin: 10px;
            height: 56px;
            background:#0d9c49;
            box-shadow: 0 3px 6px rgba(180, 184, 204, .5);
            border-radius: 26px;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
        }
        .method-box {
            border: 1px solid #8d8ecb;
            border-radius: 26px;
            margin-top: 9px;
            padding: 5px 25px 15px 25px;
            background: white;
        }
        .note {
            font-size: 13px;
            color: #313381;
            font-weight: 700;
            margin-bottom: 15px;
            opacity: 0.52;
        }
        .note::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgb(49, 51, 129);
            position: relative;
            top: -10px;
            left: -21px;
        }
        .form-box {
            border: 1px solid #8d8ecb;
            border-radius: 26px;
            padding: 0 23px;
            min-height: 48px;
            width: 100%;
            line-height: 48px;
        }
        .copy {
            color: rgb(162, 163, 236);
            font-weight: 500;
            text-decoration-line: underline;
            font-size: 15px;
            cursor: pointer;
        }
        p.any_issue {
            margin: 0;
            font-size: 11px;
            line-height: 30px;
            min-height: 30px;
        }
        input:focus-visible {
            border: 1px solid #707070;
            outline: none;
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
         section.number_popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #00000061;
            transition: .4s;
        }
        .number_popUp_container {
            position: absolute;
            width: 250px;
            height: 200px;
            background: #fff;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 20px;
        }
        button.btn_p {
            border: none;
            background: #466ef2;
            padding: 6px 11px;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
        }
        p.p_pera {
            margin: 10px 11px;
            font-size: 13px;
            text-align: center;
            line-height: 23px;
        }
        button.btn_p.btn_p_can {
            background: transparent;
            border: 2px solid #466ef2;
            color: #466ef2;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://onepays.news/checkoutdata/onepay/img/topbg.6b8f4100.png" style="height: 172px; width: 100%">
    </header>
    <div style="margin-top: -125px;" class="px-4">
        <div class="">
            <div class="d-flex align-items-center" style="gap: 5px;">
                <img src="https://onepays.news/checkoutdata/onepay/img/pixlogo.2432dd36.png" style="height: 28px; width: 28px;">
                <p class="mb-0 text-white" style="font-size: 21px;">ONEPAY</p>
            </div>
            <p class="mt-2 mb-2" style="font-size: 13px;color: rgba(255, 255, 255, 0.6);">
                অর্ডারের পরিমাণ
            </p>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <div class="">
                <p class="mb-0" style="text-align:center;font-size: 30px; color: #fff;font-family: 'Gilroy-Heavy',sans-serif;font-weight: 700;">
                    <?= $row['amount'] ?> TK
                </p>
            </div>
            <h6 class="one_pay_timer text-white mb-0" style="font-size: 19px;" id="timer">
                <!-- <span class="time">04</span><span class="separator">:</span><span class="time">26</span> -->
            </h6>
        </div>
        <div style="margin-top: 45px;">
            <div class="mb-4" style="border-left: 1px solid rgb(49, 51, 129); padding-left: 19px;">
                <div class="note">
                    ধাপ 1. পেমেন্টের তথ্য কপি করুন
                </div>
                <div class="note">
                    ধাপ 2. আপনি যে পরিমাণ রিচার্জ করতে চান তা 
                    <span class="d-block ps-2">
                    <?= $method['name'] ?> Send Money মাধ্যমে আমাদের কাছে ট্রান্সফার করুন।
                    </span>
                </div>
                <div class="note">
                    ধাপ 3. রিচার্জ সম্পূর্ণ করতে দয়া করে TxnID <span class="d-block ps-2"> লিখুন</span>
                </div>
            </div>
            <p class="any_issue text-end text-danger" onclick="change_number()" style="cursor: pointer;"> অ্যাকাউন্ট ত্রুটি, স্যুইচ করতে ক্লিক করুন</p>
            <div class="">
                <div style="font-size: 13px; color: #b8b8b8;"> <span class="text-danger">*</span>
                    OnePay শুধুমাত্র পেমেন্ট সংগ্রহ পরিষেবা প্রদান করে
                </div>
                <div class="my-2 form-box d-flex align-items-center justify-content-between" id="copyNumber">
                    <div>
                        <span class="copy_pay_number bg-transparent border-0" id="nagad-number"><?= isset($method['number']) ? $method['number'] : '' ?></span>
                    </div>
                    <div class="copy" onclick="copyToClipboard()">
                        কপি
                    </div>
                </div>
                <form action="" method="post" class="form" id="paymentForm">
                    <div style="font-size: 13px; color: #b8b8b8;">
                        <span class="text-danger">*</span> অর্থপ্রদানের পরে অনুগ্রহ করে আপনার [TxnID] অনুলিপি করুন
                    </div>
                    <div class="mt-3">
                        <input type="text" class="form-box" name="manual_trx_id" id="trxid" placeholder="8-সংখ্যার TxnID" required>
                        <input type="hidden" name="original-trx" id="original-trx" value="<?= isset($_POST['trx']) ? $_POST['trx'] : '' ?>">
                        <input type="hidden" name="method" id="method" value="nagad">
                    </div>
                </form>
                <div class="main-btn text-center" id="go_payment_btn">
                    নিশ্চিত করুন
                </div>
            </div>
        </div>
    </div>
    <section class="number_popup" style="display: none;">
        <div class="number_popUp_container">
            <div class="text-center">
                <img style="width: 55px;margin-top: 8px;" src="https://onepays.news/checkoutdata/onepay/assets/img/tri.png">
            </div>

            <div>
                <p class="p_pera">বর্তমান রিসিভিং অ্যাকাউন্ট অস্বাভাবিক এবং পেমেন্ট করতে পারে না? আমার কি প্রাপক পরিবর্তন করতে হবে?</p>
            </div>

            <div class="mt-3 d-flex justify-content-between mx-4">
                <button class="btn_p" onclick="confirm_change()" type="button">নিশ্চিত</button>
                <button class="btn_p btn_p_can" onclick="can_btn()" type="button">বাতিল করুন</button>
            </div>
        </div>
    </section>
    <div class="status-message"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function change_number(){
            document.querySelector('.number_popup').style.display = 'block';
        }
        function confirm_change(){
            history.back()
        }
        function can_btn(){
            document.querySelector('.number_popup').style.display = 'none';
        }
        // Initialize timer
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            const interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    // Timer expired action if needed
                }
            }, 1000);
        }

        // Copy to clipboard function
        function copyToClipboard() {
            const textToCopy = document.getElementById('nagad-number').textContent;
            navigator.clipboard.writeText(textToCopy).then(function() {
                showStatusMessage('কপি করা হয়েছে!', 'success');
            }, function(err) {
                showStatusMessage('কপি করতে ব্যর্থ হয়েছে: ' + err, 'error');
            });
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Start timer (5 minutes)
            const fiveMinutes = 60 * 5;
            const display = document.querySelector('#timer');
            startTimer(fiveMinutes, display);

            // Attach event listeners
            document.getElementById('go_payment_btn').addEventListener('click', verifyPayment);
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                verifyPayment();
            });
        });
    </script>
</body>
</html>