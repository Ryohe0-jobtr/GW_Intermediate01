<?php

$id = $_GET["id"];

include "funcs.php";
$pdo = db_con();

// ページング用のパラメータ
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // 現在のページ番号（デフォルトは1）
$perPage = 10; // 1ページあたりのリスト数
$numArrows = 2; // 前後に表示する矢印の数

$stmt = $pdo->prepare("SELECT * FROM cardnest_company WHERE id =:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    $resultCompany = $stmt->fetch();
}

$stmt = $pdo->prepare("SELECT * FROM cardnest_person WHERE company_id = :company_id LIMIT :offset, :perPage");
$stmt->bindValue(':company_id', $id, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$status = $stmt->execute();

$person = "";
if ($status == false) {
    sqlError($stmt);
} else {
    while ($resultPerson = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $person .= '<li>';
        $person .= '<a href="personal.php?id=' . $resultPerson["id"] . '">';
        $person .= $resultPerson["name"];
        $person .= '</a>';
        $person .= '</li>';
    }
}

$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM cardnest_person WHERE company_id = :company_id");
$stmt->bindValue(':company_id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
    sqlError($stmt);
} else {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRows = $row['total'];
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Nest</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="CSS/style01.css">
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

            <div class="company-name">
                <p>会社詳細</p>
            </div>

            <div class="company-data">
                <dl>
                    <dt>会社名：</dt>
                    <dd><?= $resultCompany["company"] ?></dd>
                    <br>
                    <dt>郵便番号：</dt>
                    <dd><?= '〒' . substr($resultCompany["postcode"], 0, 3) . "-" . substr($resultCompany["postcode"], 3, 4); ?></dd>
                    <br>
                    <dt>住所：</dt>
                    <dd><?= $resultCompany["address"] ?></dd>
                    <br>
                    <dt>登録者氏名：</dt>
                    <dd><?= $resultCompany["registered_by"] ?></dd>
                    <br>
                    <dt>登録日：</dt>
                    <dd><?= date('Y/n/d', strtotime($resultCompany["registered_date"])) ?></dd>
                    <br>
                    <dt>更新日：</dt>
                    <dd><?= date('Y/n/d', strtotime($resultCompany["update_date"])) ?></dd>

                </dl>
            </div>

            <div>
                <p class="personal-title" id="section1">社員一覧</p>
            </div>

            <div class="personal-list">
                <ul>
                    <?= $person ?>
                </ul>
            </div>

            <!-- ページング部分 -->
            <div class="pagination-wrapper">
                <div class="pagination">
                    <?php if ($totalRows > $perPage) : ?>
                        <?php if ($page > 1) : ?>
                            <a href="?id=<?= $id ?>&page=1">≪</a>
                            <a href="?id=<?= $id ?>&page=<?= $page - 1 ?>">＜</a>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - $numArrows); $i <= min(ceil($totalRows / $perPage), $page + $numArrows); $i++) : ?>
                            <a href="?id=<?= $id ?>&page=<?= $i ?>" <?= $i === $page ? 'class="current"' : '' ?>><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < ceil($totalRows / $perPage)) : ?>
                            <a href="?id=<?= $id ?>&page=<?= $page + 1 ?>">＞</a>
                            <a href="?id=<?= $id ?>&page=<?= ceil($totalRows / $perPage) ?>">≫</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <button type="button" class="top-btn">
                    <p>▲</p>
                </button>
            </div>

    </div>

    <div class="edit">
        <a class="company-dlt" href="#">
            <p>会社データ削除</p>
        </a>
        <a class="company-edit" href="detail-company.php?id=<?= $resultCompany["id"] ?>">
            <p>会社データ修正</p>
        </a>
        <a class="personal-regi" href="regist-person.php?id=<?= $resultCompany["id"] ?>">
            <p>社員データ登録</p>
        </a>
    </div>

    <!-- モーダルのHTML -->
    <div id="my-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <!-- モーダルの内容 -->
            <p>本当に会社データを削除しますか？</p>
            <div style="display: flex;">
                <a href="delete-company.php?id=<?= $resultCompany["id"]; ?>" id="confirm-delete" class="delete-btn">削除</a>
                <button type="button" id="cancel-delete" class="cancel-btn">キャンセル</button>
            </div>
        </div>
    </div>

    </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".top-btn").click(function() {
                $("html, body").animate({
                    scrollTop: 0
                }, "normal");
            });
        });

        //---モーダル---

        // 「会社データ削除」リンクがクリックされたときの処理
        $(".company-dlt").click(function(e) {
            e.preventDefault(); // リンクのデフォルト動作をキャンセル

            // モーダルを表示する
            $("#my-modal").show();
        });

        // 「キャンセル」ボタンがクリックされたときの処理
        $(".close-button, .cancel-btn").click(function() {
            // モーダルを閉じる
            $("#my-modal").hide();

        });
    </script>
</body>

</html>