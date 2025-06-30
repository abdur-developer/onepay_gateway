<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Payment Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/checkoutdata/onepay/checkoutdata/onepay/assets/select2.min.css">
    <link rel="stylesheet" href="/checkoutdata/onepay/checkoutdata/onepay/assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="complete.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body cz-shortcut-listen="true">
    <header>
        <img src="https://onepays.news/checkoutdata/onepay/img/topbg.6b8f4100.png" style="height: 172px; width: 100%">
    </header>

    <div style="margin-top: -125px; background: rgb(49, 51, 129); height: 100vh;" class="px-4">
        <div class="mb-4">
            <div class="d-flex align-items-center" style="gap: 5px;">
                <img src="https://onepays.news/checkoutdata/onepay/img/pixlogo.2432dd36.png" style="height: 28px; weight: 28px;">
                <p class="mb-0 text-white" style="font-size: 21px;">ONEPAY</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <p class="mb-0" style="text-align:center;font-size: 30px; color: #fff;font-family: 'Gilroy-Heavy',sans-serif;font-weight: 700;">
                <?php
                    require_once('dbcon.php');
                    $sql = "SELECT * FROM payment WHERE trx = '{$_POST['trx']}'";
                    $result = mysqli_query($conn, $sql);

                    $row = mysqli_fetch_assoc($result);
                    if ($row) echo htmlspecialchars($row['amount']);
                    else echo "0.00";                    
                ?> TK
            </p>
        </div>
        <div class="card mt-3" style="border-top-left-radius: 25px; border-top-right-radius: 25px;">
            <div class="" style="border-bottom: 2px solid rgb(240, 240, 242);">
                &nbsp;
            </div>
            <div class="p-3">
                <div class="text-center py-3">
                    <img src="https://onepays.news/checkoutdata/onepay/img/ing.4a4c95c1.gif" style="height: 94px;">
                </div>

                <div style="font-size: 13px; color: #b8b8b8;" class="mb-4 text-center">
                    অনুগ্রহপূর্বক অপেক্ষা করুন…
                </div>

                <div style="font-size: 13px; color: #b8b8b8;" class="mb-1"> <span class="text-danger">*</span>
                    OnePay শুধুমাত্র পেমেন্ট সংগ্রহ পরিষেবা প্রদান করে
                </div>

                <div style="font-size: 17px; font-weight: 700; color: #313381;">
                    পেমেন্ট তথ্য
                </div>

                <div class="mb-3 py-2 d-flex align-items-center justify-content-between" style="border-bottom: 2px solid rgb(240, 240, 242);">
                    <div style="font-size: 13px;color: #b8b8b8;">
                        পেমেন্ট আইডি
                    </div>
                    <div style="font-size: 15px; color: #000; font-weight: 500;">
                        <?= $_POST['trx'] ?>
                    </div>
                </div>

                <div style="font-size: 17px; font-weight: 700; color: #313381;">
                    পরিশোধ রশীদ
                </div>

                <div class="mb-3 py-2 d-flex align-items-center justify-content-between"
                    style="border-bottom: 2px solid rgb(240, 240, 242);">
                    <div style="font-size: 13px;color: #b8b8b8;">
                        লেনদেন নাম্বার
                    </div>
                    <div style="font-size: 15px; color: #000; font-weight: 500;">
                        7436PEVE
                    </div>
                </div>
            </div>

            <div style="width: 313px; height: 36px; margin-bottom: -36px; margin-left: -14px;">
                <div style="background-image: url(img/curve.png); background-position: 0% 0%; background-size: 100% 100%; background-repeat: no-repeat;">
                    <img style="display: block; position: absolute; left: 0; width: 100%;" src="img/curve.png" draggable="false">
                </div>
            </div>
        </div>
    </div>
    <section>
        <div class="container d-none">
            <div class="row">
                <div class="col-12 text-center">
                    <h4 style="font-size: 20px;line-height: 36px;font-weight: bold;padding-top: 50px;">
                        অর্ডার পর্যালোচনা করা হয়েছে এবং আপনার পেমেন্ট সম্পূর্ণ হয়েছে
                    </h4>
                </div>
                <div class="col-12 mt-4">
                    <ul style="list-style: none;margin: 0;padding: 0">
                        <li class="single-details ">অর্ডার নম্বর: <span> 7436PEVE </span></li>
                        <li class="single-details mt-4">অর্ডারের পরিমাণ: <span> 10</span></li>
                        <li class="single-details mt-4">প্রকৃত অর্থপ্রদানের পরিমাণ : <span> 10</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        //============================================
        const styles = document.createElement('style');
        styles.innerHTML = `
            @keyframes  shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                50% { transform: translateX(10px); }
                75% { transform: translateX(-10px); }
            }
            .shake-animation {
                animation: shake 0.5s;
            }
        `;
        document.head.appendChild(styles);
        
        setTimeout(() => {
            <?php
                if(isset($_POST['trx'])) {
                    $trx = $_POST['trx'];
                    echo "window.location.href = 'success.php?trx=" . urlencode($trx) . "';";
                }
            ?>
        }, 5000);
    </script>
</body>
</html>