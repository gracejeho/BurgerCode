<?php
require 'database.php';
$nameError = $descriptionError=$categoryError = $imageError=$priceError = $name = $description = $price =$category = $image ="";
if(!empty($_POST))
{
   $name =            checkInput( $_POST['name']);
   $description =     checkInput($_POST['description']);
   $price =           checkInput($_POST['price']);
   $category =      checkInput($_POST['category']);
   $imagepath =          '../images/'.basename($image);
   $imageExtension  = pathinfo($imagepath, PATHINFO_EXTENSION);
   $isSucess=            true;
   $isUploadSucess=        false;
   if(empty($name))
   {
    $nameError='ce champ ne peut être vide';
    $isSucess=false;
   }

   if(empty($description))
   {
    $descriptionError='ce champ ne peut être vide';
    $isSucess=false;
   }

   if(empty($price))
   {
    $priceError='ce champ ne peut être vide';
    $isSucess=false;
   }

   if(empty($category))
   {
    $categoryError='ce champ ne peut être vide';
    $isSucess=false;
   }

   if(empty($image))
   {
    $imageError='ce champ ne peut être vide';
    $isSucess=false;
   }else
   {
    $isUploadSucess = true;
    if($imageExtension !="jpg"&& imageExtension != "png"&& imageExtension !="jpg" && imageExtension !="gif")
    {
        $imageError="Les fichiers autorisees sont .jpg, .jpeg, .png, .gif";
        $isUploadSuccess = false;
    }
    if(file_exists(imagePath))
    {
        $imageError= "le fichier existe deja";
        $isUploadSucces = false;
    }

    if($_FILES["image"]["size"] > 500000)
    {
        $imageError="Le fichier ne doit pas depasser les 500KB";
        $isUploadSucccess = false;
    }
    if($isUploadSucccess)
    {
        if(!move_uploaded_file($_FILES["image"]["tmp_name"],$imagepath))
        {
            $imageError = "il y a eu une erreur lors de l upload";
            $isUploadSucccess = false;
        }
    }
     
   }
   if($isSucess && $isUploadSucccess)
   {
    $db = Database::connect();
    $statement = $db->prepare("INSERT INTO items(name,description,price,category,image) values(?,?,?,?,?)");
    $statement ->execute(array($name, $description,$price,$category,$image));
    Database:disconnect();
    header("Location: index.php");

   }


}

function checkInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!doctype html>

  <head>
    <title>Burger code</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <link href='http://fonts.googleapis.com/css?family=Holtwood+one+SC' rel='stylesheet' type='text/css'>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">

  </head>


  <body>
    <h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger code <span class="glyphicon glyphicon-cutlery"></span> </h1>
    <div class="container admin ">

      <div class="row">

      <h1>ajouter un item</h1>
        <br>
        <form class="form" role="form" action="insert.php" method="post" enctype="mutipart/form-data">
            <div class="form-group">
                <label for="name">Nom:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="nom" value="<?php echo $name; ?>">
                <span class="help-inline"><?php echo $nameError; ?></span>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">
                <span class="help-inline"><?php echo $descriptionError; ?></span>
            </div>

           
            <div class="form-group">
            <label for="price">prix: (en $)</label>
                <input type="number"step="0.01" class="form-control" id="description" name="price" placeholder="prix" value="<?php echo $price; ?>">
                <span class="help-inline"><?php echo $priceError; ?></span>
            </div>

            <div class="form-group">
                <label for="category">Categorie:</label>
                <select class="form-control" id="category" name="category">
                    <?php
                    $db = Database::connect();
                    foreach($db->query('SELECT * FROM categories') as $row)
                    {
                         echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                    }
                    Database::disconnect();
                    ?>  
                    </select> 
                    <span class="help-inline"><?php echo $categoryError; ?></span>
            </div>

            <div class="form-group">
                <label for="image">Selectionner une image:</label>
                <input type="file" id="image" name="image">
                <span class="help-inline"><?php echo $imageError;?></span>
            </div>
        
   <div class="form-actions">
    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> A jouter </button>
    <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour </a>
   </div>
                </form>


      </div> 

                </div>
                </div>
     
    


        
</body>
</html>