<?php
/*
Section 1.0 - Data Cleansing Functions
Section 2.0 - Database Query Functions
Section 3.0 - Login Functions
Section 4.0 - File Functions
Section 5.0 - String Functions
*/


/*====================================================================================================================================================
                                                                        Section 1.0                                                                         
====================================================================================================================================================*/

    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function strip_bad_chars( $input ) {

		$output = preg_replace( "/[^a-zA-Z0-9_-]/", "", $input );
		return $output;

    }

/*====================================================================================================================================================
                                                                        Section 2.0                                                                         
====================================================================================================================================================*/

    function user_db_check($Login_UserName, $Connect){
        $result = mysqli_query($Connect, "SELECT subID FROM subscribe WHERE subUserName = '$Login_UserName';");
        
        if (mysqli_num_rows($result) == 1){
            
            return TRUE;
            
        }   else    {
            
            return FALSE;
            
        }
    }

    function email_db_check($email, $Connect){
        $result = mysqli_query($Connect, "SELECT subID FROM subscribe WHERE subEmail = '$email';");
        
        if (mysqli_num_rows($result) == 1){
            
            return TRUE;
            
        }   else    {
            
            return FALSE;
            
        }
    }

    function file_check($query, $Connect) {
        
        $result = mysqli_query($Connect, $query);
        
        if (mysqli_num_rows($result) == 1) {
            
            return TRUE;
        } else {
            
            return FALSE;
            
        }
        
    }

    function get_user_id($Login_UserName, $Connect){
        
        $query          = mysqli_query($Connect, "SELECT subID FROM subscribe WHERE subUserName = '$Login_UserName';");
        $queryResult    = mysqli_fetch_assoc($query);
        return  $queryResult;
        
    }

    function getUserInfo($UserID, $Connection){
        
        $query          = mysqli_query($Connection, "SELECT subFirstName, subLastName, subEmail, subUserName, subJoinDate FROM subscribe WHERE subID = $UserID;");
        $queryResult    = mysqli_fetch_assoc($query);
        return $queryResult;
        
    }

    function getContribution($UserID, $Connection){
        
        $query          = mysqli_query($Connection, "SELECT C_File_Name, C_Album_Name, C_Composer, C_Date FROM contribute WHERE subID = $UserID;");
        return $query;
        
    }

    function getUserName($email, $Connection){
        
        $query  = mysqli_query($Connection, "SELECT subUserName FROM subscribe WHERE subEmail = '$email';");
        $result = mysqli_fetch_assoc($query);
        return $result;
        
    }

/*====================================================================================================================================================
                                                                        Section 3.0                                                                         
====================================================================================================================================================*/

    function logged_in(){
        
        if (isset($_SESSION['subUserName'])){
            
            return true;
            
        }   else    {
            
            return false;
            
        }
    }

/*====================================================================================================================================================
                                                                        Section 4.0                                                                         
====================================================================================================================================================*/

    function replacePassword($qty){ 
        //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code. 
        $Characters = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
        $QuantifyCharacters = strlen($Characters); 
        $QuantifyCharacters--; 
        
        $Hash=NULL; 
        for($x=1; $x<=$qty; $x++){ 
            $Posicao = rand(0,$QuantifyCharacters); 
            $Hash .= substr($Characters,$Posicao,1); 
        } 
        
        return $Hash; 
    }

    function create_zip($file, $destination, $overwrite = false) {
	   //if the zip file already exists and overwrite is false, return false
	   if(file_exists($destination) && !$overwrite) { return false; }
	   //vars
	   $valid_file;
	   //if files were passed in...
	   if(is_file($file)) {
	   	//cycle through each file
	   	//foreach($files as $file) {
	   		//make sure the file exists
	   		if(file_exists($file)) {
	   			$valid_file = $file;
	   		}
	   	//}
	   }
	   //if we have good files...
	   if(count($valid_file)) {
	   	   //create the archive
	   	   $zip = new ZipArchive();
	   	   if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
	   	   	return false;
	   	   }
	   	   //add the files
	   	   //foreach($valid_files as $file) {
	   	   	$zip->addFile($file,$file);
	   	   //}
	   	   //debug
	   	   //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
	   	   
	   	   //close the zip -- done!
	   	   $zip->close();
	   	   
	   	   //check to make sure the file exists
	   	   return file_exists($destination);
	   }
	   else
	   {
	   	return false;
	   }
    }

/*====================================================================================================================================================
                                                                        Section 5.0                                                                         
====================================================================================================================================================*/

/*    after ('@', 'biohazard@online.ge');
    //returns 'online.ge'
    //from the first occurrence of '@'
    
    before ('@', 'biohazard@online.ge');
    //returns 'biohazard'
    //from the first occurrence of '@'
    
    between ('@', '.', 'biohazard@online.ge');
    //returns 'online'
    //from the first occurrence of '@'
    
    after_last ('[', 'sin[90]*cos[180]');
    //returns '180]'
    //from the last occurrence of '['
    
    before_last ('[', 'sin[90]*cos[180]');
    //returns 'sin[90]*cos['
    //from the last occurrence of '['
    
    between_last ('[', ']', 'sin[90]*cos[180]');
    //returns '180'
    //from the last occurrence of '['
*/

    function after ($this, $inthat)
    {
        if (!is_bool(strpos($inthat, $this)))
        return substr($inthat, strpos($inthat,$this)+strlen($this));
    };

    function after_last ($this, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $this)))
        return substr($inthat, strrevpos($inthat, $this)+strlen($this));
    };

    function before ($this, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $this));
    };

    function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strripos($inthat, $this));
    };

    function between ($this, $that, $inthat)
    {
        return before ($that, after($this, $inthat));
    };

    function between_last ($this, $that, $inthat)
    {
     return after_last($this, before_last($that, $inthat));
    };

    function dot_check ($string) {
        
        $capture    = explode(".", $string);
        $strCount   = count($capture);
        
        if ($strCount == 2 or $strCount == 3) {
            
            return $capture[0];
            
        } else if ($strCount == 4) {
            
            return $capture[0].$capture[1];
            
        } else if ($strCount == 5) {
            
            return $capture[0].$capture[1].$capture[2];
            
        } else {
            
            return $string;
            
        }
        
    }

?>