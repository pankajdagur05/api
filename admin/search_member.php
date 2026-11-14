<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$stype=$obj['type'];
 $branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){

$branch=$branchid;
      $user=$branch;
      $userby=$type;

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];


$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
 
if(strtolower($stype)=='staff')
{
    $q="select id,name,gname,mobile,post from staff where name like '%$search%' and branch='$branch' and (status='Active' or status IS NULL)";

}else
{
    $q="select id,name,fname as gname,smobile as mobile,standard as post from students where name like '%$search%' and cid='$branch' and tc_date IS NULL and session='$session'";

}
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     
     $sid=$d['id'];
     $s2=mysqli_query($host,"select sum(if(returndate<$date,1,0)),count(id) from book_issue where type='$stype' and sid='$sid' and status='issued' and cid='$user'");
     $d2=mysqli_fetch_array($s2);
     $row['name']=$d['name'];
     $row['gname']=$d['gname'];
     $row['mobile']=$d['mobile'];
      $row['standard']=$d['post'];
     $row['id']=$d['id'];
     $row['issue']=$d2[1];
     $row['over']=$d2[0];
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