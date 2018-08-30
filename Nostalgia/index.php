<?php

    $codeOne    =   '122087';
    $codeTwo    =   '021589';
    $codeThree  =   '040790';
    $codeFour   =   '082491';
    $codeFive   =   '090298';

    $error = "";

    function no_header_inject($str){
               return preg_match( "/[\r\n]/", $str );
           }

    if (isset($_POST['codesubmit'])){
        
        $codeGiven = trim($_POST['code']);
        $codeGiven = htmlspecialchars($_POST['code']);
    
        if (no_header_inject($codeGiven)){
            die();
        }
            
        
        if ($codeGiven == $codeOne){
           $error = "Your code matches";

        } else if ($codeGiven == $codeTwo){
            $error = "Your code matches";

        } else if ($codeGiven == $codeThree){
            $error = "Your code matches";

        } else if ($codeGiven == $codeFour){
            $error = "Your code matches";

        } else if ($codeGiven == $codeFive){
            $error = "Your code matches"; 
        
        } else if ($codeGiven == ""){
            $error = "Please enter a code.";
            
        } else {
            $error = "Please enter a valid code.";
        }
        
        if ($error == "Your code matches"){
            header('Location: main.php');
            exit();
        }
        
    }

    define("TITLE", "The Nostalgic Vault | Home");
    include('includes/header.php');

    
    
?>

<div class="row">
    <div class="hero col-xs-12 col-sm-12 col-md-12 col-lg-12">
    
           <h1>Nostalgic Vault</h1>
           <p><span id="subheading">Video Game Music of the Ages</span></p>
           
    </div><!-- end of content -->
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="img-scroll">
            <img src="images/footer_img.png" alt="System Images">
        </div>
    </div>
</div>

<div class="row" id="enter">
       <form method="post" action="">
           <div class="form-group col-xs-offset-4 col-xs-4">
               <label for="passcode">Enter Code&#58;</label>
               <input type="text" id="passcode" name="code" class="form-control"><br>
               <button type="submit" class="btn btn-success" name="codesubmit">Submit</button>
               <span class="text-danger"><?php echo $error; ?></span>
           </div>
       </form>
</div>

<?php
    include('includes/footer.php');
?>