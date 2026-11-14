<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$title=$obj['title'];
$status=$obj['status'];
$extend_date=$obj['extend_date'];
$extend_cid=$obj['extend_cid'];
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
       $access=$username;
       
 $s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];


$tid=$obj['update'];
$delete=$obj['delete'];
if($title){
$type=$obj['type']; 
$review=$obj['review']; 
$standard=explode(",",$obj['standard']); 
$fodate=$obj['fodate']==""?NULL:$obj['fodate']; 
$exam=explode(",",$obj['exam']); 
$eper=$obj['eper']; 
$afdate=$obj['afdate']==""?NULL:$obj['afdate']; 
$atdate=$obj['atdate']==""?NULL:$obj['atdate']; 
$aper=$obj['aper']; 
$rper=$obj['rper']; 
$remark=$obj['remark']; 
$ftype=$obj['ftype'];
$fdate=$obj['fdate']==""?NULL:$obj['fdate']; 
$ftdate=$obj['ftdate']==""?NULL:$obj['ftdate']; 
$respo=$obj['respo']; 
$condi=explode(",",$obj['condi']); 
$q="insert into calling set title='$title',type='$type',review='$review',fodate='$fodate',eper='$eper',afdate='$afdate',atdate='$atdate',aper='$aper',userid='$username',remark='$remark',ftype='$ftype',fdate='$fdate',ftdate='$ftdate',respo='$respo',date='$date',time='$time',status='Active',userby='$userby',cid='$user',dele='0'";
if(mysqli_query($host,$q)){


$s4=mysqli_query($host,"select * from staff where id='$respo'");
$d4=mysqli_fetch_array($s4); 
$s2=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");  
$d2=mysqli_fetch_array($s2);
 
$device=$d4['device'];
$key=$d2['app_id_teacher'];
$not='calling';

$title2="Give You Responsbility for Calling as Manager By ".$userby;
send_notification($key,$device,'Calling Manager',$title2,$d4['id'],$not);      
    
 $ss=mysqli_query($host,"select id from calling where cid='$user' order by id desc limit 1");  
 $dd=mysqli_fetch_array($ss);
 $cid=$dd['id'];
 for($a=0; $a<count($exam); $a++){
   $val=$exam[$a];
    if($val){
   mysqli_query($host,"insert into calling_attachment set type='exam',val='$val',calling='$cid',cid='$user'");
    }
 }
 
  for($a=0; $a<count($condi); $a++){
   $val=$condi[$a];
   if($val){
   mysqli_query($host,"insert into calling_attachment set type='condi',val='$val',calling='$cid',cid='$user'");
   }
 }
 
  for($a=0; $a<count($standard); $a++){
   $val=$standard[$a];
   $val2=explode("_",$val);
   $val=$val2[1];
    if($val){
   $qq="insert into calling_attachment set type='standard',val='$val',calling='$cid',cid='$user'";
   mysqli_query($host,$qq);
    }
 }
$arr['status']=true;
$arr['message']='Successfully Inserted';   
 
}else{
$arr['status']=false;
$arr['message']='Something Went Wrong';     
}    
  
    
  
}else{

if($delete){
// mysqli_query($host,"update calling set dele='1' where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
  
  
  if($status){
$ss=mysqli_query($host,"select * from calling where id='$status' and cid='$user'"); 
$dd=mysqli_fetch_array($ss);
if($dd['status']=='Active'){
$st='Inactive';    
}else{
    $st='Active';
}
  $q="update calling set status='$st' where id='$status' and cid='$user'";
mysqli_query($host,$q); 
$arr['status']=true;
$arr['message']=$st;
}else{
   if($extend_date){
mysqli_query($host,"update calling set ftdate='$extend_date' where id='$extend_cid' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully Extend Date';
}else{ 
$arr['status']=true;
$arr['message']='success';  
}

}   

}
 

$arr['access']="01";
$arr['data']=array();
$arr['stream']=array();
$arr['review']=array();
$arr['staff']=array();
$arr['exam']=array();
$arr['card']=array();
$arr['stats']=array();

$ss=mysqli_query($host,"select * from create_status where (ty='Calling - Other' or ty='Calling - Finance' or ty='Calling - Exam' or ty='Calling - Review' or ty='Calling - Attendance') and sub_domain='$subdomain'");
while($dd=mysqli_fetch_array($ss)){
    $roo=array();
    $nn=explode("-",$dd['ty']);
    $roo['name']=str_replace(' ', '',$nn[1]);
    $roo['value']=$dd['name'];
    $arr['stats'][]=$roo;
}
$s9=mysqli_query($host,"select calling.*,staff.name as sname from calling INNER JOIN staff ON staff.id=calling.respo where calling.cid='$user'  and calling.dele='0' order by calling.id desc");
while($d9=mysqli_fetch_array($s9)){
   
    if($d9['review']){
        $rev=$d9['review'];
        $s001=mysqli_query($host,"SELECT * FROM create_review where id='$rev'");
        $d001=mysqli_fetch_array($s001);
        $review=$d001['slot'];
        $row9['question']=array(); 
        $q="select * from qslot_manage INNER JOIN review_question ON review_question.id=qslot_manage.qid where qslot_manage.cid='$branch' and qslot_manage.sid='$review'";
$s2=mysqli_query($host,$q);
while($d2=mysqli_fetch_array($s2)){
 $row2=array();    
$qid=$d2['id'];
$row2['qstn']=$d2['qstn']; 
$row2['qid']=$d2['id']; 
$row2['type']=$d2['type'];  
$row2['remark']=$d2['remark'];  
$row2['option']=array();  
$s3=mysqli_query($host,"select * from review_question_option where qid='$qid' and cid='$branch' order by id asc");
while($d3=mysqli_fetch_array($s3)){
$row3=array();    
$row3['name']=$d3['options'];    
$row3['score']=$d3['score'];   
$row3['logic']=$d3['logic'];  
$row2['option'][]=$row3;
}
$row9['question'][]=$row2;

}
    }
    
    $id=$d9['id'];
$row9['id']=$d9['id']; 
$row9['type']=$d9['type']; 
$row9['review']=$d9['review'];  
$row9['fodate']=$d9['fodate'];
$row9['eper']=$d9['eper']; 
$row9['afdate']=$d9['afdate']; 
$row9['atdate']=$d9['atdate']; 
$row9['aper']=$d9['aper']; 
$row9['rper']=$d9['rper']; 
$row9['remark']=$d9['remark']; 
$row9['ftype']=$d9['ftype'];
$row9['fdate']=$d9['fdate']==""?"":date('d M Y',strtotime($d9['fdate'])); 
$row9['ftdate']=$d9['ftdate']==""?"":date('d M Y',strtotime($d9['ftdate'])); 
$row9['respo']=$d9['respo'];
$row9['sname']=$d9['sname'];
$row9['title']=$d9['title'];
$row9['status']=$d9['status'];
$row9['createby']=$d9['uploadby'];
$row9['userid']=$d9['userid']==""?"00":$d9['userid'];
$row9['date']=$d9['date'];
$row9['time']=$d9['time'];
$row9['staff']=array();
$row9['standard']=array();
$row9['exam']=array();
$ss0=mysqli_query($host,"select *,staff.id as sid from calling_attachment INNER JOIN staff ON staff.id=calling_attachment.val where calling_attachment.cid='$user' and calling_attachment.type='condi' and calling_attachment.calling='$id'");
while($dd0=mysqli_fetch_array($ss0)){
$sid=$dd0['sid'];    
$row10['name']=$dd0['name'];  
$row10['id']=$dd0['sid'];
$ss1=mysqli_query($host,"select id from calling_students where rid='$id' and staff_id='$sid'");
$rr1=mysqli_num_rows($ss1);

$ss2=mysqli_query($host,"select id from calling_status where rid='$id' and staff='$sid' and close='1' group by sid");
$rr2=mysqli_num_rows($ss2);

$row10['text1']=$rr1; 
$row10['text2']=$rr2; 
$row10['text3']=$rr1-$rr2; 
$row9['staff'][]=$row10;
}
$ss11=mysqli_query($host,"select calling_attachment.val,count(students.id) from calling_attachment INNER JOIN students ON students.standard=calling_attachment.val where calling_attachment.cid='$user' and students.cid='$user' and students.session='$session' and calling_attachment.type='standard' and calling_attachment.calling='$id' group by calling_attachment.val");
while($dd11=mysqli_fetch_array($ss11)){
$row11['name']=$dd11[0];
$row11['id']=$dd11[1];
$row9['standard'][]=$row11;
}

$ss0=mysqli_query($host,"select * from calling_attachment where calling_attachment.cid='$user' and calling_attachment.type='exam' and calling_attachment.calling='$id'");
while($dd0=mysqli_fetch_array($ss0)){
$row101['name']=$dd0['name'];  
$row9['exam'][]=$row101;
}

$arr['card'][]=$row9;
}
 

$s1=mysqli_query($host,"select name,id from staff where branch='$user' and status='Active' order by name asc");
while($d=mysqli_fetch_array($s1)){
$tid=$d['id'];
$row['name']=$d['name']; 
$row['id']=$tid; 

$arr['staff'][]=$row;
}

$s1=mysqli_query($host,"select name from exam where user='$user'");
while($d=mysqli_fetch_array($s1)){
$row8['name']=$d['name']; 
$arr['exam'][]=$row8;
}


$s1=mysqli_query($host,"select name,id from stream where cid='$user' order by num asc");
while($d=mysqli_fetch_array($s1)){
$row2['name']=$d['name'];
$row2['id']=$d['id'];
$pps=$d['id'];
$row2['standard']=array();
$s2=mysqli_query($host,"select name from standard where type='$pps' and cid='$user' order by num asc");
while($d2=mysqli_fetch_array($s2)){
 $row3['name']=$d2['name'];   
 $row2['standard'][]=$row3;
}

$arr['stream'][]=$row2;
}

$s1=mysqli_query($host,"select qslot.name as name ,create_review.id as id from qslot INNER JOIN create_review ON create_review.slot=qslot.id where qslot.cid='$user' and create_review.status='Active'  order by qslot.name asc");
while($d=mysqli_fetch_array($s1)){
$tid=$d['id'];
$row4['name']=$d['name']; 
$row4['id']=$tid; 
$arr['review'][]=$row4;
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