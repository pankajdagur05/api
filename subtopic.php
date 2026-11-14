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


$cid=$obj['cid'];
$name=$obj['name'];
$ttid=$obj['tid'];

$sid=$username;
$tid=$obj['update'];
$delete=$obj['delete'];
if($name){
if($tid){
$q="update paper_subtopic set name='$name',date='$date' where id='$tid' and topicid='$ttid' and chapterid='$cid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{    
$q="insert into paper_subtopic set name='$name',date='$date',topicid='$ttid' ,chapterid='$cid',dele='0',cid='$user'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted';
 
} 
}else{

if($delete){
mysqli_query($host,"update paper_subtopic set dele='1' where id='$delete' and chapterid='$cid'  and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
$arr['status']=true;
$arr['message']='success';  
}
 
 
 
$arr['data']=array();
$arr['topic']=array();

$ss=mysqli_query($host,"select * from paper_topic where cid='$user' and chapterid='$cid' and dele='0' order by id desc");
while($dd=mysqli_fetch_array($ss))
{
  $row1['name']=$dd['name']; 
   $row1['id']=$dd['id']; 
  $arr['topic'][]=$row1;
}

$s=mysqli_query($host,"select paper_subtopic.*,paper_topic.name as tname from paper_subtopic inner join paper_topic on paper_subtopic.topicid=paper_topic.id where paper_subtopic.cid='$user' and paper_subtopic.chapterid='$cid' and paper_subtopic.dele='0' order by paper_subtopic.id desc");
while($d=mysqli_fetch_array($s)){

$tid=$d['id'];
$row['name']=$d['name']; 
$row['cid']=$d['chapterid']; 
$row['tid']=$d['topicid']; 
$row['tname']=$d['tname']; 
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