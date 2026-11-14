<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

 
$class=$obj['standard'];
$stream=$obj['stream'];
 

 

$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
  if($class){
        $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.*,lable.color as color from feesdue INNER JOIN lable INNER JOIN students ON students.id=feesdue.sid and lable.name=students.feesflag where students.standard='$class' and students.stream='$stream' and students.status='Active' and feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and feesdue.session='$session' 
";
  }else{
        $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.*,lable.color as color from feesdue INNER JOIN lable INNER JOIN students ON students.id=feesdue.sid and lable.name=students.feesflag where feesdue.cid='$branch' and students.status='Active' and feesdue.rdate<='$date' and feesdue.cancel='No' and feesdue.session='$session' 
";
  }
  
$q.=" group by feesdue.sid limit 200";

 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
$over=$d[0]-$d[1];
$parent=$d['parentflag'];
$s03=mysqli_query($host,"select * from lable where name='$parent'");
$d03=mysqli_fetch_array($s03);

$row['date']=$d['rdate'];
$row['sid']=$d['sid'];
$row['name']=$d['name'];
$row['fname']=$d['fname'];
$row['stream']=$d['stream'];
$row['standard']=$d['standard'];
$row['smobile']=$d['smobile'];
$row['feesflag']=$d['color'];
$row['parentflag']=$d03['color'];
 $row['amount']=$over;
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