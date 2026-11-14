<?php
include("../../db.php");  
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branch=$obj['branch'];
$type='admin';
$date=date('Y-m-d');
$time=date('h:i:s');
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

$arr['status']=true;
$arr['message']="select * from stream where cid='$user' and (status='Active' or status IS NULL)";
$arr['data']=array();

$s3=mysqli_query($host,"select * from stream where cid='$user' and (status='Active' or status IS NULL)");
while($d3=mysqli_fetch_array($s3)){
$stream=$d3['id'];
$row2['name']=$d3['name'];   
$row2['data']=array();
$idate=$obj['idate']==""?$date:$obj['idate'];
$s=mysqli_query($host,"select * from standard where cid='$user' and (status='active' or status IS NULL) and type='$stream' order by num asc");
while($d=mysqli_fetch_array($s)){
    $standard=$d['name'];
    $s2=mysqli_query($host,"select id from students where cid='$user' and status='Active' and session='$session' and standard='$standard'");
    $r2=mysqli_num_rows($s2);
    $s1=mysqli_query($host,"select sum(if(attendance.status='p',1,0)),sum(if(attendance.status='a',1,0)),sum(if(attendance.status='l',1,0)) from attendance inner join students ON students.id=attendance.sid where attendance.subdomain='$user' and attendance.idate='$idate' and students.standard='$standard' and (students.status='Active' or students.status IS NULL)");
    $d1=mysqli_fetch_array($s1);
    $row['name']=$d['name'];
    $row['total']=$r2;
    $row['p']=$d1[0];
    $row['a']=$d1[1];
    $row['l']=$d1[2];
    $row2['data'][]=$row;
}
 $arr['data'][]=$row2;
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