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
$dates=$obj['date'];
$date=date('Y-m-d');
if($dates==""){ $dates=$date; }
$staff=$obj['staff'];

$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
$arr['status']=true;
$arr['message']='success';

if($sid)
{
    
    if(strtolower($type)=='admin'){ $ssname='admin'; }else{  
        $s=mysqli_query($host,"Select name from staff where id='$userid' and branch='$user'");
        $d=mysqli_fetch_array($s);
        $ssname=$d['name'];
    }


$ss12=mysqli_query($host,"select status from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$dates' and status='A'"); 
if(mysqli_num_rows($ss12)>0){
   mysqli_query($host,"delete from staff_attendance where sid='$sid' and status='A' and idate='$dates' and subdomain='$branch'");       
}

$ss=mysqli_query($host,"select * from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$dates'"); 
if(mysqli_num_rows($ss)>0){
mysqli_query($host,"update staff_attendance set time=concat(time,',$ttime'),uploadby=concat(uploadby,',','$ssname') where sid='$sid' and subdomain='$branch' and idate='$dates'");    
}else{
mysqli_query($host,"insert into staff_attendance set sid='$sid',status='P',idate='$dates',date='$date',time='$ttime',uploadby='$ssname',subdomain='$branch'");    
}
}

if($staff){
 mysqli_query($host,"insert into staff_attendance set sid='$staff',status='A',idate='$dates',date='$date',time='$time',uploadby='$ssname',subdomain='$branch'");       
}

$arr['data']=array();
$q="select id,name,post from staff where branch='$user' and status='Active' order by name asc";
$s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){ 
$sid=$d['id'];
$row['sid']=$sid;
 $ss=mysqli_query($host,"select time from staff_attendance where subdomain='$branch' and sid='$sid' and idate='$dates'");
   $dd=mysqli_fetch_array($ss);
  $ss9=mysqli_query($host,"select status from staff_attendance where subdomain='$branch' and sid='$sid' and idate='$dates' and status='A'");
  $dd9=mysqli_fetch_array($ss9);
       if(mysqli_num_rows($ss)==1){
       
        if(count(explode(",",$dd['time']))%2==0){
            $row['myattend']=0; 
        }else{
         $row['myattend']=1;   
        
        }
       }else{
          $row['myattend']=0;  
       }
      if($dd[0]==""){   $row['status']="Not Punched"; }else{ $row['status']="Punched";  } if($dd9>0){ $row['status']="Absent";   }

       $check=explode(",",$dd['time']);
       $checkin="";
       $checkout="";
       if(count($check)==1)
       {    
         $checkin=$check[0];  
       }
       else
       { 
       if(count($check)>1)
       {    
            $checkin=$check[count($check)-2];
            $checkout=$check[count($check)-1];
       }
       }
       
       
$row['name']=$d['name'];
$row['post']=$d['post'];
$row['checkin']=$checkin;
$row['checkout']=$checkout;
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