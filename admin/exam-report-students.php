<?php
include("../../db.php");  
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$standard=$_POST['standard'];
$section=$_POST['section'];
$subject=$_POST['subject'];
$exam=$_POST['exam'];
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){



$q="select name,id from students where cid='$branch' and standard='$standard' and session='$session' and batch='$section' order by id desc";
$arr['status']=true;
$arr['message']=$q;
$arr['data']=array();

$s=mysqli_query($host,$q);
while($d=mysqli_fetch_array($s)){ 
$sid=$d['id'];
$s1=mysqli_query($host,"select outoff,obtain from uploadresult where cid='$branch' and sid='$sid' and exam='$exam' and subject='$subject'");
$d1=mysqli_fetch_array($s1);
$row['name']=$d['name'];
$row['outoff']=$d1['outoff'];
$row['obtain']=$d1['obtain'];
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