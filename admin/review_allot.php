<?php include("../../db.php");
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
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];

$username=$obj['userid'];
$token=$obj['token'];
$rid=$obj['rid'];
$standard=$obj['standard'];
$data=$obj['data'];
$staffidd=$obj['staffid'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
  $arr['status']=true;
    $arr['message']="Success";
     $arr['data']=array();
     $arr['data2']=array();
     $arr['data3']=array();
   
   $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffid=$d3['name'];

if(count($data)>0){
for($a=0; $a<count($data); $a++){
    $id=$data[$a]['id'];
    if(is_numeric($id)){
    $updatequery.="update review_allot set staffid='$staffidd' where id='$id'; \n";
     ++$countt;
    }else{
        if($id=='Staff List'){
            $s3=mysqli_query($host,"select id from review_allot where rid='$rid' and type='teacher'");
            while($d3=mysqli_fetch_array($s3)){
                $sid=$d3['id'];
                 $updatequery.="update review_allot set staffid='$staffidd' where id='$sid'; \n";
                 ++$countt;
            }
        }else{
           
            $s3=mysqli_query($host,"select review_allot.id from review_allot INNER JOIN students ON students.id=review_allot.allot_id where review_allot.rid='$rid' and review_allot.type<>'teacher' and students.standard='$id'");
            while($d3=mysqli_fetch_array($s3)){
                $sid=$d3[0];
                 $updatequery.="update review_allot set staffid='$staffidd' where id='$sid'; \n";
                  ++$countt;
            }
         
            
            
        }
    }
} 

if($updatequery){
mysqli_multi_query($host,$updatequery);
}
 
 $s23=mysqli_query($host,"select device from staff where id='$staffid'");
      $d23=mysqli_fetch_array($s23);
$token=array();
 $token[0]=$d23['device'];
  $sid=$d23['id'];

$s11=mysqli_query($host,"select * from global where name='Teacher Key'");
$d11=mysqli_fetch_array($s11);
$tkey=$d11['val'];
$title="Review Notification";
$descr=$countt." Review Form Given To You ";
    send_notification($title,$descr,$token,$tkey,"complaint");
    mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='staff'");
}      
      

if($standard=='Staff List'){      
$s=mysqli_query($host,"select staff.name,staff.gname,review_allot.staffid,staff.staff_id,review_allot.id,staff.id from review_allot INNER JOIN staff ON staff.id=review_allot.allot_id where rid='$rid' and type='teacher' and cid='$branch'");
while($d=mysqli_fetch_array($s)){ 
$sid=$d[5];    
$row['name']=$d[0];
$row['gname']=$d[1];
$row['sr']=$d[3];
$row['rid']=$d[4];
$row['id']=$d[5];
if($d[2]==NULL){
$row['aname']="";    
$arr['data'][]=$row;      
}else{
$ssid=$d[2];
$ssss=mysqli_query($host,"select name from staff where id='$ssid'");
$dddd=mysqli_fetch_array($ssss);
$row['aname']=$dddd[0];
$ss=mysqli_query($host,"select id from review_submit where review_id='$rid' and student_id='$sid'");    
if(mysqli_num_rows($ss)==0){
$arr['data2'][]=$row;
}else{
$arr['data3'][]=$row;    
}
}
  
}
}else{

$s=mysqli_query($host,"select students.name,students.fname,review_allot.staffid,students.stu_id,review_allot.id,students.id from review_allot INNER JOIN students ON students.id=review_allot.allot_id where review_allot.rid='$rid' and review_allot.type<>'teacher' and review_allot.cid='$branch' and students.standard='$standard'");
while($d=mysqli_fetch_array($s)){ 
    $sid=$d[5];
$row['sr']=$d[3]; 
$row['rid']=$d[4]; 
$row['id']=$d[5]; 
$row['name']=$d[0];
$row['gname']=$d[1];
if($d[2]==NULL){
$row['aname']="";    
$arr['data'][]=$row;      
}else{
$ssid=$d[2];
$ssss=mysqli_query($host,"select name from staff where id='$ssid'");
$dddd=mysqli_fetch_array($ssss);
$row['aname']=$dddd[0];
$row['score']="";    
$ss=mysqli_query($host,"select id,score from review_submit where review_id='$rid' and student_id='$sid'");    
if(mysqli_num_rows($ss)==0){ $row['score']=""; 
$arr['data2'][]=$row; 
}else{ 
    
$d011=mysqli_fetch_array($ss);  
$row['score']=$d011['score']; 
$arr['data3'][]=$row;     
}
}
  
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