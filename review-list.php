<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$rid=$obj['rid'];
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
      
$s=mysqli_query($host,"select count(id),sum(if(staffid IS NULL,1,0)) as notallot from review_allot where rid='$rid' and type='teacher' and cid='$branch' and staffid='$username'");
while($d=mysqli_fetch_array($s)){
    if($d[0]>0){
   $s1=mysqli_query($host,"select  count(review_submit.id) from review_allot INNER JOIN review_submit ON review_submit.review_id=review_allot.rid and review_allot.allot_id=review_submit.student_id where review_allot.rid='$rid' and review_allot.type='teacher' and review_allot.cid='$branch' and review_allot.staffid='$username'");    
$row['name']='Staff List';   
$row['total']=$d[0]; 
$row['allot']=mysqli_num_rows($s1);
$arr['data'][]=$row;  
}
}

$s=mysqli_query($host,"select count(review_allot.id),sum(if(review_allot.staffid IS NULL,1,0)) as notallot,students.standard from review_allot INNER JOIN students ON students.id=review_allot.allot_id where review_allot.rid='$rid' and review_allot.type<>'teacher' and review_allot.cid='$branch'   and staffid='$username' group by students.standard");
while($d=mysqli_fetch_array($s)){
$s1=mysqli_query($host,"select count(review_submit.id) from review_allot INNER JOIN students INNER JOIN review_submit ON  review_submit.review_id=review_allot.rid and review_allot.allot_id=review_submit.student_id and students.id=review_allot.allot_id where review_allot.rid='$rid' and review_allot.type<>'teacher' and review_allot.cid='$branch'   and staffid='$username' group by students.standard");
$row['name']=$d[2];   
$row['total']=$d[0]; 
$row['allot']=mysqli_num_rows($s1);
$arr['data'][]=$row;    
}
 
 $arr['message']="select count(review_allot.id),sum(if(review_allot.staffid IS NULL,1,0)) as notallot,students.standard from review_allot INNER JOIN students ON students.id=review_allot.allot_id where review_allot.rid='$rid' and review_allot.type<>'teacher' and review_allot.cid='$branch'   and staffid='$username' group by students.standard";

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