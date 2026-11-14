<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);  
$token=$_POST['token'];
$sid=$_POST['sid'];
$date=date('Y-m-d');
$time=date('h:i:s a');
$today=time();
$tim=time();
if($sid){
    
function resizeImage($imagePath, $newImagePath, $maxWidth, $maxHeight)
{
    // Get the original image dimensions
    list($originalWidth, $originalHeight, $imageType) = getimagesize($imagePath);

    // Calculate the aspect ratio
    $ratio = $originalWidth / $originalHeight;

    // Calculate the new dimensions
    if ($maxWidth / $maxHeight > $ratio) {
        $newWidth = $maxHeight * $ratio;
        $newHeight = $maxHeight;
    } else {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;
    }

    // Create a new image resource with the desired dimensions
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Load the original image based on its type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $originalImage = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
            $originalImage = imagecreatefrompng($imagePath);
            break;
        case IMAGETYPE_GIF:
            $originalImage = imagecreatefromgif($imagePath);
            break;
        // Add support for other image formats if needed

        default:
            // Unsupported image type
            return false;
    }

    // Resize the image
    imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    // Save the resized image as JPEG
    imagejpeg($newImage, $newImagePath);

    // Free up memory
    imagedestroy($originalImage);
    imagedestroy($newImage);

    return true;
}



    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    
    if($r==1){ 
$wname=strtolower(date('D'));
   $s3=mysqli_query($host,"select sub_domain,branch,name,$wname from staff where id='$sid'");
$d3=mysqli_fetch_array($s3);
$branch=$d3['branch']; 
$name=$d3['name'];
$subdomain=$d3['sub_domain'];
$attend=explode(",",$d3[$wname]);
if(strtolower($attend[0])=='yes'){
$fromtime=$attend[1]; 
$totime=$attend[2]; 
}

 if(!empty($_FILES['file'])){ 
     
   
   
     $maxWidth = 200;
    $maxHeight = 200;

    // Get the uploaded image details
    $image = $_FILES['file'];
    $imagePath = $image['tmp_name'];
    $path=$_FILES['myform']['name'];
$ext=pathinfo($path, PATHINFO_EXTENSION);

    // Generate a unique filename for the resized image
    $fileName=time().uniqid().".jpg";
      $newImagePath='../../cpanel/access/upload/staff/'.$fileName;

    // Resize the image
    if (resizeImage($imagePath, $newImagePath, $maxWidth, $maxHeight)){  
             
 
$ss=mysqli_query($host,"select id,time,status from staff_attendance where sid='$sid' and idate='$date'"); 
$dd=mysqli_fetch_array($ss);
if(mysqli_num_rows($ss)>0 and strtolower($dd['status'])=='p'){
if(count(explode(",",$dd['time']))%2==0){
    $msg=$name." Check In"; 
     $msg2=$name."  Check In Recently ".$time;
}else{
 $msg=$name." Check Out";  
 $msg2=$name."  Check Out Recently ".$time;
}
mysqli_query($host,"insert into notification set msg='Attendance Notification',mode='$msg2',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");

mysqli_query($host,"update staff_attendance set time=concat(time,',$time'),uploadby=concat(uploadby,',self'),punch=concat(punch,',photo'),lat=concat(lat,',$fileName'),lon=concat(lon,',$fileName') where sid='$sid' and subdomain='$branch' and idate='$date'");    
}else{

if(strtolower($dd['status'])=='a'){
  mysqli_query($host,"delete from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$date'");   
}           
    
$msg=$name." Check In";   
   $msg2=$name."  Check In Recently ".$time;
   mysqli_query($host,"insert into notification set msg='Attendance Notification',mode='$msg2',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");

   
mysqli_query($host,"insert into staff_attendance set fromtime='$fromtime',totime='$totime',sid='$sid',status='P',idate='$date',date='$date',time='$time',uploadby='self',lat='$fileName',lon='$fileName',subdomain='$branch',punch='photo'");    
}
$arr['status']=true;
$arr['message']=$msg; 

 
}else{
    $arr['status']=false;
    $arr['message']="Something Went wrong Try Again";
}
 
    
    
 }else{
    $arr['status']=false;
    $arr['message']="Something Went wrong Try Again";   
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