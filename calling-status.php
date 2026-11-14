<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_REQUEST['delete'];
$username=$_REQUEST['userid'];
$subdomain=$_REQUEST['subdomain'];
$token=$_REQUEST['token'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
    
$rid=$_REQUEST['rid'];
$sid=$_REQUEST['sid'];
$remark=$_REQUEST['remark'];
$rating=$_REQUEST['rating'];
$status=$_REQUEST['status'];
$close=$_REQUEST['close'];
$mode=$_REQUEST['mode'];
$type=$_REQUEST['type'];
$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staffid=$d3['name'];
      $appointment=$_REQUEST['adate'];
if($status){
if($_FILES['file']['tmp_name']){
    $today=time();
 $targetDir = "../cpanel/upload/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx','mp3','MP3','Mp3'); 
     $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid().$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){ 
           $file=$fileName;
        }
        
    }
}else
{
    if($_FILES['files']['tmp_name']){
    $today=time();
 $targetDir = "../cpanel/upload/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx','mp3','MP3','Mp3'); 
     $fileType = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid().$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['files']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['files']['tmp_name'], $targetFilePath)){ 
           $file=$fileName;
        }
        
    }
}
}


$q="insert into  calling_status set status='$status',remark='$remark',type='$type',rid='$rid',mode='$mode',close='$close',attch='$file',rating='$rating',sid='$sid',staff='$username',uploadby='$staffid',date='$date',time='$time',cid='$branch'";
if(mysqli_query($host,$q)){
    
if(strtolower($type)=='finance'){
    $q="insert into  fees_status set idate='$appointment',remark='$remark',date='$date',cid='$branch',sid='$sid',type='staff',uid='$staffid'";
if(mysqli_query($host,$q)){
 $s03=mysqli_query($host,"select * from fees_status where sid='$sid' and cid='$branch'");
$ro=mysqli_num_rows($s03);
if($ro>0){
$s04=mysqli_query($host,"select * from lable where level='$ro'");
$d04=mysqli_fetch_array($s04);
$lbl=$d04['name'];
mysqli_query($host,"update students set feesflag='$lbl' where id='$sid' and cid='$branch'");
}   
  
}
}    
  $arr['status']=true;
$arr['message']="Successfully Update";  
}else{
  $arr['status']=false;
$arr['message']='Something Went Wrong';  
}

 
}else{
   
   
    $arr['status']=true;
$arr['message']='Success'; 
$arr['data']=array();
$ss=mysqli_query($host,"select * from calling_status where sid='$sid' and rid='$rid' and cid='$branch'");
while($dd=mysqli_fetch_array($ss)){
$exp=explode(".",$dd['attch']);
$exten=$exp[1];
if($exten=='jpg' or $exten=='jpeg' or $exten=='JPG' or $exten=='Jpg' or $exten=='PNG' or $exten=='Png' or $exten=='png'){ $tt="image";  }else{   $tt="record"; } 
$row['id']=$dd['id'];  
$row['status']=$dd['status'];  
$row['mode']=$dd['mode'];  
$row['remark']=$dd['remark'];  
$row['file']=$dd['attch']==""?"":"https://edpluss.com/school/admin-panel/cpanel/upload/".$dd['attch'];  
$row['rating']=$dd['rating'];  
$row['type']=$tt;
$arr['data'][]=$row;
}
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