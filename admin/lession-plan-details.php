<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$standard=$obj['standard'];
$cid=$branchid; 
$user=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){
$arr['status']=true;
$arr['message']="success";
$arr['data']=array();

if($obj['subject']){
$standard=$obj['standard'];     
$sub=$obj['subject'];    

}else{

$s1=mysqli_query($host,"select standard.name from standard where cid='$user' order by num asc");while($d1=mysqli_fetch_array($s1)){  $st=$d1['name'];
  	    $s2=mysqli_query($host,"select * from subject_link_with_class where user='$user' and stream='$st'");
  	    $s3=mysqli_query($host,"select * from lession_slot where cid='$user' and standard='$st' and fromdate<='$date' and todate>='$date' group by subject");
  	    
 $row['standard']=$d1['name'];
 $row['subject']=mysqli_num_rows($s2);
 $row['publisher']=mysqli_num_rows($s3);
 $arr['data'][]=$row;
  	    
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