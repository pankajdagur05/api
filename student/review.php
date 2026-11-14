<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    
    $s0=mysqli_query($host,"select cid from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0[0];
    
$s=mysqli_query($host,"SELECT qslot.name,sum(review_answer.outoff) as outoff,sum(review_answer.score) as score,review_submit.id as rid FROM `review_submit` INNER JOIN review_answer INNER JOIN create_review INNER JOIN qslot ON qslot.id=create_review.slot and create_review.id=review_submit.review_id and review_answer.reviewsubmit_id=review_submit.id where review_submit.student_id='$sid' and review_submit.cid='$user' GROUP by review_submit.review_id"); 
while($d=mysqli_fetch_array($s)){  
$row12['obtain']=$d[2];
$row12['outoff']=$d[1];
$row12['name']=$d[0];
$obt+=$d[2];
$out+=$d[1];
$rid=$d['rid'];
$row12['table']=array();
$s2=mysqli_query($host,"SELECT * FROM `review_submit` INNER JOIN review_answer INNER JOIN review_question ON review_question.id=review_answer.qtn and review_answer.reviewsubmit_id=review_submit.id where review_submit.student_id='$sid' and review_submit.cid='$user' and review_submit.id='$rid'"); 
while($d2=mysqli_fetch_array($s2)){

$row13['qstn']=$d2['qstn'];
$row13['score']=$d2['score'];
$row13['outoff']=$d2['outoff'];
$row12['table'][]=$row13;
++$x;

}
$arr['data'][]=$row12;
$arr['text1']=$x;
$arr['text2']=$obt;
$arr['text3']=$out;
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