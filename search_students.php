<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
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
 $arr['tag'][]=array();

$q="select * from students where name like '%$search%' and cid='$branch' and (status='Active' or status IS NULL)  and session='$session'";
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     $sid=$d['id'];
      $row['sid']=$sid;
 $row['name']=$d['name'];
 $row['fname']=$d['fname'];
 $row['stream']=$d['stream'];
 $row['standard']=$d['standard'];
  $row['mname']=$d['mname'];
   $row['gender']=$d['gender'];
    $row['smobile']=$d['smobile'];
     $row['gmobile']=$d['gmobile'];
     $row['email']=$d['email'];
     $row['address']=$d['address'];
     $row['gmobile']=$d['gmobile'];
     $row['batch']=$d['batch'];
     
     $arr['data'][]=$row;
     
     
      $s1=mysqli_query($host,"select * from students_tag INNER JOIN create_status ON create_status.id=students_tag.statusid where students_tag.sid='$sid'");  
while($d1=mysqli_fetch_array($s1)){
    $row21['name']=$d1['name'];
    $arr['tag'][]=$row21;
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