<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escritor</title>
    <style>
        body{
            text-align: center;
        }
        textarea{
            resize: none;
        }
    </style>
</head>
<body>
    
<form method="post" enctype="multipart/form-data">
    <h4>Título:</h4>
    <input type="text" name="titulo" required>
    <h4>Matéria:</h4>
    <textarea name="corpo" required rows="6" cols="50"></textarea> 
    <h4>Escolha uma imagem de capa</h4>
    <input type="file" accept=".png, .jpg" id="file" name="img" required>
    <h4>Escolha uma imagem de corpo</h4>
    <input type="file" accept=".png, .jpg" id="file" name="imgC" required>
    <h4>Categoria da notícia</h4>
    <select name="categoria" required>
        <option value="1">Esporte</option>
        <option value="2">Entretenimento</option>
        <option value="3">Política</option>
    </select>
    <br><br>
    <input type="submit" value="upload" name="submit">
</form>

</body>
</html>

<?php 

    $con = mysqli_connect("localhost:3306", "root", "", "beluganews");

    session_start();
    $autorID = $_SESSION['id'];

    //corpo e titulo
    @$titulo = $_POST['titulo'];
    @$corpo = $_POST['corpo'];

    //categoria
    @$categoria = $_POST['categoria'];

    //infos da imagem
    @$fileName = $_FILES['img']['name'];
    @$fileType = $_FILES['img']['type'];
    @$fileSize = $_FILES['img']['size'];
    @$fileNameC = $_FILES['imgC']['name'];
    @$fileTypeC = $_FILES['imgC']['type'];
    @$fileSizeC = $_FILES['imgC']['size'];

    $target = "../uploads/";
    $uploadOk = 1;

    @$targetFile = $target.basename($_FILES['img']['name']);
    @$imageType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    @$targetFileC = $target.basename($_FILES['imgC']['name']);
    @$imageTypeC = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    @$nomeFoto = mysqli_query($con, "SELECT nome FROM imagem WHERE nome = '$fileName' or nome = '$fileNameC'");
    @$nomeFoto = mysqli_fetch_array($nomeFoto);

    if(isset($_POST['submit']))
    {
        $check = getimagesize($_FILES['img']['tmp_name']);
        $checkC = getimagesize($_FILES['imgC']['tmp_name']);

        if($check !== false && $checkC !== false)
        {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    @$tituloCheck = mysqli_query($con, "SELECT titulo FROM noticia WHERE titulo = '$titulo'");
    @$tituloCheck = mysqli_fetch_array($tituloCheck);

    //envia a noticia
    if(isset($nomeFoto))
    {
        echo"<h4>Erro</h4><p>Nome de arquivo já utilizado</p>";
    }
    else{
        if(move_uploaded_file($_FILES['img']['tmp_name'], $targetFile) && move_uploaded_file($_FILES['imgC']['tmp_name'], $targetFile) && isset($tituloCheck) == false)
        {   
            echo"<h4>Notícia enviada com sucesso</h4>";
            $res = mysqli_query($con, "INSERT INTO noticia (titulo, corpo, categoria, id_autor) VALUES ('$titulo', '$corpo', $categoria, $autorID)");
            $res = mysqli_query($con, "INSERT INTO imagem (nome, tipo, tamanho) VALUES ('$fileName', '$fileType', $fileSize)");
            $res = mysqli_query($con, "INSERT INTO imagem (nome, tipo, tamanho) VALUES ('$fileNameC', '$fileTypeC', $fileSizeC)");

            $capaID = mysqli_query($con, "SELECT id FROM imagem WHERE nome = '$fileName'");
            $capaID = mysqli_fetch_array($capaID);

            $capaCID = mysqli_query($con, "SELECT id FROM imagem WHERE nome = '$fileNameC'");
            $capaCID = mysqli_fetch_array($capaCID);

            $query2 = mysqli_query($con, "UPDATE noticia SET imagem_capa = $capaID[0] WHERE titulo = '$titulo' ");
            $query3 = mysqli_query($con, "UPDATE noticia SET imagem_corpo = $capaCID[0] WHERE titulo = '$titulo' ");
        } else if(isset($tituloCheck)){
            echo "<h4>Título já existe<h4>";
        }
        else{
            echo "<h4>Erro</h4>";
        }
    }

?> 