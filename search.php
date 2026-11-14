<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=trim($obj['search']);
$stype=$obj['type'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){
$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];


$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
 
 if($stype=='student' or $stype=='parent'){
if($stype=='parent')
{
    $q="select * from students where ( fname like '%$search%' or standard like '%$search%' or name like '%$search%' ) and cid='$branch' and status='Active' and session='$session'";

}else
{
    if($stype=='student')
    {
      $q="select * from students where ( name like '%$search%' or standard like '%$search%' ) and cid='$branch' and status='Active' and session='$session'";
    }
    else
    {
        $q="select * from staff where name like '%$search%' and cid='$branch' and status='Active' and session='$session'";
    }
}

 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     $sid=$d['id'];
      $row['sid']=$sid;
if($stype=='parent')
{
 $row['name']=$d['fname']." - ".$d['name']." - ".$d['standard'];
  $row['standard']="";
   $row['gender']=$d['gender'];
  $row['gnumber']=$d['gmobile'];
}
else
{
    if($stype=='student')
    {
   $row['name']=$d['name']; 
   $row['standard']=$d['stream'].' - '.$d['standard'];
   $row['gender']=$d['gender'];
   $row['gnumber']=$d['smobile'];
   }
   else
   {
   $row['name']=$d['name']; 
   $row['standard']=$d['post'];
   $row['gender']=$d['gender'];
   $row['gnumber']=$d['mobile'];    
   }
}
 
 $row['gname']=$d['fname'];

     
     $arr['data'][]=$row;
     
      
 }
 }else{
     

$q="select * from staff where name like '%$search%' and branch='$branch' and status='Active'";
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     $sid=$d['id'];
      $row['sid']=$sid;
 $row['name']=$d['name'];
 $row['gname']=$d['gname'];
 $row['standard']='';
     
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