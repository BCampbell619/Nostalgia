<?php

    define("TITLE", "User Name | The Nostalgia Vault");
    include('includes/connection.php');
    include('includes/header.php');
    include('includes/functions.php');

    $email = "";                    //variable to hold the user input email
    $error = "";                    //variable to hold the error output line
    $success = "";                  //variable to hold the success output line

    if (isset($_POST['user_get'])){
        
        $email = clean_input($_POST['email']);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){      //If email address is not a valid email address an error is thrown
				
				$error = "Please provide a valid email address.";
				
			}
        
        if (!getUserName($email, $Connect)){
            
            $error = "Email not on file";
            
        } else {
            
            $userName = getUserName($email, $Connect);
            $success = "Your User Name has been sent to your email.";
            
            $to = "$email";
                       
            $subject = "User Name request - The Nostalgia Vault";
            
            $message = "Your user name is: $userName[subUserName]\r\n\r\n";
            $message .= "Thanks for being a member of the Vault!\r\n";
            $message .= "Your contributions are much appreciated by all!";
    
            
            $message = wordwrap($message, 70);
            
            $headers = "MIME-Version 1.0\r\n";
            $headers .="Content-type: text/plain; charset=iso-8859-1\r\n";
            $headers .="From: " . " The Vault Team " . "<" . "thecampbellscorner.com" . ">\r\n";
            $headers .="X-Priority: 1\r\n";
            $headers .="X-MSMail-Priority: High\r\n\r\n";
            
            mail($to, $subject, $message, $headers);
            
        }
        
    }
?>

<div class="row" id="userGet">
    
    <div class="col-sm-offset-4 col-sm-4">
        
        <form action="user_get.php" method="post">

            <?php if ($error == "" && $success == ""){ ?>
            
                <div class="form-group">
            
            <?php } else if ($error != "" && $success == ""){ ?>
            
                <div class="form-group has-error">
            
            <?php } else if ($error == "" && $success != ""){ ?>
            
                <div class="form-group has-success">
            
            <?php } ?>
               
                <label class="control-label" for="email">
                
                <?php if ($error == "" && $success == ""){ ?>
                
                    Please enter your email address:
                    
                    <?php } else if ($error != ""){ 
    
                                    echo $error; 
        
                                } else if ($success != ""){ 
    
                                            echo $success; 

                                        } ?>
                                                
                </label>
                    
                <input type="text" id="email" name="email" class="form-control"><br>
                <button class="myfrmbtn" type="submit" id="user_get" name="user_get">Submit</button>
                <a href="login.php" id="userCancel">Cancel</a>
                <?php if ($success != ""){
    
                    echo "<a href=\"login.php\">back</a>";
    
                } ?>
                
            </div>
            
        </form>
        
    </div>
    
</div>


<?php

    include('includes/footer.php');

?>