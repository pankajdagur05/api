<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$obj['sid'];
$username=$obj['username'];
$token=$obj['token'];
$type=$obj['type'];
$route=$obj['route'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){ 
        $arr['status']=true;
        $arr['message']="Success";
        $s=mysqli_query($host,"select cid from students where id='$sid'");
        $d=mysqli_fetch_array($s);
        $user=$d['cid'];
        if($mode==1){
        $s=mysqli_query($host,"select driver.lat,driver.lon,driver.update_time from route INNER JOIN driver ON driver.mobile=route.driver where driver.cid='$user' and route.name='$route' and route.cid='$user' ");
        $arr['lat']=$d[0];
         $arr['lon']=$d[1];
          $arr['date']=date('d M',strtotime($d[0]));
           $arr['time']=date('h:i A',strtotime($d[0]));
        }else{
            $arr['data']=array();
        $s=mysqli_query($host,"select * from trip_students where sid='$sid' and type='pickup' and pickup='1' order by id desc limit 20");
        while($d=mysqli_fetch_array($s)){
            
             $date=$d['date'];
             $s1=mysqli_query($host,"select * from trip_students where sid='$sid' and date='$date' and type='drop' and pickup='1'");
             $d1=mysqli_fetch_array($s1);
             $row['p_location']=$d['location'];
             $row['p_time']=date('h:i A',$d['time']);
              $row['d_location']=$d1['location'];
             $row['d_time']=date('h:i A',$d1['time']);
             $row['date']=date('d M Y',strtotime($d['date']));
             $arr['data'][]=$row;
        }
        $s=mysqli_query($host,"select * from trip where route='$route' and cid='$user' and date='$date'");
        if(mysqli_num_rows($s)==0){
        $arr['trip']="Not Started";    
        }else{
        $d=mysqli_fetch_array($s);
        if($d['pickup_start'] and $d['pickup_end']==NULL){
             $arr['trip']="On Going";
        }else{
          if($d['drop_start'] and $d['drop_end']==NULL){
             $arr['trip']="On Going";
        }else{
             $arr['trip']="Not Started";
        }  
        }
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