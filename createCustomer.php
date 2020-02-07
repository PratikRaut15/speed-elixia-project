<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excellibrary\excel_reader2.php';
$data = new Spreadsheet_Excel_Reader("example.xls");
    function p($p){
        echo "<pre>";
        print_r($p);
        echo "<pre>";
        die;
    }
    if(isset($_POST['submit']) && isset($_FILES)){
        
        
        $filePath = $_FILES['file']['name'];
        p(fopen($_FILES["file"]["tmp_name"], 'r'));
        
        // opening the file
        $fileOpen = fopen($_FILES["file"]["tmp_name"], 'r');
        if($fileOpen){

        }

    }
?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Title Page</title>

        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        
        <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 well">
            <h1 class="h3">Create User</h1>
                <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" name="file" id="exampleInputFile">
                    <p class="help-block">Please upload the Excel File</p>
                </div>
               
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
            <div class="col-md-4"></div>
        </div>
        </div>



        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>
