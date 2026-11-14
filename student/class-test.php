<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type=$_POST['type'];
$typed=$_POST['typed'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    
    $s0=mysqli_query($host,"select cid,standard from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0[0];
    $standard=$d0[1];
    
 $s=mysqli_query($host,"select * from notes where standard='$standard' and cid='$user' and dele='0' and type='classtest' order by date desc");  
while($d=mysqli_fetch_array($s)){
    $id=$d['id'];
    $s2=mysqli_query($host,"select * from classtest where classtest='$id' and cid='$user' and sid='$sid'");
    if(mysqli_num_rows($s2)>0){
    $d2=mysqli_fetch_array($s2);
 $row['title']=$d['title']; 
 $row['obtain']=$d2['mark']; 
 $row['outoff']=$d2['outoff']; 
  $row['description']=$d['description'];   
   $row['path']=$d['path']==""?NULL:"https://edpluss.com/school/admin-panel/cpanel/upload/notes/".$d['path'];   
    $row['subject']=$d['subject'];   
     $row['uploadby']=$d['user']; 
     $row['date']=date("d M Y",strtotime($d2['date'])); 
     $row['time']=date("h:i:s",strtotime($d2['date'])); 
     $row['syllabus']=array();
     
     $s3=mysqli_query($host,"select * from sync where cid='$user' and featureid='$id'");
     while($d3=mysqli_fetch_array($s3)){
         $row2['title']=$d3['title'];
         $row2['type']=$d3['type'];
         $row['syllabus'][]=$row2;
     }
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