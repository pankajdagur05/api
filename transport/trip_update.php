<?php error_reporting(4);
include("../../db.php");

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
$json = file_get_contents('php://input');
$obj= json_decode($json,true); 
$username=$obj['userid']; 
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
if($obj['time']){
$tim=strtotime($date.' '.$obj['time']);
}else{
    $tim=time();
   
}
 $time=date('h:i:s a');
$route=$obj['route'];
$bid=$obj['branch'];
$remark=$obj['remark'];
$km=$obj['km'];
if($username){   
  
      
     $s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
        $s9=mysqli_query($host,"select name from staff where mobile='$username' and branch='$bid'");
        $d9=mysqli_fetch_array($s9);
        $sname=$d9['name'];
        
          $s6=mysqli_query($host,"select * from session where cid='$bid' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
        
        $s8=mysqli_query($host,"select * from route where name='$route' and cid='$bid'");
        $d8=mysqli_fetch_array($s8);
        $v=$d8['vechile'];
        
       $s=mysqli_query($host,"select * from trip where route='$route' and cid='$bid' and date='$date'"); 
       if(mysqli_num_rows($s)==1){
         $d=mysqli_fetch_array($s);
         $id=$d['id'];
         if($d['pickup_end']==NULL){ 
              $desc="Pickup End";
            mysqli_query($host,"update trip set remark='$remark',p2='$km',pickup_end='$tim' where id='$id' and cid='$bid'"); 
         }else{
             
               if($d['drop_start']==NULL){
                   $desc="Trip Started to Drop";
                     mysqli_query($host,"update trip set remark='$remark',d1='$km',drop_start='$tim',drop_by='$sname',drop_user='$username',d_vehicle='$v' where id='$id' and cid='$bid'"); 
               }else{
                    $desc="Drop End";
                  mysqli_query($host,"update trip set remark='$remark',d2='$km',drop_end='$tim' where id='$id' and cid='$bid'"); 
               }
             
         }
       }else{
           $desc="Trip Started to pickup you";
           mysqli_query($host,"insert into trip set remark='$remark',p1='$km',route='$route',date='$date',pickup_start='$tim',pickup_by='$sname',pickup_user='$username',cid='$bid',p_vehicle='$v'");
       }
       

if($desc){       
       
        
$title="Transport Notification";

$token=array();
$x=0;
$s90=mysqli_query($host,"select id,p_device,s_device from students where cid='$user' and route='$route' and (length(s_device)>0 or length(p_device)>0) and (status='active' or status IS NULL) and session='$session'");
while($d90=mysqli_fetch_array($s90)){
    $sid=$d90['id'];
if($d90['s_device']){
$token[$x]=$d90['s_device'];
$x++;
mysqli_query($host,"insert into notification set msg='$desc',mode='$title',date='$today',time='$time',sid='$sid',sub_domain='$subdomain',type='student'");
}
if($d90['p_device']){
$token[$x]=$d90['p_device'];
$x++;    
mysqli_query($host,"insert into notification set msg='$desc',mode='$title',date='$today',time='$time',sid='$sid',sub_domain='$subdomain',type='parent'");
}
}

$token=array_unique($token);

$s=mysqli_query($host,"select * from global where name='Student Key'");
$d=mysqli_fetch_array($s);
$key=$d['val'];

send_notification($title,$desc,$token,$key,$typed);
}
         $ar1['status']=true;
		 $ar1['message']='Success';
		 echo $json=json_encode($ar1); 
        
    }else{
         $ar1['status']=false;
		 $ar1['message']='Token Did Not Match';
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