<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
$noticolor=array("A"=>'#0c8a11','F'=>'#1b0c76fc','G'=>'#99750b','R'=>'#0f709c','T'=>'#6a0c7e','M'=>'#09766c');
$tim=time();
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $sid=$d3['id'];

 
$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
 
  $s=mysqli_query($host,"select * from notification where sub_domain='$subdomain' and type='staff' and sid='$sid' order by id desc limit 30");

  while($d=mysqli_fetch_array($s)){


$row['title']=$d['msg'];
$row['mode']=$d['mode'];
$row['date']=date('d M Y h:i A',$d['time']);
$row['time']=$d['time'];
$row['seen']=$d['seen'];
if($noticolor[$d['msg'][0]]){ $row['color']=$noticolor[$d['msg'][0]]; }else{ $row['color']='#105ea3'; }
$arr['data'][]=$row;

    
    
 }
 
  mysqli_query($host,"update notification set seen='$tim' where  sub_domain='$subdomain' and type='staff' and sid='$sid' order by id desc");


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