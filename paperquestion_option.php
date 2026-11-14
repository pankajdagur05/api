<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$username=$_POST['userid'];
$token=$_POST['token'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $user=$branch;
      $userby=$d3['name'];
$qid=$_POST['qid'];
$options=$_POST['option'];
$headline=$_POST['headline'];
 
 
$sid=$username;
$tid=$_POST['update'];
$delete=$_POST['delete'];
if($options){

 if(!empty($_FILES['file'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/upload/question/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx'); 
     $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = $token.$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){ 
            
          $uqst=$fileName;
        } 
    } 
}else{
  if($tid){
     $s1=mysqli_query($host,"select * from paper_option where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $uqst=$d1['file'];
  }
}    
    
if($tid){
$q="update paper_option set  qid='$qid',options='$options',file='$uqst',date='$date' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
$q="insert into paper_option set  qid='$qid',options='$options',file='$uqst',date='$date',cid='$user'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted';
 
} 
}else{

if($delete){
mysqli_query($host,"delete from paper_option where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
 
 




 
}
$arr['data']=array();
$s=mysqli_query($host,"select * from paper_option where cid='$user' and qid='$qid' order by id desc");
while($d=mysqli_fetch_array($s)){
   
$tid=$d['id'];
$row['qid']=$d['qid']; 
$row['option']=$d['options']; 
$row['path']=$d['file']==''?NULL:'https://smartechindia.in/leadpanel/cpanel/upload/question/'.$d['file'];
$row['date']=$d['date']; 
$row['id']=$tid; 
$arr['data'][]=$row;
    
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