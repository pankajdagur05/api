<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type=$obj['type'];
$type2=$obj['type2'];
$name=$obj['name'];
$branchid=$obj['branchid'];
$typess=$obj['typefirst'];
$route=$obj['route'];
$session=$obj['session'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$sid=$obj['sid'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
      $arr['status']=true;
      $arr['message']="Select date from trip_students where cid='$user' and sid='$sid' group by date order by id desc";
      $arr['data']=array();
     $s=mysqli_query($host,"Select date from trip_students where cid='$user' and sid='$sid' group by date order by id desc");
     while($d=mysqli_fetch_array($s)){
         $dt=$d['date'];
         $row['date']=date('d M Y',strtotime($dt));
         $s1=mysqli_query($host,"Select time,location from trip_students where cid='$user' and sid='$sid' and date='$dt' and type='pickup' and pickup='0'");
         $d1=mysqli_fetch_array($s1);
         $row['pickupstart']=date('h:i A',strtotime($d1['time']));
          $row['pickupstartlocation']=$d1['location'];
           $s1=mysqli_query($host,"Select time,location from trip_students where cid='$user' and sid='$sid' and date='$dt' and type='drop' and pickup='0'");
         $d1=mysqli_fetch_array($s1);
 $row['pickupend']=date('h:i A',strtotime($d1['time']));
          $row['pickupendlocation']=$d1['location'];
           $s1=mysqli_query($host,"Select time,location from trip_students where cid='$user' and sid='$sid' and date='$dt' and type='pickup' and pickup='1'");
         $d1=mysqli_fetch_array($s1);
$row['dropstart']=date('h:i A',strtotime($d1['time']));
          $row['dropstartlocation']=$d1['location'];
           $s1=mysqli_query($host,"Select time,location from trip_students where cid='$user' and sid='$sid' and date='$dt' and type='drop' and pickup='1'");
         $d1=mysqli_fetch_array($s1);
  $row['dropend']=date('h:i A',strtotime($d1['time']));
          $row['dropendlocation']=$d1['location'];
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