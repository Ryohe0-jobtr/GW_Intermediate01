<?php

$id = $_GET["id"];

include "funcs.php";
$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM cardnest_person WHERE id =:id");
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
    <link rel="stylesheet" href="CSS/style06.css">
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
                <p>社員データ修正</p>
            </div>

            <form class="person-edit" action="update-person.php" method="post" autocomplete="off">
                <div class="input-container">
                    <label for="name">氏名</label><br>
                    <input type="text" name="name" class="input" id="name" value="<?= $result["name"] ?>">
                    <span class="error-message">氏名を入力してください</span>

                </div>
                <div class="input-container">
                    <label for="affiliation">所属</label><br>
                    <input type="text" name="affiliation" class="input" id="affiliation" value="<?= $result["affiliation"] ?>">
                    <span class="error-message">所属を入力してください</span>
                </div>

                <div class="input-container">
                    <label for="tel">電話番号(任意、ハイフンは除いて入力してください)</label><br>
                    <input type="tel" name="tel" class="input2" id="tel" value="<?= $result["tel"] ?>">
                    <span class="error-message2">ハイフンは除いて入力してください</span>
                    <span class="error-message3">半角数字のみを入力してください</span>
                    <span class="error-message5">15桁以内で入力してください</span>
                </div>
                <div class="input-container">
                    <label for="mail">メールアドレス(任意)</label><br>
                    <input type="text" name="mail" class="input2" id="mail" value="<?= $result["mail"] ?>">
                    <span class="error-message4">@を入力してください</span>
                </div>
                <div class="input-container">
                    <label for="update-by">登録者氏名</label><br>
                    <input type="text" name="updateBy" class="input" id="update-by">
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
            $('.person-edit').on('submit', function(event) {
                let formValid = true;

                // 全ての入力フィールドをループしてバリデーションを行う
                $('.input').each(function() {
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

                // 電話番号のバリデーション
                const telNumber = $('#tel').val();
                const errorMessage2 = $('.error-message2');
                const errorMessage3 = $('.error-message3');
                const errorMessage5 = $('.error-message5');

                if (telNumber !== '') {
                    if (telNumber.includes('-') && /[^0-9-]/.test(telNumber)) {
                        formValid = false;
                        $('#tel').addClass('invalid');
                        $('#tel').parent('.input-container').addClass('error');
                        errorMessage2.show().addClass('active');
                        errorMessage3.show().addClass('active');
                        errorMessage5.hide();
                    } else if (telNumber.includes('-')) {
                        formValid = false;
                        $('#tel').addClass('invalid');
                        $('#tel').parent('.input-container').addClass('error');
                        errorMessage2.show().removeClass('active');
                        errorMessage3.hide();
                        errorMessage5.hide();
                    } else if (/[^0-9-]/.test(telNumber)) {
                        formValid = false;
                        $('#tel').addClass('invalid');
                        $('#tel').parent('.input-container').addClass('error');
                        errorMessage3.show().removeClass('active');
                        errorMessage2.hide();
                        errorMessage5.hide();
                    } else if (telNumber.length > 15) {
                        $('#tel').addClass('invalid');
                        $('#tel').parent('.input-container').addClass('error');
                        errorMessage2.hide();
                        errorMessage3.hide();
                        errorMessage5.show();
                    } else {
                        $('#tel').removeClass('invalid');
                        $('#tel').parent('.input-container').removeClass('error');
                        errorMessage2.hide();
                        errorMessage3.hide();
                        errorMessage5.hide();
                    }
                } else {
                    $('#tel').removeClass('invalid');
                    $('#tel').parent('.input-container').removeClass('error');
                    errorMessage2.hide();
                    errorMessage3.hide();
                    errorMessage5.hide();
                }

                // メールアドレスのバリデーション
                const email = $('#mail').val();
                const errorMessage4 = $('.error-message4');

                if (email !== '') {
                    if (!email.includes('@')) {
                        formValid = false;
                        $('#mail').addClass('invalid');
                        $('#mail').parent('.input-container').addClass('error');
                        errorMessage4.show();
                    } else {
                        $('#mail').removeClass('invalid');
                        $('#mail').parent('.input-container').removeClass('error');
                        errorMessage4.hide();
                    }
                } else {
                    $('#mail').removeClass('invalid');
                    $('#mail').parent('.input-container').removeClass('error');
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