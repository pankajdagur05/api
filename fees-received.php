<?php
include("../db.php");

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
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$invoice=$obj['invoice'];
$idate=$obj['date'];
$remark=$obj['remark'];
$sid=$obj['sid'];
$sms=$obj['sms'];
$num=count($data);
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $userby=$d3['name'];
      
 

for($a=0; $a<$num; $a++){

$entry=$data[$a]['entry'];
$mode=$data[$a]['mode'];
$amount=$data[$a]['amount'];
$gamount+=$amount;
if(substr($mode,0,4)=='Bank' or substr($mode,0,4)=='bank'){
$bdetails=ltrim($mode,"Bank : "); 
$pos1=strpos($mode,'-');
$str=substr($mode,$pos1+2,300);
$pos2=strpos($str,'-');
$baccount=substr($str,0,$pos2-1);
$mode='Bank'; 
}
$s1=mysqli_query($host,"select * from feesdue where id='$entry' and cid='$branch'");
$d1=mysqli_fetch_array($s1);
$cateid=$d1['cateid'];
$session=$d1['session'];
if(mysqli_query($host,"insert into feesdue set sid='$sid',cateid='$cateid',credit='$amount',voucher='receive',cancel='No',remark='$remark',type='$entry',rdate='$idate',invoice='$invoice',cid='$branch',userby='$userby',bdetails='$bdetails',bankno='$baccount',date='$date',mode='$mode',session='$session'")){
    
}else{
    ++$x;
}
 
    
}


if($x>0){
  $arr3['status']=false;
$arr3['message']='Something Went Wrong';   
} else{
    if($sms){
        
        $s=mysqli_query($host,"select s_device,p_device,name from students where id='$sid'");
        $d90=mysqli_fetch_array($s);
        
        
$desc="Dear ".$d90['name']." Your Fees INR ".$gamount."/- Deposited";
$title="Fees Deposit";


$token=array();
$x=0;
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
$token=array_unique($token);
$s=mysqli_query($host,"select * from global where name='Student Key'");
$d=mysqli_fetch_array($s);
$key=$d['val'];
send_notification($title,$desc,$token,$key,'fees');
    }
  $arr3['status']=true;
$arr3['message']='Succesfully Submit';   
}



}else{
    $arr3['status']=false;
    $arr3['message']='Token Not Match';
    $arr3['data']='';
}

echo $json = json_encode($arr3);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>