<?php

$id = $_GET["id"];

include "funcs.php";
$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM cardnest_company WHERE id =:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    $result = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Nest</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="CSS/style04.css">
    <link rel="icon" href="img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Noto+Sans+JP:wght@100..900&family=Sawarabi+Mincho&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="top-link">
                <a href="index.php">
                    <p class="title">Card Nest</p>
                    <img src="img/icon.jpg" alt="Card Nest">
                </a>
            </div>
            <a class="top" href="index.php">
                <p>登録会社一覧</p>
            </a>
        </header>
        <hr>

        <main>
            <div class="edit-title">
                <p>会社データ修正</p>
            </div>

            <form class="company-edit" action="update-company.php" method="post" autocomplete="off" autocomplete="off">
                <div class="input-container">
                    <label for="company">会社名</label><br>
                    <input type="text" name="company" class="input" id="company" value="<?= $result["company"] ?>">
                    <span class="error-message">会社名を入力してください</span>
                </div>
                <div class="input-container">
                    <label for="postcode">郵便番号(ハイフンを除いて入力してください)</label><br>
                    <input type="text" name="postcode" class="input" id="postcode" value="<?= $result["postcode"] ?>">
                    <span class="error-message">郵便番号を入力してください</span>
                    <span class="error-message2">ハイフンを除いて入力してください</span>
                    <span class="error-message3">半角数字のみを入力してください</span>
                    <span class="error-message4">郵便番号を7桁で入力してください</span>

                </div>
                <div class="input-container">
                    <label for="address">住所</label><br>
                    <input type="text" name="address" class="input" id="address" value="<?= $result["address"] ?>">
                    <span class="error-message">住所を入力してください</span>
                </div>
                <div class="input-container">
                    <label for="registered-by">登録者氏名</label><br>
                    <input type="text" name="registeredBy" class="input" id="registered-name" value="<?= $result["registered_by"] ?>">
                    <span class="error-message">登録者氏名を入力してください</span>
                </div>
                <div class="edit-btn-wrap">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input class="edit-btn" type="submit" value="修正">
                </div>
            </form>

        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.company-edit').on('submit', function(event) {
                let formValid = true;

                // 全ての入力フィールドをループしてバリデーションを行う
                $('.company-edit input').each(function() {
                    const errorMessage = $(this).next('.error-message');
                    if ($(this).val() == '') {
                        formValid = false;
                        $(this).addClass('invalid');
                        $(this).parent('.input-container').addClass('error');
                        errorMessage.show();
                    } else {
                        $(this).removeClass('invalid');
                        $(this).parent('.input-container').removeClass('error');
                        errorMessage.hide();
                    }
                });

                // 郵便番号のバリデーション
                const postcode = $('#postcode').val();
                const errorMessage2 = $('.error-message2');
                const errorMessage3 = $('.error-message3');
                const errorMessage4 = $('.error-message4');

                if (postcode == '') {
                    formValid = false;
                    $('#postcode').addClass('invalid');
                    $('#postcode').parent('.input-container').addClass('error');
                    errorMessage2.hide();
                    errorMessage3.hide();
                    errorMessage4.hide();               
                } else if (postcode.includes('-') && /[^0-9-]/.test(postcode)) {
                    formValid = false;
                    $('#postcode').addClass('invalid');
                    $('#postcode').parent('.input-container').addClass('error');
                    errorMessage2.show().addClass('active');
                    errorMessage3.show().addClass('active');
                    errorMessage4.hide();
                } else if (/[^0-9-]/.test(postcode)) {
                    formValid = false;
                    $('#postcode').addClass('invalid');
                    $('#postcode').parent('.input-container').addClass('error');
                    errorMessage3.show().removeClass('active');
                    errorMessage2.hide();
                    errorMessage4.hide();
                } else if (postcode.includes('-')) {
                    formValid = false;
                    $('#postcode').addClass('invalid');
                    $('#postcode').parent('.input-container').addClass('error');
                    errorMessage2.show().removeClass('active');
                    errorMessage3.hide();
                    errorMessage4.hide();
                } else if (!/^\d{7}$/.test(postcode)){
                    formValid = false;
                    $('#postcode').addClass('invalid');
                    $('#postcode').parent('.input-container').addClass('error');
                    errorMessage2.hide();
                    errorMessage3.hide();
                    errorMessage4.show(); 
                } else {
                    $('#postcode').removeClass('invalid');
                    $('#postcode').parent('.input-container').removeClass('error');
                    errorMessage2.hide().removeClass('active');
                    errorMessage3.hide().removeClass('active');
                    errorMessage4.hide();
                    
                }

                // フォーム送信時にのみバリデーションを行う
                if (!formValid) {
                    event.preventDefault(); // フォーム送信をキャンセル
                }
            });
        });
    </script>

</body>

</html>