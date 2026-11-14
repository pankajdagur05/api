<?php
include("../db.php"); 
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$type=$obj['type']; 
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $user=$d3['branch'];
      $staffid=$d3['name'];
   
$arr['status']=true;
$arr['message']="success";
$arr['data']=array();


$s=mysqli_query($host,"select * from calender where cid='$user' and tdate>='$date' order by tdate desc");
while($d=mysqli_fetch_array($s)){ 
$now = time(); // or your date as well
$your_date = strtotime($d['fdate']);
$datediff = $your_date-$now;
$days=round($datediff / (60 * 60 * 24));

if($d['tdate']){
 $now = strtotime($d['tdate']);
$your_date = strtotime($d['fdate']);
$datediff = $now - $your_date;
$gap=ceil($datediff / (60 * 60 * 24));   
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


$s=mysqli_query($host,"select * from calender where cid='$user' and tdate<'$date' order by tdate desc limit 15");
while($d=mysqli_fetch_array($s)){ 
$now = time(); // or your date as well
$your_date = strtotime($d['fdate']);
$datediff = $your_date-$now;
$days=round($datediff / (60 * 60 * 24));

if($d['tdate']){
 $now = time();
$your_date = strtotime($d['fdate']);
$datediff = $now - $your_date;
$gap=round($datediff / (60 * 60 * 24));   
}else{
  $gap="1";  
}




if($gap==0){ $mg="Today"; $color="#1ab69d"; }else{ if($gap==1){  $mg="Tommarow"; $color="#4d90fe";   }else{ if($gap<0){  $msg=""; }else{ $mg=$days." Days Ago";  $color="red"; } }}       
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
$row['tag']="Past";
$row['gap']=$gap;
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