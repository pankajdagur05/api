<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$username=$obj['userid'];
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
       $arr['status']=true;
    $arr['message']='Success';
$arr['data']=array();
$s=mysqli_query($host,"select * from paperslot where cid='$user' and sid='$username' order by id desc");
while($d=mysqli_fetch_array($s)){
    
$row['board']=$d['board']; 
$row['medium']=$d['medium']; 
$row['standard']=$d['standard']; 
$row['subject']=$d['subject']; 
$row['book']=$d['book']; 
$row['date']=$d['date']; 
$row['id']=$d['id']; 
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