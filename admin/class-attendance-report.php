<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$fromdate=$obj['fromdate'];
$todate=$obj['todate'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$x=$obj['standard'];
$y=$obj['date'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
         $arr['status']=true;
    $arr['message']="success";
    $arr['data']=array();
         $s=mysqli_query($host,"select students.name,students.fname,attendance.status from attendance inner join students ON students.id=attendance.sid where attendance.subdomain='$user' and students.cid='$user' and attendance.idate='$y' and students.standard='$x'"); while($d=mysqli_fetch_array($s)){
             
             $row['name']=$d[0];
             $row['fname']=$d[1];
             $row['status']=$d[2];
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