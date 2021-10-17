<?php
require_once '_connect.php';

$pdo = new \PDO(DSN, USER, PASS);

$connection = new PDO(DSN, USER, PASS);

$friendList = $connection->query('SELECT firstname, lastname FROM friend ORDER BY firstname');
$data = $friendList->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD']=='POST') {
    $newFriend = array_map('trim', $_POST);
    if(empty($newFriend['firstname'])) {
        $firstnameErr="firstame is required";
    }
    elseif (!preg_match("/^[a-zA-Z-' ]*$/",$newFriend['firstname'])) {
        $firstnameErr="Only letters are allowed";
    }
    if(empty($newFriend['lastname'])) {
        $lastnameErr="lastname is required";
    }
    elseif (!preg_match("/^[a-zA-Z-' ]*$/",$newFriend['lastname'])) {
        $lastnameErr="Only letters are allowed";
    }

    if (empty($lastnameErr) && empty($firstnameErr)) {
        $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
        $friendList = $connection->prepare($query);
        $friendList->bindValue(':firstname',$newFriend["firstname"],PDO::PARAM_STR);
        $friendList->bindValue(':lastname',$newFriend["lastname"],PDO::PARAM_STR);
        $friendList->execute();

        header('Location :index.php');
    }
}

$friendList = $connection->query('SELECT firstname, lastname FROM friend ORDER BY firstname');
$data = $friendList->fetchAll(PDO::FETCH_ASSOC);



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/book.css">
    <title>Checkpoint PHP 1</title>
</head>
<body>

    <main>
        <form method="post" class="field">
            <div name="name">
                <label for="firstname">firstname</label>
                <input type="text" name="firstname">
                <span class="error"> <?php if (isset($firstnameErr))  echo $firstnameErr;?></span>
            </div>
            <div name="name">
                <label for="lastname">lastname</label>
                <input type="text" name="lastname">
                <span class="error"> <?php if (isset($lastnameErr))  echo $lastnameErr;?></span>
            </div>
            <div class="envoie">
                <input type="submit" value="Se faire un nouvel ami">
            </div>
            <ul>
                <?php
                foreach($data as $value) : ?>
                <li><?= $value['firstname'] ." ". $value['lastname']?></li>
                <?php  endforeach; ?>
            </ul>
        </form>
    </main>
</body>
</html>