<?php

    /*$Connect = mysqli_connect("localhost", "thecamp7_admin", "573-Gzw-wD2-HLH", "thecamp7_ALBUM");*/

    $Connect = mysqli_connect("localhost", "root", "R77ZdvPs2qnzQYSu", "album");

    if (mysqli_connect_errno()){
        
        echo "Error occurred while attempting to connect to the database ".mysqli_connect_errno();
        
    }

?>