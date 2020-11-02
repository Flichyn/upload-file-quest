<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/';
    $maxSize = 1000000;
    $extensions = ['.jpg', '.png', '.gif'];
    $errors = [];

    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
        $extension = strrchr($_FILES['images']['name'][$i], '.');
        $size = filesize($_FILES['images']['tmp_name'][$i]);

        if (!in_array($extension, $extensions)) {
            $errors[] = "File number " . $i . ". The type of your file must be 'jpg', 'png' or 'gif'.";
        }

        if ($size > $maxSize) {
            $errors[] = 'File number ' . $i . '. The size of your file musn\'t go beyond 1Mo.';
        }

        if (empty($errors)) {
            $tmpFilePath = $_FILES['images']['tmp_name'][$i];
            $newFileName = uniqid() . $extension;
            move_uploaded_file($tmpFilePath , $uploadDir . $newFileName);
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Upload File Quest</title>
</head>
<body>

<h1>Upload A File</h1>

<form action="" method="post" enctype="multipart/form-data">
    <div>
        <label for="upload">Upload your file !</label>
        <input type="file" id="upload" name="images[]" multiple="multiple"/><br/>
        <input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
        <button>Submit</button>
    </div>

    <?php if (!empty($errors)) {
        foreach ($errors as $error) { ?>
            <p><?= $error; ?></p>
        <?php }
    }

    $files = new FilesystemIterator(__DIR__ . '/uploads', FilesystemIterator::SKIP_DOTS);

    foreach ($files as $file) { ?>
        <figure>
            <img src="uploads/<?= $file->getFilename(); ?>" alt="<?= $file->getFilename(); ?>"/>
            <figcaption><?= $file->getFilename(); ?></figcaption>
        </figure>
    <?php }

    ?>

</form>
</body>
</html>