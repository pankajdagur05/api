<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$tim=time();
function send_notification($title,$desc,$token,$key,$m)
{

#prep the bundle
     $msg = array
          (
        'body'  => $desc,
        'title' => $title,
        'click_action' => $m,
                
          );
          
         
    $fields = array
            (
                'registration_ids'  => $token,
                'notification'  => $msg
            );
    
    
    $headers = array
            (
                'Authorization: key=' . $key,
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
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    
     
    $r=mysqli_num_rows($s);
    
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
 
$fdate=$obj['fdate'];
$tdate=$obj['tdate'];
$subject=$obj['title'];
$remark=$obj['title'].' : '.$obj['remark'];



$time=time('h:i:s');

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $user=$d3['id'];
      $staffname=$d3['name'];
 $started=strtotime($fdate);
 $ended=strtotime($tdate);
 if($tdate){
                    $key=",todate='$tdate'";
                } 
        
$q="insert into staff_leaveapplication set sid='$user',subject='$subject',description='$remark',fromdate='$fdate'$key,cid='$branch',status='Pending',date='$date'";
mysqli_query($host,$q);


 $token=array();
         $s23=mysqli_query($host,"select staff.admin_device as admin_device,staff.id as id from staff INNER JOIN setting ON setting.appeal_respo=staff.id where length(staff.admin_device)>0 and setting.cid='$user'");
if(mysqli_num_rows($s23)>0){
    
$d23=mysqli_fetch_array($s23);
  $token[0]=$d23['admin_device'];
  $sid=$d23['id'];

$s=mysqli_query($host,"select * from global where name='Admin Key'");
$d=mysqli_fetch_array($s);
$tkey=$d['val'];
$title="Leave Application  by ".$staffname;
$descr="Please Go To Web Panel Give Response";
    send_notification($title,$descr,$token,$tkey,"notification");
    mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='staff'");
       mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");
}




 
 
$arr['status']=true;
$arr['message']='success';


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