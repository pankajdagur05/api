<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
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

$board=$obj['board'];
$medium=$obj['medium'];
$standard=$obj['standard'];
$subject=$obj['subject'];
$chapter=$obj['chapter'];
$book=$obj['book'];
$descr=$obj['descr'];
$marks=$obj['marks'];
$sid=$username;
$tid=$obj['update'];
$delete=$obj['delete'];
if($chapter){
if($tid){
$q="update paperchapterslot set board='$board',medium='$medium',standard='$standard',subject='$subject',book='$book',date='$date',chapter='$chapter',descr='$descr',marks='$marks' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
$q="insert into paperchapterslot set board='$board',medium='$medium',standard='$standard',book='$book',subject='$subject',date='$date',chapter='$chapter',descr='$descr',marks='$marks',dele='0',cid='$user'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted';
 
} 
}else{

if($delete){
mysqli_query($host,"update paperchapterslot set dele='1' where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
$arr['data']=array();
$s=mysqli_query($host,"select * from paperchapterslot where cid='$user' and board='$board' and book='$book' and medium='$medium' and standard='$standard' and subject='$subject' and dele='0' order by id desc");
while($d=mysqli_fetch_array($s)){
   
$tid=$d['id'];
$row['board']=$d['board']; 
$row['medium']=$d['medium']; 
$row['standard']=$d['standard']; 
$row['subject']=$d['subject']; 
$row['chapter']=$d['chapter'];
$row['book']=$d['book'];
$row['descr']=$d['descr']; 
$row['marks']=$d['marks']; 
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