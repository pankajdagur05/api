<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
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

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
       $branch=$d3['branch'];
       $user=$branch;
       $userby=$d3['name'];
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
$insert.=" ( '$stu','$sid','$id','$date','$user' ) ,";
}else{
mysqli_query($host,"update calling_students set staff_id='$sid',date='$date'  where stu_id='$stu' and rid='$id' and cid='$user'");    
}




}else{
 

 $s0=mysqli_query($host,"select type from calling where id='$id'");
 $d0=mysqli_fetch_array($s0);
 if(strtolower($d0['type'])=='finance'){
    $coon=" and (students.rte='No' or students.rte IS NULL or students.rte='')";
}else{
     $coon=" and (students.status='Active' or students.status=NULL or students.status='')";
}

  
$q1="select students.id as id ,students.name as name,students.standard as standard, students.fname as fname,students.stream as stream,students.smobile as smobile from students where students.cid='$user' and students.session='$session' $coon ";
$q1.=" and students.standard='$stu' order by students.name asc";
 
$ss=mysqli_query($host,$q1); 
while($dd=mysqli_fetch_array($ss)){
     $ob=0; $out=0;
     $xx=0;
     $stu=$dd['id']; 
     $ssid=$stu;
     if($d0['type']=='Exam'){
     $obb=0;
     $outb=0;
         $row11=array();
           
for($r=0; $r<count($exam); $r++){
    $exa=$exam[$r];
    $row11['name']=$exam[$r];
    $row11['marks']=array();
    $ob=0;
     $out=0;
      $row12=array();
      $qq="select subject,outoff,obtain from uploadresult where sid='$stu' and exam='$exa'";
    $ss1=mysqli_query($host,$qq);
    while($dd1=mysqli_fetch_array($ss1)){
        $ob+=$dd1['obtain'];
          $out+=$dd1['outoff'];
        $row12['subject']=$dd1['subject'];
        $row12['outoff']=is_numeric($dd1['outoff'])?$dd1['outoff']:0;
        $row12['obtain']=is_numeric($dd1['obtain'])?$dd1['obtain']:0;
        $row11['marks'][]=$row12;
        $obb+=$dd1['obtain'];
        $outb+=$dd1['outoff'];
    }
    

$row11['ob']=$ob;
    $row11['out']=$out;
    $row['exam'][]=$row11;
    
    
}
    if($d0['eper']){
      $per=$d0['eper'];
    if((($obb*100)/$outb)>$per){
     $xx=1;    
    }
    }
         
      }
      
      if($d0['type']=='Attendance'){
         
           $row11=array();
          $q="select SUM(CASE WHEN status='P' THEN 1 ELSE 0 END) as p,SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as a from attendance where sid='$stu' ";
          if($d0['afdate']){
              $fdate=$d0['afdate'];
              $tdate=$d0['atdate'];
              $q.=" and idate BETWEEN '$fdate' AND '$tdate'";
          }
           $sss=mysqli_query($host,$q);
           $ddd=mysqli_fetch_array($sss);
              
           $row11['ob']=$ddd[0];
            $row11['out']=$ddd[0]+$ddd[1];
            $row['attendance'][]=$row11;
            
             if($d0['aper']){
    $per=$d0['aper'];
    if((($ob*100)/$out)>$per){
    $xx=1;    
    }
    }
            
      }
      
      if($d0['type']=='Finance'){
        $q="select sum(credit),sum(debit) from feesdue where cid='$user' and sid='$ssid' and cancel='No'";
        if($d0['fodate']){
            $ff=explode(",",$d0['fodate']);
         $fdate=str_replace(' ', '', $ff[0]);
        $q.=" and rdate<='$fdate'";
        }else{
      $q.=" and rdate<='$date'";
        }
        
         $sss=mysqli_query($host,$q);
         $ddd=mysqli_fetch_array($sss);
         
          $row11['ob']=$ddd[0];
          $row11['out']=$ddd[1];
          if($ddd[0]>=$ddd[1]){
              $xx=1;
          }
     
          $row['finance'][]=$row11;
      }
      
    
   
     if($d0['type']=='Review'){
          
          $ob="4";
          $out="10";
          $row11['ob']=$ob;
          $row11['out']=$out;
    if($ob>=$out){
    $xx=1;    
    }
          
          $row['review'][]=$row11;
      }
     
      if($xx==1){
          
      }else{
          
$s000=mysqli_query($host,"select id from calling_students where stu_id='$stu' and rid='$id' and cid='$user'");  
$num=mysqli_num_rows($s000);
if($num==0)
{
$insert.=" ( '$stu','$sid','$id','$date','$user' ) ,";
}
else
{
mysqli_query($host,"update calling_students set staff_id='$sid',date='$date' where stu_id='$stu' and rid='$id' and cid='$user'");    
}

   
      }
      
      
      
      
}  
   
 
 
   
    
}

}

if($insert){
     $intl=" INSERT INTO calling_students (stu_id, staff_id, rid, date, cid) VALUES";
    $intl.=$insert;
    $intl=rtrim($intl,",");
mysqli_query($host,$intl);
}
$arr['status']=true;    
$arr['message']="Successfully Alloted".$intl;
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