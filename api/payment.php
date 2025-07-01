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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/gilroy-bold" rel="stylesheet">
    <meta name="csrf-token" content="6xhlNNhkLh5ZP13p7aa9FzMF52PN7IhiFnER6djs">
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
            background:rgb(0, 153, 13);
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
            z-index: 10;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://onepays.news/checkoutdata/onepay/img/topbg.6b8f4100.png" style="height: 115px; width: 100%">
    </header>
    <div style="margin-top: -73px;" class="px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center" style="gap: 5px;">
                <img src="https://onepays.news/checkoutdata/onepay/img/pixlogo.2432dd36.png" style="height: 28px; width: 28px;">
                <p class="mb-0 text-white" style="font-size: 21px;">ONEPAY</p>
            </div>
            <h6 class="one_pay_timer text-white mb-0" style="font-size: 19px;" id="timer">
                <!-- <span class="time">04</span><span class="separator">:</span><span class="time">43</span> -->
            </h6>
        </div>
        <div style="margin-top: 70px;">
            <p style="font-size: 13px; color: #bbbbbb;" class="mb-3">
                অর্ডার আইডি: <?php echo htmlspecialchars($row['trx']); ?>
            </p>
            <p class="mb-2" style="text-align:center;font-size: 30px; color: #313381;font-family: 'Gilroy-Heavy',sans-serif;font-weight: 700;">
                <?= $row['amount']?> BDT
            </p>
            <div class="mt-3" style="position: relative; margin-top: 28px;">
                <div style="padding: 0 3px; position: absolute; left: 28px; top: -9px; background: #fff; font-size: 13px; color: #6f6f6f;">
                    আপনার অ্যাকাউন্ট
                </div>
                <input type="text" placeholder="016xxxxx345" class="input-form mb-3" id="account_number">
            </div>
            <div style="position: relative;">
                <div class="mt-3" style="position: relative; margin-top: 28px;">
                    <div style="padding: 0 3px; position: absolute; left: 28px; top: -9px; background: #fff; font-size: 13px; color: #6f6f6f;">
                        পেমেন্ট চ্যানেল 
                    </div>
                    <div class="input-form mb-3 d-flex align-items-center justify-content-between" id="input-box" style="cursor: pointer;">
                        <div id="method_selected">
                            <div style="font-size: 13px; color: #9e9e9e;">
                                আপনার পেমেন্ট চ্যানেল নির্বাচন করুন
                            </div>
                        </div>
                        <img src="/checkoutdata/onepay/img/xl.png" style="height: 13px; width: 13px;">
                    </div>
                </div>
                <div class="method-box d-none" id="method_box">
                    <?php
                        $bkash = true; $nagad = true; $rocket = true;
                        if($row['isUser'] != 0 && $row['isUser'] != null){
                            $sql = "SELECT rocket, bkash, nagad FROM `buy_pack` WHERE id = '{$row['isUser']}'";
                            $buy_pack = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                            if(!$buy_pack['bkash']) $bkash = false;
                            if(!$buy_pack['nagad']) $nagad = false;
                            if(!$buy_pack['rocket']) $rocket = false;
                        }
                    
                        if($bkash){ ?> 
                            <div class="d-flex align-items-center py-2 payment-option" onclick="selectMethod('bkash', 'https://checkout-bdt.onepay.news/static/Collection/bkash.png')" style="padding: 16px 0px; gap: 8px; cursor: pointer;">
                                <img src="https://checkout-bdt.onepay.news/static/Collection/bkash.png" style="height: 23px; width: 23px; border-radius: 5px;" alt="Bkash">
                                <div style="color: #313381; font-size: 12px; font-weight: 700;">
                                    Bkash
                                </div>
                            </div>
                        <?php
                        }
                    if($nagad){ ?>
                        <div class="d-flex align-items-center py-2 payment-option" onclick="selectMethod('nagad', 'https://onepays.news/uploads/24/10/1812520512990336.png')" style="padding: 16px 0px; gap: 8px; cursor: pointer;">
                            <img src="https://onepays.news/uploads/24/10/1812520512990336.png" style="height: 23px; width: 23px; border-radius: 5px;" alt="Nagad">
                            <div style="color: #313381; font-size: 12px; font-weight: 700;">
                                Nagad
                            </div>
                        </div>
                    <?php
                        }
                    if($rocket){ ?>
                        <div class="d-flex align-items-center py-2 payment-option" onclick="selectMethod('rocket', 'https://play-lh.googleusercontent.com/sDY6YSDobbm_rX-aozinIX5tVYBSea1nAyXYI4TJlije2_AF5_5aG3iAS7nlrgo0lk8=w240-h480-rw')" style="padding: 16px 0px; gap: 8px; cursor: pointer;">
                            <img src="https://play-lh.googleusercontent.com/sDY6YSDobbm_rX-aozinIX5tVYBSea1nAyXYI4TJlije2_AF5_5aG3iAS7nlrgo0lk8=w240-h480-rw" style="height: 23px; width: 23px; border-radius: 5px;" alt="Rocket">
                            <div style="color: #313381; font-size: 12px; font-weight: 700;">
                                Rocket
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            
            <form method="post" action="process.php" id="paymentForm">
                <input type="hidden" name="trx" value="<?php echo htmlspecialchars($trx); ?>">
                <input type="hidden" name="method" id="method" value="">
                <input type="hidden" name="account" id="account" value="">
                
                <div style="font-size:11px; color: #999999; margin-bottom: 10px; margin-top: 30px;">
                    মিলনাদানী পরামর্শ:
                </div>
                <div style="font-size:11px; color: #999999; margin-bottom: 10px;">
                    1.একটি ব্যাংক অ্যাকাউন্ট প্রাপ্ত করার জন্য কোন ব্যাংক নাম চয়ন করুন, এবং তথ্য অনুযায়ী টাকা স্থানান্তর করতে হবে সম্পর্কিত ব্যাংক নাম এবং ব্যাংক অ্যাকাউন্টে।;
                </div>
                <div style="font-size:11px; color: #999999; margin-bottom: 10px;">
                    2. একই ফোন নম্বর একাধিক ব্যাংক অ্যাকাউন্টে সংযোজিত করা যেতে পারে। আপনার পছন্দের অন্য কোন ব্যাংক অ্যাকাউন্টে অর্থ স্থানান্তর করবেন না।;
                </div>
                <div style="font-size:11px; color: #999999; margin-bottom: 10px;">
                    <span class="text-danger">*গুরুত্বপূর্ণ স্মরণকারী:</span>
                    যদি অর্থ একই ফোন নম্বরের সাথে অন্য একটি ব্যাংকে স্থানান্তরিত করা হয়, তবে অর্থগুলি<br> আপনার অ্যাকাউন্টে ক্রেডিট করা হতে পারে না।
                </div>
                <div class="main-btn text-center" id="go_payment_btn">
                    পেমেন্ট যান
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle payment method dropdown
        document.getElementById('input-box').addEventListener('click', function() {
            document.getElementById('method_box').classList.toggle('d-none');
        });
        
        // Select payment method
        function selectMethod(methodName, methodImage) {
            document.getElementById('method').value = methodName;
            document.getElementById('method_selected').innerHTML = `
                <div class="d-flex align-items-center" style="gap: 8px;">
                    <img src="${methodImage}" style="height: 23px; width: 23px; border-radius: 5px;">
                    <div style="color: #313381; font-size: 12px; font-weight: 700;">
                        ${methodName}
                    </div>
                </div>
            `;
            document.getElementById('method_box').classList.add('d-none');
        }
        
        // Submit payment form
        document.getElementById('go_payment_btn').addEventListener('click', function() {
            const accountNumber = document.getElementById('account_number').value.trim();
            const paymentMethod = document.getElementById('method').value;
            
            if (!accountNumber) {
                alert('অনুগ্রহ করে আপনার অ্যাকাউন্ট নম্বর লিখুন');
                return;
            }
            
            if (!paymentMethod) {
                alert('অনুগ্রহ করে একটি পেমেন্ট পদ্ধতি নির্বাচন করুন');
                return;
            }
            
            document.getElementById('account').value = accountNumber;
            document.getElementById('paymentForm').submit();
        });
        
        // Timer functionality (if needed)
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                display.textContent = minutes + ":" + seconds;
                
                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }
        
        window.onload = function () {
            const fiveMinutes = 60 * 5,
                display = document.querySelector('#timer');
            startTimer(fiveMinutes, display);
        };
    </script>
</body>
</html>