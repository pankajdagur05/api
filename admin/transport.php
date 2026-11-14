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

$date=date('Y-m-d');
$time=date('h:i:s');
if($obj['idate']){
$idate=$obj['idate'];    
}else{
$idate=$date;
}
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
      $arr['status']=true;
      $arr['message']="success";
      $arr['data']=array();
        $arr['data2']=array();
      
      if($route){
         $s1=mysqli_query($host,"select * from session where cid='$user' order by id desc limit 1");
        $d1=mysqli_fetch_array($s1);
        $session=$d1['name'];
         $arr['message']="success"."Select * from students where cid='$user' and route='$route' and status='Active' and session='$session' order by priority asc";
        $s=mysqli_query($host,"Select * from students where cid='$user' and route='$route' and status='Active' and session='$session' order by priority asc");  
        while($d=mysqli_fetch_array($s)){
            $sid=$d['id'];
            $row['id']=$d['id'];
            $row['name']=$d['name'];
            $row['fname']=$d['fname'];
            $row['standard']=$d['standard'];
            $loca=explode(",",$d['location']);
            $row['lat']=$loca[0];
            $row['lon']=$loca[1];
            $s1=mysqli_query($host,"select * from trip_students where cid='$user' and sid='$sid' and date='$idate' and type='pickup'");
            if(mysqli_num_rows($s1)==0){
                $row['pickup']="Pending";
            }else{
              $d1=mysqli_fetch_array($s1);
              if($d1['pickup']==1){
                   $row['pickup']="Picked";
                   $row['pickup_location']=$d1['location'];
                   $row['pickup_time']=date('h:i A',strtotime($d1['time']));
              }else{
                   $row['pickup']="Not Pickup";
              }
            }
            
             $s1=mysqli_query($host,"select * from trip_students where cid='$user' and sid='$sid' and date='$idate' and type='drop'");
            if(mysqli_num_rows($s1)==0){
                $row['drop']="Pending";
            }else{
              $d1=mysqli_fetch_array($s1);
              if($d1['pickup']==1){
                   $row['drop']="Droped";
                   $row['drop_location']=$d1['location'];
                   $row['drop_time']=date('h:i A',strtotime($d1['time']));
              }else{
                   $row['drop']="Not Droped";
              }
            }
            $arr['data2'][]=$row;
            
        }
          
      }else{
      
      $s=mysqli_query($host,"select route.*,driver.name as dname,driver.lat,driver.lon,driver.update_time,driver.profile from route INNER JOIN driver ON driver.mobile=route.driver where route.cid='$user' and driver.cid='$user'");
      while($d=mysqli_fetch_array($s)){
          $route=$d['name'];
          $row['route']=$d['name'];
           $row['vechile']=$d['vechile'];
            $row['driver']=$d['dname'];
             $row['lat']=$d['lat'];
             $row['photo']=$d['profile']==NULL?NULL:"https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['profile'];
               $row['lon']=$d['lon'];
                if($d['update_time']){
                 $row['time']=date('h:i A',$d['update_time']);
                  $row['date']=date('d M Y',$d['update_time']);
                }else
                {
               
                 $row['time']="";
                  $row['date']="No Update";     
                }
              $row['datee']="";
                  $s1=mysqli_query($host,"select * from trip where route='$route' and cid='$user' and date='$idate'");
        if(mysqli_num_rows($s1)==0){
        $row['trip']="Not Started";    
        }else{
        $d1=mysqli_fetch_array($s1);
        
        if($d1['pickup_start'] and $d1['pickup_end']==NULL){
             $row['trip']="Pickup Ongoing";
              $row['datee']=date('d M Y h:i',$d1['pickup_start']);
        }else{
          if($d1['pickup_end'] and $d1['drop_start']==NULL){
               $row['trip']="Pickup End";
              $row['datee']=date('d M Y h:i',$d1['pickup_end']);
          }else
          {
          if($d1['drop_start'] and $d1['drop_end']==NULL){
             $row['trip']="Drop Ongoing";
              $row['datee']=date('d M Y h:i',$d1['drop_start']);
        }else{
              $row['trip']="Drop End";
              $row['datee']=date('d M Y h:i',$d1['drop_end']);
        }  
        }
        }
        }
                  
                  
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