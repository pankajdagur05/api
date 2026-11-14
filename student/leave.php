<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$subject=$_POST['subject'];
$desc=$_POST['descr'];
$token=$_POST['token'];
$type=$_POST['type'];
$reason=$_POST['reason'];
$fdate=$_POST['fdate'];
$tdate=$_POST['tdate'];
$force=$_POST['force'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
         $s0=mysqli_query($host,"select * from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0['cid'];
    $sid=$d0['id'];
    if($type=='parent'){  if($d0['gmobile']){ $mobile=$d0['gmobile'];  }else{ $mobile=$d0['smobile'];   } $name=$d0['fname']; }else{ $mobile=$d0['smobile']; $name=$d0['name'];  }
    
    
        if($subject){ 
            
$startTime=strtotime($fdate);
if($tdate){
$endTime=strtotime($tdate);
for($i=$startTime; $i<=$endTime; $i = $i + 86400 ){
  $thisDate=date( 'Y-m-d', $i );
  $qr.=" (fdate<='$thisDate' and tdate>='$thisDate')";
  if($i==$endtime){
      
  }else{
      $qr.=" or ";
  }
}
$qr.=" (1=2)";
}else{
$endTime=$startTime; 
$qr.=" (fdate<='$fdate' and tdate>='$fdate')";
}


// Loop between timestamps, 24 hours at a time

 
            
            
            $s0=mysqli_query($host,"select * from calender where cid='$user' and mode<>'holiday' and ($qr) order by fdate desc limit 1");
            if(mysqli_num_rows($s0)>0 and $force==""){
            $d0=mysqli_fetch_array($s0);
            $arr['warning_title']=$d0['title'];
            $sub="The ".$d0['mode']." of <b>".$d0['title']."</b> ";
            if($d0['tdate']==$d0['fdate']){
                 $sub.=" is on ".date('d M Y',strtotime($d0['fdate']))."</b>. <br/> Are you sure do you want to leave ?";  
            }else{
                $sub.=" is From <b>".date('d M Y',strtotime($d0['fdate']))."</b> to <b>".date('d M Y',strtotime($d0['tdate']))."</b>. <br/> Are you sure do you want to leave ?";
                
            }
            $arr['warning_descr']=$sub;
            $force=1;
            }else{
                $arr['warning_title']="";
                $arr['warning_descr']="";
            }
            if($force=="" or $force=="0"){
                if($tdate){
                    $key=",todate='$tdate'";
                } 
                
          $q="insert into leave_application set sid='$sid',reason='$reason',subject='$subject',description='$desc',fromdate='$fdate'$key,type='$type',cid='$user',status='Pending',date='$date'";
            mysqli_query($host,$q);
            }
        
        }
        
    $arr['status']=true;
    $arr['message']="success";
    $arr['data']=array();
    
   
    
$s=mysqli_query($host,"select * from leave_application where sid='$sid' order by id desc limit 4"); while($d=mysqli_fetch_array($s)){ 

array_push($dpush,$d['fromdate']);     
if($d['todate']){$tdate=date("d M Y", strtotime($d['todate'])); }
$row16['date']=date("d/m/Y", strtotime($d['date'])); 
$row16['subject']=$d['subject'];

$row16['descr']=$d['description'];  
$row16['type']=$d['type'];
$row16['status']=$d['status'];
$row16['reason']=$d['reason'];
$row16['remark']=$d['remark'];
$row16['fdate']=date("d M Y", strtotime($d['fromdate']));
$row16['tdate']=$tdate;
$arr['data'][]=$row16;
       
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