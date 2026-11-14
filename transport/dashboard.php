<?php error_reporting(4);
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$username=$obj['userid']; 
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s a');
if($username){
     $s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");
    $r=mysqli_num_rows($s);
    if($r==1){
        $arr['status']=true;
        $arr['message']="success";
        $arr['time']=10000;
        $s=mysqli_query($host,"select * from driver where mobile='$username' and status='Active'");
        $d=mysqli_fetch_array($s);
        $arr['name']=$d['name'];
        $arr['mobile']=$d['mobile'];
        $arr['altmob']=$d['altmob'];
        $arr['type']=$d['type']; 
        $arr['address']=$d['addr']." ".$d['dist']." ".$d['state']." ".$d['pin'];
        $arr['photo']=$d['profile']==NULL?NULL:"https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['profile'];
        $arr['data']=array();
        $s1=mysqli_query($host,"select branch_category.iname,branch_category.name as bid,branch_category.id from route INNER JOIN branch_category ON branch_category.id=route.cid where route.driver='$username' group by route.cid");
        while($d1=mysqli_fetch_array($s1)){
          $bid=$d1['id'];
            $s112=mysqli_query($host,"select * from session where cid='$bid' order by id desc limit 1");
        $d123=mysqli_fetch_array($s112);
        $session=$d123['name'];
            $row['branchid']=$d1['id'];
            $row['branchname']=$d1['iname'];
            $row['branch']=$d1['bid'];
            $row['route']=array();
            $s2=mysqli_query($host,"select * from route  where driver='$username' and cid='$bid'");
            while($d2=mysqli_fetch_array($s2)){
            $name=$d2['name'];
            $row2['name']=$d2['name'];
            $row2['bname']='Bus - 14 Seater - Yellow '; 
            $row2['pickup']=$d2['starttime'];
            $row2['drop']=$d2['endtime'];
            $row2['vechile']="Vechile No. : ".$d2['vechile'];
            
           
            $s32=mysqli_query($host,"select id from students where route='$name' and cid='$bid' and status='Active' and session='$session'");
            $d322=mysqli_num_rows($s32); 
            
            $s3=mysqli_query($host,"select * from trip where cid='$bid' and route='$name' and date='$date'");
            $d3=mysqli_fetch_array($s3);
            $d311=0;
             if($d3['pickup_start']==null)
             {
                 $s3=mysqli_query($host,"select d2 from trip where route='$name' and cid='$bid' order by id desc LIMIT 1");
                 $d31=mysqli_fetch_array($s3);
                 $km=$d31[0];
                 
                 
             }else{
                   $s31=mysqli_query($host,"select count(students.id),sum(case when trip_students.type='pickup' and trip_students.pickup='1' then 1 else 0 end) from students inner join trip_students on students.id=trip_students.sid where students.route='$name' and trip_students.type='pickup' and trip_students.date='$date' and students.cid='$bid' and students.status='Active' and students.session='$session'");
                   $frm=mysqli_fetch_array($s31);
                   $picktime=date('h:i A',$d3['pickup_start']);
                   $d311=$frm[0];
                   $pickstudent=$frm[1]; if($pickstudent==""){ $pickstudent=0; }
                if($d3['pickup_end']==null){
                 
                  
             }else{
                if($d3['drop_start']==null){
                 
                 $s3=mysqli_query($host,"select p2 from trip where route='$name' and cid='$bid' order by id desc LIMIT 1");
                 $d31=mysqli_fetch_array($s3);
                 $km=$d31[0];
                 
             }else{
                 
                 $s31=mysqli_query($host,"select count(students.id),sum(case when trip_students.type='drop' and trip_students.pickup='1' then 1 else 0 end) from students inner join trip_students on students.id=trip_students.sid where students.route='$name' and trip_students.type='drop' and trip_students.date='$date' and students.cid='$bid' and students.status='Active' and students.session='$session'");
                 $frm=mysqli_fetch_array($s31);
                 $droptime=date('h:i A',$d3['drop_start']);
                 $d311=$frm[0];
                 $dropstudent=$frm[1]; if($dropstudent==""){ $dropstudent=0; }
             }   
             } 
             }
            
            
             $row2['km']=$km;
             $row2['kmpattern']='1';
             if($picktime)
             { 
             $row2['ptime']="Pickup : ".$picktime."  "."  -  "."  <b>".$pickstudent." | ".$d322."</b>";    
             }
             if($droptime)
             {
             $row2['dtime']="Drop : ".$droptime."  "."  -  "."  <b>".$dropstudent." | ".$d322."</b>";    
             }
            $row2['min']=$d311;
            $row2['total']=$d322;
            $row2['pickup_start']=$d3['pickup_start']==null?"0":"1";
            $row2['pickup_end']=$d3['pickup_end']==null?"0":"1"; 
            $row2['drop_start']=$d3['drop_start']==null?"0":"1";
            $row2['drop_end']=$d3['drop_end']==null?"0":"1";
             
            $row['route'][]=$row2;
            }

            $arr['data'][]=$row;
        }
     echo $json=json_encode($arr);    
    }else{
         $ar1['status']=false;
		 $ar1['message']='Token Did Not Match'."select * from apk_token where token='$token' and user='$username'";
		 echo $json=json_encode($ar1); 
    }
    
}else
{
    
    ?>
    <script>
        window.location="http://edpluss.com";        
    </script>
    <?php
}
?>