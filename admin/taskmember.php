<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');

 
function send_notification($title,$desc,$id,$sub,$h,$staff)
{
$s=mysqli_query($h,"select * from admin_login where sub_domain='$sub'");  
$d=mysqli_fetch_array($s);
$adminkey=$d['app_id_admin'];
$teacherkey=$d['app_id_teacher'];
$admindevice=$d['device'];

$q="select * from staff where length(device)>1 and id='$staff'";
$s1=mysqli_query($h,$q);
$token=array();
$x=0;
while($d1=mysqli_fetch_array($s1)){
$token[$x] = $d1['device'];
$sid=$d1['sid'];
++$x;
mysqli_query($host,"insert into notification set msg='$desc',mode='$title',date='$date',time='$time',sid='$sid',sub_domain='$sub',type='staff'");
}
$tokan.="abc";
#prep the bundle
     $msg = array
          (
        'body'  => $desc,
        'title' => $title,
        'click_action' => 'notification',
                
          );
          
         
    $fields = array
            (
                'registration_ids'  => $token,
                'notification'  => $msg
            );
    
    
    $headers = array
            (
                'Authorization: key=' . $teacherkey,
                'Content-Type: application/json'
            );
            
           
             
#Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        
            
        
       
}

 
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

$sid=$obj['sid'];
$ctype=$obj['ctype'];
$tid=$obj['tid'];
 $delete=$obj['delete'];
if($delete){
mysqli_query($host,"delete from taskmember where sid='$sid' and tid='$delete' and user='$user'");
$arr['status']=true;
$arr['message']='Sucessfully Delete';
echo $json = json_encode($arr);
return;
}


if($ctype){
$rr=mysqli_query($host,"select * from taskmember where sid='$sid' and tid='$tid' and user='$user'");

if(mysqli_num_rows($rr)>0)
{
    $arr['status']=false;
    $arr['message']='Member Already Exist'; 
    
echo $json = json_encode($arr);
      return;
}
else
{
$s0=mysqli_query($host,"select * from task where id='$tid'");   
$d0=mysqli_fetch_array($s0);
$tname=$d['title'];
$q="insert into taskmember set sid='$sid',tid='$tid',type='$ctype',live='1',date='$date',time='$time',user='$user'";    
mysqli_query($host,$q);
send_notification("Task","Added In $tname",$tid,$subdomain,$host,$sid);
} 
}


$arr['status']=true;
$arr['message']='success';

$arr['data']=array(); 
$s1=mysqli_query($host,"select * from taskmember INNER JOIN staff ON staff.id=taskmember.sid where taskmember.user='$user' and taskmember.tid='$tid' order by taskmember.id desc");
while($d1=mysqli_fetch_array($s1)){
$row2['name']=$d1['name'];  
$row2['sid']=$d1['sid'];  
$row2['tid']=$d1['tid'];  
$row2['type']=$d1['type']; 
$arr['data'][]=$row2;
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