<?php 

function database_connection() {
    static $con;
    if ($con===NULL){ 
        $con = mysqli_connect ("localhost", "root", "password", "library");
    }
    return $con;
}