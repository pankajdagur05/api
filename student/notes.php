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
    
 $s=mysqli_query($host,"select * from notes where standard='$standard' and cid='$user' and dele='0' and type='$typed' order by date desc");  
while($d=mysqli_fetch_array($s)){
    $id=$d['id'];
 $row['title']=$d['title'];   
  $row['description']=$d['description'];   
   $row['path']=$d['path']==""?NULL:"https://edpluss.com/school/admin-panel/cpanel/upload/notes/".$d['path'];   
    $row['subject']=$d['subject'];   
     $row['uploadby']=$d['user']; 
     
     
    //  $row['date']=date("d M Y",$d['date']); 
     
     
      $dbDate = trim($d['date']);        

$row['date'] = !empty($dbDate)
    ? (is_numeric($dbDate) 
        ? date("d F Y", (int)$dbDate) 
        : date("d F Y", strtotime($dbDate)))
    : null;
     
     $row['time']=date("h:i:s",strtotime($d['date'])); 
     $row['syllabus']=array();
     
     $s2=mysqli_query($host,"select * from sync where cid='$user' and featureid='$id'");
     while($d2=mysqli_fetch_array($s2)){
         $row2['title']=$d2['title'];
         $row2['type']=$d2['type'];
         $row['syllabus'][]=$row2;
     }
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