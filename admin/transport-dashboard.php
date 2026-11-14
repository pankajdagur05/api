<?php include("../../db.php");
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
$nid=$obj['nid'];
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
    $arr['message']="select sum(debit),sum(credit),sum(if(feesdue.rdate<='$date',credit,0)),sum(if(feesdue.rdate<='$date',debit,0)),students.name as name,students.fname as fname,students.standard as standard,students.stopage as stopage from students INNER JOIN feesdue ON feesdue.sid=students.id where students.cid='$user' and students.session='$session' and (students.status='Active' or students.status IS NULL) and feesdue.cancel='no' and students.route='$nid' group by feesdue.sid";
    $arr['data']=array();
     $arr['data2']=array();
    if($nid){
       
       $s=mysqli_query($host,"select * from route INNER JOIN driver ON driver.mobile=route.driver where route.name='$nid' and route.cid='$user' and driver.cid='$user'");
       $d=mysqli_fetch_array($s);
       $arr['dtype']=$d['type'];
       $arr['dname']=$d['name'];
       $arr['dmobile']=$d['mobile'];
       $arr['daddr']=$d['addr'];
       $arr['daadhar']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['aadhar'];
       $arr['ddl']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['dl'];
       $arr['dnda']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['nda'];
       
       $s=mysqli_query($host,"select * from route INNER JOIN vehicle ON vehicle.name=route.vechile where route.name='$nid' and route.cid='$user' and vehicle.cid='$user'");
       $d=mysqli_fetch_array($s);
       $arr['vnumber']=$d['name'];
       $arr['vname']=$d['vname'];
       $arr['vinsc']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['insc'];
       $arr['vphoto']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d['photo'];
      
     
      $s=mysqli_query($host,"select sum(debit),sum(credit),sum(if(feesdue.rdate<='$date',credit,0)),sum(if(feesdue.rdate<='$date',debit,0)),students.name as name,students.fname as fname,students.standard as standard,students.stopage as stopage from students INNER JOIN feesdue ON feesdue.sid=students.id where students.cid='$user' and students.session='$session' and (students.status='Active' or students.status IS NULL) and feesdue.cancel='no' and students.route='$nid' group by feesdue.sid");
        while($d=mysqli_fetch_array($s)){
            $row2['fees']=$d[0];
            $row2['deposit']=$d[1];
            $row2['overdue']=$d[3]-$d[2];
            $row2['pending']=$d[0]-$d[1];
            $row2['name']=$d['name'];
            $row2['fname']=$d['fname'];
            $row2['stopage']=$d['stopage'];
            $row2['standard']=$d['standard'];
            $arr['data2'][]=$row2;
        }
     
     
        
        
    }else{
        $s=mysqli_query($host,"select id from vehicle where cid='$user'");
        $arr['totalv']=mysqli_num_rows($s);
         $s=mysqli_query($host,"select id from driver where cid='$user' and status='Active'");
        $arr['totald']=mysqli_num_rows($s);
        $s=mysqli_query($host,"select sum(debit),sum(credit),sum(if(feesdue.rdate<='$date',credit,0)),sum(if(feesdue.rdate<='$date',debit,0)),count(students.id) from students INNER JOIN feesdue ON feesdue.sid=students.id where students.cid='$user' and students.session='$session' and (students.status='Active' or students.status IS NULL) and feesdue.cancel='no' and length(students.stopage)>0");
         $d=mysqli_fetch_array($s);
        $arr['totals']=$d[4];
        
        $arr['totalf']=$d[0];
        $arr['pending']=$d[3]-$d[2];
        $arr['receive']=$d[1];
        
        $s0=mysqli_query($host,"select * from route where cid='$user'");
        while($d0=mysqli_fetch_array($s0)){
            $route=$d0['name'];
            $row['route']=$d0['name'];
            $row['driver']=$d0['driver'];
            $row['vechile']=$d0['vechile'];
            $row['starttime']=$d0['starttime'];
            $row['endtime']=$d0['endtime']; 
            $s=mysqli_query($host,"select sum(debit),sum(credit),sum(if(feesdue.rdate<='$date',credit,0)),sum(if(feesdue.rdate<='$date',debit,0)) from students INNER JOIN feesdue ON feesdue.sid=students.id where students.cid='$user' and students.session='$session' and (students.status='Active' or students.status IS NULL) and feesdue.cancel='no' and students.route='$route'");
         $d=mysqli_fetch_array($s);
         $today=$d[4];
        $row['fees']=$d[0];
         $row['pending']=$d[0]-$d[1];
        $row['overdue']=($d[3]-$d[2])>0?($d[3]-$d[2]):0;
        $row['received']=$d[1];
        $row['today']=$d[4];
        $s=mysqli_query($host,"select count(trip_students.id) from students INNER JOIN trip_students ON trip_students.sid=students.id where students.cid='$user' and students.session='$session' and (students.status='Active' or students.status IS NULL) and trip_students.date='$date' and trip_students.pickup='1' and students.route='$route'");
         $d=mysqli_fetch_array($s);
         $row['present']=$d[0];
          $row['absent']=$today-$d[0];
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