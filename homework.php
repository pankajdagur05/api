<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$username=$_POST['userid'];
$token=$_POST['token'];
$type='staff';
$date=time();

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
$title=$_POST['title'];
$desc=mysqli_real_escape_string($host,$_POST['desc']);
$subject=$_POST['subject'];
$standard=$_POST['standard'];

$sid=$username;
$tid=$_POST['update'];
$delete=$_POST['delete'];
if($title){

 if(!empty($_FILES['file'])){ 
     
    // File upload configuration 
    $targetDir = "../cpanel/upload/homework/"; 
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
     $s1=mysqli_query($host,"select * from homework where id='$tid' and cid='$user'"); 
     $d1=mysqli_fetch_array($s1);
     $uqst=$d1['path'];
  }
}    
    
 
        
    
    
if($tid){
$q="update homework set title='$title',description='$desc',path='$uqst',standard='$standard',subject='$subject',cid='$user',date='$date',user='$userby',dele='0' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
 $q="insert into homework set title='$title',description='$desc',path='$uqst',standard='$standard',subject='$subject',cid='$user',user='$userby',date='$date',dele='0'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted';
 
} 
}else{

if($delete){
mysqli_query($host,"update homework set dele='1' where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
 
 
$arr['data']=array();
$arr['topic']=array();


$s4=mysqli_query($host,"SELECT timetabledata.standard as standard FROM timetabledata INNER JOIN time_table ON time_table.id=timetabledata.timetable where time_table.status='Active' and timetabledata.cid='$user' and timetabledata.sid='$username'");
while($d4=mysqli_fetch_array($s4)){
$standard=$d4['standard'];
$row1['name']=$d4['standard'];
$row1['subtopic']=array();

$s0=mysqli_query($host,"SELECT timetabledata.subject as subject FROM timetabledata INNER JOIN time_table ON time_table.id=timetabledata.timetable where time_table.status='Active' and timetabledata.cid='$user' and timetabledata.standard='$standard' and timetabledata.sid='$username'");
while($d0=mysqli_fetch_array($s0)){
 $row4['name']=$d0['subject'];
$row1['subtopic'][]=$row4;    
    
}

}



  $s4=mysqli_query($host,"select * from staffaccess where cid='$branch' and staff='$staff' and (feature='All' or feature='Homework') and type='class' group by class"); 
         while($d4=mysqli_fetch_array($s4)){
             $stream=$d4['class'];
             $row1['name']=$d4['class'];
                $row1['subtopic']=array();
                
                $s0=mysqli_query($host,"select * from subject_link_with_class where user='$branch' and stream='$stream'");
                while($d0=mysqli_fetch_array($s0)){
                    $row4['name']=$d0['name'];
                    $row1['subtopic'][]=$row4;
                }
                
              $arr['topic'][]=$row1;
              
              
         }
         
         
            
              
              
         


$s1=mysqli_query($host,"select * from homework where cid='$user' and dele='0' order by id desc");
while($d=mysqli_fetch_array($s1)){
   
$tid=$d['id'];
$row['title']=$d['title']; 
$row['desc']=$d['description']; 
$row['standard']=$d['standard']; 
$row['subject']=$d['subject']; 
$row['path']=$d['path']==''?NULL:'https://edpluss.com/school/admin-panel/cpanel/upload/notes/cpanel/upload/homework/'.$d['path'];
$row['date']=date('d M Y',strtotime($d['date'])); 
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