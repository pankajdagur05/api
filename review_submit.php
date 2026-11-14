<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$rid=$obj['rid'];
$sid=$obj['sid'];

$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
$d3=mysqli_fetch_array($s3);
$branch=$d3['branch'];
$staff=$d3['id'];
$data=$obj['data'];
$num=count($data);
$datee=date('Y-m-d');
$s112=mysqli_query($host,"select * from review_submit where student_id='$sid' and review_id='$rid' and date='$datee' and cid='$branch'");
if(mysqli_num_rows($s112)>0)
{
   $ss=mysqli_fetch_array($s112);
 
}else
{
mysqli_query($host,"insert into review_submit set student_id='$sid',review_id='$rid',score='0',date='$date',cid='$branch'");
$s12=mysqli_query($host,"select * from review_submit order by id desc limit 1");
$ss=mysqli_fetch_array($s12);
  
}
$rrid=$ss['id']; 


$sc=0;
for($a=0; $a<$num; $a++){
   
$qtn=$data[$a]['qtn'];
$ans=$data[$a]['ans'];
$remark=$data[$a]['remark'];
$score=$data[$a]['score'];
$s01=mysqli_query($host,"select max(score) from review_question_option where qid='$qtn' and cid='$branch'");
$d01=mysqli_fetch_array($s01);
if($d01[0]){
   $mark=$d01[0];    
}else{
 $mark=5;  
}
$sc=$sc+$score;

$s=mysqli_query($host,"select * from review_answer where reviewsubmit_id='$rrid' and qtn='$qtn' and cid='$branch' and date='$date'");
if(mysqli_num_rows($s)>0){
   $t.="update review_answer set ans='$ans',date='$date',score='$score',remark='$remark',outoff='$mark' where reviewsubmit_id='$rrid' and qtn='$qtn' and cid='$branch'";
mysqli_query($host,"update review_answer set ans='$ans',date='$date',score='$score',remark='$remark',outoff='$mark' where reviewsubmit_id='$rrid' and qtn='$qtn' and cid='$branch'");    
}else{
mysqli_query($host,"insert into review_answer set ans='$ans',remark='$remark',reviewsubmit_id='$rrid',qtn='$qtn',score='$score',outoff='$mark',date='$date',cid='$branch'");

}

}
mysqli_query($host,"update review_submit set score='$sc' where id='$rrid' and cid='$branch'");

    $arr['status']=true;
    $arr['message']='Sucessfully Submit'.$t;
  


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