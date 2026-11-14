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
      $userby=$d3['name'];;
$chapter=$_POST['chapter'];
$topic=$_POST['topic'];
$headline=$_POST['headline'];
$type=$_POST['type'];
$lavel=$_POST['level'];
$marks=$_POST['marks'];
$times=$_POST['qtime'];
$qst=str_replace(",","⸗",$_POST['question']);

$hqst=str_replace(",","⸗",$_POST['hquestion']);
$answer=str_replace(",","⸗",$_POST['answer']);
 $hanswer=str_replace(",","⸗",$_POST['hanswer']);
$sid=$username;
$tid=$_POST['update'];
$delete=$_POST['delete'];
$today=date();
if($topic){

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
     $s1=mysqli_query($host,"select * from paper_question where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $uqst=$d1['uqst'];
  }
}    
    
    

 if(!empty($_FILES['file1'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/upload/question_hindi/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx'); 
     $fileType = pathinfo($_FILES['file1']['name'], PATHINFO_EXTENSION);
    $fileName = $token.$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file1']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['file1']['tmp_name'], $targetFilePath)){ 
            
          $uhqst=$fileName;
        } 
    } 
}else{
  if($tid){
     $s1=mysqli_query($host,"select * from paper_question where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $uhqst=$d1['uhqst'];
  }
}    
  
  
  
   if(!empty($_FILES['file2'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/upload/answer/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx'); 
     $fileType = pathinfo($_FILES['file2']['name'], PATHINFO_EXTENSION);
    $fileName = $token.$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file2']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['file2']['tmp_name'], $targetFilePath)){ 
            
          $aqst=$fileName;
        } 
    } 
}else{
  if($tid){
     $s1=mysqli_query($host,"select * from paper_question where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $aqst=$d1['aqst'];
  }
}    

   if(!empty($_FILES['file3'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/upload/answer_hindi/"; 
    $allowTypes = array('jpg','JPG','JPEG','PNG','png','pdf','PDF','docx'); 
     $fileType = pathinfo($_FILES['file3']['name'], PATHINFO_EXTENSION);
    $fileName = $token.$today.".".$fileType;
    $targetFilePath = $targetDir.$fileName; 
     
    // Check whether file type is valid 
    $filesize = filesize($_FILES['file3']['tmp_name']); // bytes
$filesize = round($filesize / 1024 , 1);
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['file3']['tmp_name'], $targetFilePath)){ 
            
          $haqst=$fileName;
        } 
    } 
}else{
  if($tid){
     $s1=mysqli_query($host,"select * from paper_question where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $haqst=$d1['haqst'];
  }
}    
        
    
    
if($tid){
$q="update paper_question set  chapter='$chapter',topic='$topic',qtime='$times',headline='$headline',type='$type',lavel='$lavel',marks='$marks',qst='$qst',uqst='$uqst',qst_hindi='$hqst',uhqst='$uhqst',answer='$answer',aqst='$aqst',hanswer='$hanswer',haqst='$haqst',date='$date',sid='$sid',userby='$userby' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
 $q="insert into paper_question set chapter='$chapter',topic='$topic',qtime='$times', headline='$headline',type='$type',lavel='$lavel',marks='$marks',qst='$qst',uqst='$uqst',qst_hindi='$hqst',uhqst='$uhqst',answer='$answer',hanswer='$hanswer',haqst='$haqst',aqst='$aqst',date='$date',sid='$sid',userby='$userby',cid='$user',dele='0'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted';
 
} 
}else{

if($delete){
mysqli_query($host,"update paper_question set dele='1' where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
 
 
$arr['data']=array();
$arr['topic']=array();

$ss=mysqli_query($host,"select * from paper_topic where cid='$user' and chapterid='$chapter' and dele='0' order by id desc");
while($dd=mysqli_fetch_array($ss))
{
    $tjid=$dd['id']; 
  $row1['name']=$dd['name']; 
   $row1['id']=$dd['id']; 
   $row1['subtopic']=array();
   $sss=mysqli_query($host,"select * from paper_subtopic where cid='$user' and topicid='$tjid' and chapterid='$chapter' and dele='0' order by id desc");
while($ddd=mysqli_fetch_array($sss))
{
     $row2['name']=$ddd['name']; 
   $row2['id']=$ddd['id']; 
   $row1['subtopic'][]=$row2;
}
   
  $arr['topic'][]=$row1;
}




$s=mysqli_query($host,"select * from paper_question where cid='$user' and chapter='$chapter' and dele='0' order by id desc");
while($d=mysqli_fetch_array($s)){
   
$tid=$d['id'];
$row['chapter']=$d['chapter']; 
$row['topic']=$d['topic']; 
$row['headline']=$d['headline']; 
$row['type']=$d['type']; 
$row['level']=$d['lavel']; 
$row['question']=$d['qst']; 
$row['hquestion']=$d['qst_hindi']; 
$row['answer']=$d['answer']; 
$row['hanswer']=$d['hanswer']; 
$row['qtime']=$d['qtime']; 
$row['marks']=$d['marks']; 
$row['path']=$d['uqst']==''?NULL:'https://smartechindia.in/leadpanel/cpanel/upload/question/'.$d['uqst'];
$row['date']=$d['date']; 
$row['id']=$tid; 
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