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
    <link rel="stylesheet" href="CSS/style05.css">
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
            <div class="person-title">
                <p>社員データ</p>
            </div>

            <div class="personal-data">
                <dl>
                    <dt>氏名：</dt>
                    <dd><?= $result["name"] ?></dd>
                    <br>
                    <dt>所属：</dt>
                    <dd><?= $result["affiliation"] ?></dd>
                    <br>
                    <dt>電話番号：</dt>
                    <dd><span id="formattedTel"><?= $result["tel"] ?></span></dd>
                    <br>
                    <dt>メールアドレス：</dt>
                    <dd><?= $result["mail"] ?></dd>
                    <br>
                    <dt>登録者氏名：</dt>
                    <dd><?= $result["registered_by"] ?></dd>
                    <br>
                    <dt>登録日：</dt>
                    <dd><?= date('Y/n/d', strtotime($result["registered_date"])) ?></dd>
                    <br>
                    <dt>最終更新者氏名：</dt>
                    <dd><?= $result["update_by"] ?></dd>
                    <br>
                    <dt>更新日：</dt>
                    <dd><?= date('Y/n/d', strtotime($result["update_date"])) ?></dd>
                </dl>
            </div>

            <div class="edit">
                <a class="person-dlt" href="">
                    <p>社員データ削除</p>
                </a>
                <a class="person-edit" href="detail-person.php?id=<?= $id ?>">
                    <p>社員データ編集</p>
                </a>
                <a class="ptop" href="company.php?id=<?= $result["company_id"] ?>#section1">
                    <p>社員一覧</p>
                </a>
            </div>

            <div id="my-modal" class="modal">
                <div class="modal-content">
                    <!-- 閉じるボタン -->
                    <span class="close-button">&times;</span>
                    <!-- モーダルの内容 -->
                    <p>本当に社員データ削除しますか？</p>
                    <form action="" method="post" style="display: flex;">
                        <a href="delete-person.php?id=<?= $id ?>" id="confirm-delete" class="delete-btn">削除</a>
                        <button type="button" id="cancelDelete" class="cancel-btn">キャンセル</button>
                    </form>
                </div>
            </div>

        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        //---モーダル---
        $(document).ready(function() {
            // 「会社データ削除」リンクがクリックされたときの処理
            $(".person-dlt").click(function(e) {
                e.preventDefault(); // リンクのデフォルト動作をキャンセル

                // モーダルを表示する
                $("#my-modal").show();
            });

            // 「キャンセル」ボタンがクリックされたときの処理
            $(".close-button, .cancel-btn").click(function() {
                // モーダルを閉じる
                $("#my-modal").hide();
            });
        });

        formatPhoneNumber();
    </script>
</body>

</html>