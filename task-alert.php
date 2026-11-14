<?php

function send_notification($title,$desc,$id,$sub,$h,$c)
{
$s=mysqli_query($h,"select * from admin_login where sub_domain='$sub'");  
$d=mysqli_fetch_array($s);
$adminkey=$d['app_id_admin'];
$teacherkey=$d['app_id_teacher'];
$admindevice=$d['device'];

$q="select * from taskmember INNER JOIN staff ON staff.id=taskmember.sid where taskmember.tid='$id' and length(staff.device)>1";
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
            
            if($c==2){
             
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
        
      
         
    $fields = array
            (
                'to'  => $admindevice,
                'notification'  => $msg
            );
    
    
    $headers = array
            (
                'Authorization: key=' . $adminkey,
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


?>