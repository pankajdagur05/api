<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$username=$obj['username'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$id=$obj['id'];
$sid=$obj['sid'];
$num=$obj['num'];
$data=$obj['data'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
function send_notification($key,$tok,$title,$desc,$cid,$not)
{

#prep the bundle
     $msg = array
          (
        'body'  => $desc,
        'title' => $title,
        'click_action' => $not,

          );
          
            $data = array
          (
        'type' =>'lead',
         'id'=>$cid,
                
          );
    $fields = array
            (
                'to'        => $tok,
                'notification'  => $msg,
                'data'  => $data,
                
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
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

    
       if($d3['calling']==1){
           $access=$username;
       }
 $s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];

$s4=mysqli_query($host,"select * from staff where id='$sid'");
$d4=mysqli_fetch_array($s4); 
$s2=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");  
$d2=mysqli_fetch_array($s2);
 
$device=$d4['device'];
$key=$d2['app_id_teacher'];
$not='calling';


 
for($a=0; $a<count($data); $a++){
    
$stu=$data[$a]['id'];
if(is_numeric($stu)){
if($num==1){
$q="insert into calling_students set stu_id='$stu',staff_id='$sid',rid='$id',date='$date',cid='$user'";
}else{

$q="update calling_students set staff_id='$sid',date='$date'  where stu_id='$stu' and rid='$id' and cid='$user'";    
}
mysqli_query($host,$q);
}else{
   
 
 
   
    
}

}
$arr['status']=true;
$arr['message']="success";    
$title="You Have Allotmed ".count($data)." Calls";
send_notification($key,$device,'Calling Allotment',$title,$d4['id'],$not);  
 
}
else{
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