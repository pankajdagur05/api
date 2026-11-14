<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$sid=$obj['sid'];
$ttime=$obj['ttime'];
$staff=$obj['staff'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
$arr3['status']=true;
$arr3['message']='success';
$arr3['data']=array();
if($obj['fromdate']){
$fromdate=$obj['fromdate'];
}else{
$fromdate=$date;    
}
$q="select staff_attendance.*,staff.name as sname from staff_attendance INNER JOIN staff ON staff.id=staff_attendance.sid where staff_attendance.subdomain='$branch' and staff_attendance.status='p' and staff_attendance.idate='$fromdate' order by staff.name asc";
$s=mysqli_query($host,$q);

 while($staff=mysqli_fetch_array($s)){
      $turn=count(explode(",",$staff['time'])); 
      $row['name']=$staff['sname'];
      $row['id']=$staff['id'];
      $row['data']=array();
      $arr=explode(",",$staff['time']); $arr2=explode(",",$staff['uploadby']); for($a=0; $a<$turn; $a++){ 
                  if($arr[$a+1]){
              $start=strtotime($staff['idate'].$arr[$a]);
              $end=strtotime($staff['idate'].$arr[$a+1]);
$dateDiff = intval(($end-$start)/60);
$hours = intval($dateDiff/60);
$minutes = $dateDiff%60;   
                  }else{
                     $hours=0; 
                     $minutes=0;
                  }
                  
                  $row2['date']=date('d-m-Y',strtotime($staff['idate']));
                  $row2['checkin']=$arr[$a];
                  $row2['checkinby']=$arr2[$a];
                  $row2['checkout']=$arr[$a+1];
                  $row2['checkoutby']=$arr2[$a+1];
                  $row2['gape']=$hours." Hrs ".$minutes." Mint.";
                  $row['data'][]=$row2;
                  
                  $a++; 
                  
      }
     $arr3['data'][]=$row;
 }
 

}else{
    $arr3['status']=false;
    $arr3['message']='Token Not Match';
}

echo $json = json_encode($arr3);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>