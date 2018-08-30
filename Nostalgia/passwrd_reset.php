<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define("TITLE", "Password Reset | The Nostalgia Vault");
    include('includes/connection.php');
    include('includes/header.php');
    include('includes/functions.php');

    $error          = "";
    $update_success = "";

    if (isset($_POST['pass_update'])) {
        
        $email      = clean_input($_POST['email']);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){      //If email address is not a valid email address an error is thrown
				
				$error = "Please provide a valid email address";
				
			} else if (!email_db_check($email, $Connect)) {
            
                $error = "Email not on file. Please provide the email associated with your account.";
            
            } else {
                $tmpPass            = replacePassword(8);
                $safePass           = password_hash("$tmpPass", PASSWORD_DEFAULT);               //Password is encrytped
                $passQuery          = "UPDATE subscribe SET subPassword = '$safePass' WHERE subEmail = '$email';";

                if (mysqli_query($Connect, $passQuery)) {                                        //Checks to see if the query runs successfully

                        $update_success = "Temporary Password has been sent to your email";      //If the query runs successfully then an email is sent with the updated tmp password
                                                                                                 //and a success message is displayed to the user
                        $to = "$email";

                        $subject = "Notalgic Vault Password Reset";

                        $message = "You have requested to update  your password for The Nostalgic Vault. Your temporary password is the following: \r\n\r\n";
                        $message .= "Temp Password: $tmpPass\r\n\r\n";
                        $message .= "Once you have logged back into your account, click on your user name to go to your profile page.\r\n";
                        $message .= "Once on your profile page, click on the \"Update Password\" button to reset your password.\r\n\r\n";
                        $message .= "Thanks,\r\n";
                        $message .= "The Vault";


                        $message = wordwrap($message, 70);

                        $headers = "MIME-Version 1.0\r\n";
                        $headers .="Content-type: text/plain; charset=iso-8859-1\r\n";
                        $headers .="From: TheVaultTeam <" . "thecampbellscorner.com" . ">\r\n";
                        $headers .="X-Priority: 1\r\n";
                        $headers .="X-MSMail-Priority: High\r\n\r\n";

                        mail($to, $subject, $message, $headers);

                    } else {

                        $error = "An error occured. Password was not reset. Click cancel and try again.";                                //If the query doesn't execute then this error will be passed
                        
                    }
                        
            
        }
        
    }

?>
<div class="container-fluid">
   
   <div class="row" id="passGet">
   
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
    
    <br><br>
    
    <form action="" method="post">
       
        <div class="form-group col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">
           <?php if ($error != "") {
    
                echo "$error<br>";
    
            } else if ($error == "" && $update_success != "") {
    
                echo "$update_success<br>";
    
            } ?>
            <label class="control-label" for="email">Email Address:</label>
            <input type="text" class="form-control" id="email" name="email"><br>
            
            <?php if ($error == "" && $update_success == "") { 
            
                echo "<button class=\"myfrmbtn\" type=\"submit\" id=\"pass_update\" name=\"pass_update\">Submit</button>
                <a href=\"login.php\" id=\"passwordCancel\">Cancel</a>";
                
            } else if ($error == "Please provide a valid email address" || $error == "Email not on file. Please provide the email associated with your account.") {
    
                echo "<button class=\"myfrmbtn\" type=\"submit\" id=\"pass_update\" name=\"pass_update\">Submit</button>
                <a href=\"login.php\" id=\"passwordCancel\">Cancel</a>";
    
            } else if ($error == "An error occured. Password was not reset. Click cancel and try again.") {
    
                echo "<a href=\"login.php\" id=\"passwordCancel\">Cancel</a>";
    
            } else if ($update_success == "Temporary Password has been sent to your email") {
    
                echo "<a href=\"login.php\" id=\"passwordCancel\">Back</a>";
    
            } ?>
        </div>
    </form>
    
    </div>
    
  </div>
    
</div>

<?php

    include('includes/footer.php');

?>