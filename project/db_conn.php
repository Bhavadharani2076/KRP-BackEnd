<?php
session_start();

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
  $Protocol = "https://";
}else{
  $Protocol = "http://";
}

define("PROD_ENV","OFF"); //  ON or OFF - For DB

define("PAYMENT_KEY","rzp_test_zTv48rEyiAaPfG");

$CurrentServer = $_SERVER['SERVER_NAME'];

if($CurrentServer == "localhost")
{
    // define("SITE_URL",$Protocol.$_SERVER['SERVER_NAME']."/KPR_Project"); 
    // define("ROOT",$_SERVER['DOCUMENT_ROOT']."/KPR_Project"); 
    // define("UPLOAD_PATH",$_SERVER['DOCUMENT_ROOT']."/KPR_Project/uploads"); 
    // define("UPLOAD_URL",SITE_URL."/uploads"); 

    if(PROD_ENV == 'ON'){
      define("DB_SERVER","mocha3036.mochahost.com"); 
      define("DB_USER","deuneic1_aakash"); 
      define("DB_PASS","admin@3112"); 
      define("DB_NAME","deuneic1_kpr"); 
    }else{
      define("DB_SERVER","localhost"); 
      define("DB_USER","root"); 
      define("DB_PASS",""); 
      define("DB_NAME","project"); 
    }
    

}else{
    // define("SITE_URL",$Protocol.$_SERVER['SERVER_NAME']."/site/parlor"); 
    // define("ROOT",$_SERVER['DOCUMENT_ROOT']."/site/parlor"); 
    // define("UPLOAD_PATH",$_SERVER['DOCUMENT_ROOT']."/site/parlor/uploads"); 
    // define("UPLOAD_URL",SITE_URL."/uploads"); 

    define("DB_SERVER","mocha3036.mochahost.com"); 
    define("DB_USER","deuneic1_aakash"); 
    define("DB_PASS","admin@3112"); 
    define("DB_NAME","deuneic1_kpr"); 
}

// Create connection
$conn =  mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";

?>