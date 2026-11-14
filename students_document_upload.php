<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_POST['delete'];
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
$name=$_POST['name'];
$today=time();
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
         $sid=$_POST['sid'];
            $fn=$_POST['name'];
        if(!empty($_FILES['file'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/access/upload/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx'); 
     $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = $token.$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        
        if(strtolower($fn)=='photos'){
            
// get the name of the uploaded file
$target_file=basename($_FILES["file"]["name"]);

// get the image type
$imageFileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));

// check if the file is an image
 
// create a new image from the uploaded file
if($imageFileType == "jpg" || $imageFileType == "jpeg") {
    

    $image = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
} elseif($imageFileType == "png") {
    
$image=imagecreatefrompng($_FILES["file"]["tmp_name"]);
} elseif($imageFileType == "gif") {

$image = imagecreatefromgif($_FILES["file"]["tmp_name"]);
} else {
   
}

// get the image width and height
$width = imagesx($image);
$height = imagesy($image);

// set the new width and height
$new_width = 200;
$new_height = ($new_width / $width) * $height;

// create a new image with the new dimensions
$new_image = imagecreatetruecolor($new_width, $new_height);

// resize the image
imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// save the resized image to the specified directory
if($imageFileType == "jpg" || $imageFileType == "jpeg") {
    
    imagejpeg($new_image, $targetFilePath);
    
} elseif($imageFileType == "png") {
    
    imagepng($new_image,$targetFilePath);
    
} elseif($imageFileType == "gif") {
    imagegif($new_image, $targetFilePath);
} 
 $tt=1;  
 
            }else{
             if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){
              $tt=1;   
             }
        }
        
        
        if($tt==1){ 
            
            $rk=mysqli_query($host,"select * from docs where name='$fn' and sid='$sid' and cid='$branch'");
            if(mysqli_num_rows($rk)>0)
            {
                   $q="update docs set filename='$fileName' where name='$fn' and sid='$sid' and cid='$branch'";
                  mysqli_query($host,$q);
            }else
            {
                  $q="insert into docs set name='$fn',filename='$fileName',sid='$sid',cid='$branch',date='$date'";
                mysqli_query($host,$q);  
                
            }
          
             
         $arr['status']=true;
    $arr['message']='File Uploaded';
    $arr['photo']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$fileName;
        }else{
             
         $arr['status']=false;
    $arr['message']='Something Went Wrong';
        } 
    } else{
        
         $arr['status']=false;
    $arr['message']='File Formate Not Support';
    }
}else{
     $arr['status']=false;
    $arr['message']='File Not Exist';
}
        
    
     
      
       
        
    }else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}

echo $json = json_encode($arr);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>