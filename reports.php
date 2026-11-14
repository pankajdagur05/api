<?php error_reporting(4);
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$typed=$obj['type'];
$val=$obj['val'];
if($typed=="search"){
$r1=$val;   
}else{
$r2=$val;    
}

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
      $user=$branch;
      
      $s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
       

if($r1 or $r2)
{
    if($r1==""){
        $q="select *,students.id as sid,students.name as sname from students INNER JOIN stream On stream.name=students.stream where students.cid='$user' and students.session='$session' and students.status='Active' and (students.standard='$r2' or CONCAT(students.standard, ' ', students.batch) ='$r2' )";
    }else{
        $q="select *,students.id as sid,students.name as sname from students INNER JOIN stream On stream.name=students.stream where students.cid='$user' and students.session='$session' and students.status='Active' and (students.name like '%$r1%' or students.stu_id like '%$r1%' or students.fname like '%$r1%')";
    }

  
$s6=mysqli_query($host,$q);   
 $r=mysqli_num_rows($s6);  
 if($r==0){
     $arr['status']=false;
    $arr['message']="Data Not Found";
 }else{ 
    $ar=array();
      $rank=array();
    $ss1=mysqli_query($host,"select * from scoresetting where cid='$user'");  $dd1=mysqli_fetch_array($ss1); 
    while($d6=mysqli_fetch_array($s6)){
      $sid=$d6['sid'];
      $row['name']=$d6['sname']; 
      $row['fname']=$d6['fname']; 
      $row['mname']=$d6['mname']; 
        $row['number']=$d6['smobile']; 
      $row['stream']=$d6['stream']; 
      $row['standard']=$d6['standard']; 
      $row['id']=$d6['sid'];
$q="select count(id),sum(if(status = 'P',1,0)) as p from attendance where subdomain='$user' and sid='$sid' "; if($dd1['attenfrom']){ $fr=$dd1['attenfrom']; $to=$dd1['attento']; $q.=" and date between '$fr' and '$to'"; } $s=mysqli_query($host,$q); $d=mysqli_fetch_array($s); $attendance0=round(($d[1]*100)/$d[0]); 
$q="SELECT sum(uploadresult.obtain),sum(uploadresult.outoff) FROM `uploadresult`INNER JOIN exam ON exam.name=uploadresult.exam where uploadresult.sid='$sid' and uploadresult.cid='$user' and (exam.include='' or exam.include IS NULL or exam.include='yes')";  $s=mysqli_query($host,$q); $d=mysqli_fetch_array($s); $exam0=round(($d[0]*100)/$d[1]);  
$q="SELECT sum(review_answer.score),sum(review_answer.outoff) FROM `review_submit` INNER JOIN review_answer INNER JOIN create_review INNER JOIN qslot ON qslot.id=create_review.slot and create_review.id=review_submit.review_id and review_answer.reviewsubmit_id=review_submit.id where review_submit.student_id='$sid' and review_submit.cid='$user' and (qslot.include='Yes' or qslot.include='' or qslot.include IS NULL)";  $s=mysqli_query($host,$q); $d=mysqli_fetch_array($s); $review0=round(($d[0]*100)/$d[1]);  
$s=mysqli_query($host,"select sum(if(voucher = 'due',debit,0)) as due,sum(if(voucher = 'receive',credit,0)) as rece from feesdue where sid='$sid' and cid='$user' and cancel='No' $filter");
$d=mysqli_fetch_array($s);
$per=round(($d[1]*100)/$d[0]);
$finance0=$per;
if($attendance0>0){ $tot2+=($attendance0*$dd1['aw'])/100;  }else{ $attendance0=0; } $out+=$dd1['aw']; 
if($review0>0){  $tot2+=($review0*$dd1['rw'])/100; }else{ $review0=0; } $out+=$dd1['rw']; 
if($finance0>0){  $tot2+=($finance0*$dd1['fw'])/100;  }else{ $finance0=0; } $out+=$dd1['fw'];
if($exam0>0){  $tot2+=($exam0*$dd1['ew'])/100;  }else{ $exam0=0; } $out+=$dd1['ew'];
$tot3=round(($tot2*100)/$out); 
if($tot3>0){
    
}else{
$tot3=0;    
}
$row['score']=$tot3;
$ar[$sid]=$row;
$rank[$sid]=$tot3;
$tot2=0; $tot3=0; $out=0;    

}

arsort($rank);

foreach ($rank as $key => $value) {
++$a;    
$x=0;
$k=0;
$sid=$key;
$row['sid']=$key;
$row['name']=$ar[$key]['name'];;
$row['fname']=$ar[$key]['fname'];;
$row['stream']=$ar[$key]['stream'];;
$row['standard']=$ar[$key]['standard'];;
$row['score']=$ar[$key]['score'];;
$row['rank']=$a;
$arr['data'][]=$row;

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