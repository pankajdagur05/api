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
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
    
  $arr['status']=true;
    $arr['message']='success';
     $arr['data']=array();
   
   $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffid=$d3['name'];
      
$s=mysqli_query($host,"select *,create_review.id as sid from create_review INNER JOIN review_reviewer INNER JOIN review_allot ON review_allot.rid=create_review.id and review_reviewer.rid=create_review.id where create_review.cid='$branch' and review_allot.staffid='$username' and review_reviewer.sid='$username' and create_review.status='Active' group by create_review.id order by create_review.date desc");
while($d=mysqli_fetch_array($s)){
 
$sid=$d['sid'];
$sssid=$d['res_person'];
$ss0=mysqli_query($host,"select name from staff where id='$sssid'");
$dd0=mysqli_fetch_array($ss0);
$slot=$d['slot'];
$ss=mysqli_query($host,"select * from qslot where id='$slot'");  
$dd=mysqli_fetch_array($ss);
$row['id']=$d['sid'];
$row['slotname']=$dd['name'];
$row['type']=$d['type'];
$row['review_name']=$dd0[0];

$ss9=mysqli_query($host,"select id from review_allot where rid='$sid' and staffid='$username'");
$rr9=mysqli_num_rows($ss9);

$sss=mysqli_query($host,"select review_submit.id from review_submit INNER JOIN review_allot ON review_allot.rid=review_submit.review_id where review_submit.review_id='$sid' and review_submit.date='$date' and review_allot.staffid='$username' group by review_submit.student_id");  
$rr2=mysqli_num_rows($sss);

$row['totalfill']=$rr9; 
$row['pendingfill']=$rr2;

if($d['category']=='Monthly'){
$status=$d['catevalue']==date('d')?'Active':'Inactive';   
}

if($d['category']=='Yearly'){
$status=$d['catevalue']==date('F')?'Active':'Inactive';   
}

if($d['category']=='Weekly'){
$status=$d['catevalue']==date('l')?'Active':'Inactive';   
}

if($d['category']=='Custom Date'){
$dt=explode (",", $d['catevalue']);    
$status=(strtotime($dt[0])<=strtotime($date) and strtotime($dt[1])>=strtotime($date))?'Active':'Inactive';   
}
if($d['category']=='Daily'){
  
$status='Active';  
}
$row['category']=$d['category'];
if($d['category']=='Daily'){ $row['catevalue']="Daily"; }else{ $row['catevalue']=$d['catevalue']; }

$row['date']=date('d M Y',strtotime($d['date']));
$row['status']=$status;
$row['question']=array();
$row['standard']=array();
$q="select * from qslot_manage INNER JOIN review_question ON review_question.id=qslot_manage.qid where qslot_manage.cid='$branch' and qslot_manage.sid='$slot'";
$s2=mysqli_query($host,$q);
while($d2=mysqli_fetch_array($s2)){
$qid=$d2['id'];
$row2['qstn']=$d2['qstn']; 
$row2['qid']=$d2['id']; 
$row2['type']=$d2['type'];  
$row2['remark']=$d2['remark'];  
$row2['option']=array();  
$s3=mysqli_query($host,"select * from review_question_option where qid='$qid' and cid='$branch' order by id asc");
while($d3=mysqli_fetch_array($s3)){
$row3['name']=$d3['options'];    
$row3['score']=$d3['score'];   
$row3['logic']=$d3['logic'];  
$row2['option'][]=$row3;
}
$row['question'][]=$row2;

}



$arr['data'][]=$row;




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