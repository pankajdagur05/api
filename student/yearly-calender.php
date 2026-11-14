<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type=$_POST['type'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    $arr['data2']=array();
    
    $s0=mysqli_query($host,"select cid,standard,batch from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0[0];
    $class=$d0['standard'];

$s=mysqli_query($host,"select * from calender where cid='$user' and tdate>='$date' order by fdate,tdate asc");
while($d=mysqli_fetch_array($s)){ 
$cal=$d['id'];    
$s0=mysqli_query($host,"select * from calender_attachment where cid='$user' and cal='$cal'");
if(mysqli_num_rows($s0)==0){ $xx=1;  }else{  $s0=mysqli_query($host,"select * from calender_attachment where cid='$user' and standard='$class' and cal='$cal'");  if(mysqli_num_rows($s0)==0){ $xx=0;  }else{ $xx=1; } }
if($xx==1){
$now = time(); // or your date as well
$your_date = strtotime($d['fdate']);
$datediff = $your_date-$now;
$days=round($datediff / (60 * 60 * 24));

if($d['tdate']){
 $now = strtotime($d['tdate']);
$your_date = strtotime($d['fdate']);
$datediff = $now - $your_date;
$gap=round($datediff / (60 * 60 * 24));   
}else{
  $gap="1";  
}




if($gap==0){ $mg="Today"; $color="#1ab69d"; }else{ if($gap==1){  $mg="Tommarow"; $color="#4d90fe";   }else{ if($gap<0){ $msg=""; }else{ if(date('Y-m-d')>=$d['fdate'] and date('Y-m-d')<=$d['tdate']){   $mg="On Going";  $color="#1ab69d";   }else{ $mg=($days+1)." Days Remaining";  $color="black";  } } }}      
if($gap=="1"){
$gap.=" Day";    
}else{
$gap.=" Days";     
}
$row['subject']=$d['title'];
$row['descr']=$d['descr'];
$row['date']=date('d M Y',strtotime($d['date']));
$row['fdate']=date('d M Y',strtotime($d['fdate']));
$row['remaining']="<span style='color:".$color."'>".$mg."<span>";
$row['ffdate']=$d['fdate'];
$row['ttdate']=$d['tdate'];
$row['tdate']=$d['tdate']==$d['fdate']?"":date('d M Y',strtotime($d['tdate'])); 
$row['type']=$d['mode'];
$row['id']=$d['id'];
$row['tag']="Upcoming";
$row['gap']=$gap;
$arr['data'][]=$row;
}
}    


$s=mysqli_query($host,"select * from calender where cid='$user' and tdate<'$date' order by fdate,tdate asc limit 15");
while($d=mysqli_fetch_array($s)){ 

$cal=$d['id'];    
$s0=mysqli_query($host,"select * from calender_attachment where cid='$user' and cal='$cal'");
if(mysqli_num_rows($s0)==0){ $xx=1;  }else{  $s0=mysqli_query($host,"select * from calender_attachment where cid='$user' and standard='$class' and cal='$cal'");  if(mysqli_num_rows($s0)==0){ $xx=0;  }else{ $xx=1; } }
if($xx==1){    
    
$now = time(); // or your date as well
$your_date = strtotime($d['fdate']);
$datediff = $your_date-$now;
$days=round($datediff / (60 * 60 * 24));

if($d['tdate']){
 $now = strtotime($d['tdate']);
$your_date = strtotime($d['fdate']);
$datediff = $now - $your_date;
$gap=round($datediff / (60 * 60 * 24))+1;   
}else{
  $gap="1";  
}

$mg=$days." Days Ago";  $color="black";     
if($days=="1"){
$days.=" Day";    
}else{
$days.=" Days";     
}
$row['subject']=$d['title'];
$row['descr']=$d['descr'];
$row['date']=date('d M Y',strtotime($d['date']));
$row['fdate']=date('d M Y',strtotime($d['fdate']));
$row['remaining']="<span style='color:".$color."'>".$mg."<span>";
$row['ffdate']=$d['fdate'];
$row['ttdate']=$d['tdate'];
$row['tdate']=$d['tdate']==$d['fdate']?"":date('d M Y',strtotime($d['tdate'])); 
$row['type']=$d['mode'];
$row['id']=$d['id'];
$row['tag']="Past";
$row['gap']=$days;
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