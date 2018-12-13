<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>My Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900|Cormorant+Garamond:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
          echo '<div class="gallery-upload">
            <form action="includes/gallery-upload.inc.php" method="post" enctype="multipart/form-data">
              <input type="text" name="filename" placeholder="File name...">
              <input type="text" name="filetitle" placeholder="Image title...">
              <input type="text" name="filedesc" placeholder="Image description...">
              <input type="file" name="file" multiple>
              <button type="submit" name="submit">UPLOAD</button>
            </form>
            </div>';
        }
        ?>
  </body>
</html>
