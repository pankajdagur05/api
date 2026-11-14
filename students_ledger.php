<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$sid=$obj['sid'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');

if($subdomain){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){


$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $user=$branch;


$s04=mysqli_query($host,"select * from students where id='$sid'");
$d04=mysqli_fetch_array($s04);
$section=$d04['batch'];

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
$d6=mysqli_fetch_array($s6);
$session=$d6['name'];
     
$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
$q2="select *,feesdue.id as idd,erp_group.name as gname,erp_category.name as cname,feesdue.userby from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.cid='$user' and feesdue.sid='$sid' and feesdue.session='$session' and  feesdue.cancel='No' order by feesdue.rdate asc"; $s3=mysqli_query($host,$q2);  
while($d3=mysqli_fetch_array($s3)){
     if($d3['cancel']=='Yes'){ $cancel=$d3['date']; }else{ $rece+=$d3['credit']; $pay+=$d3['debit']; } 
     if($d3['transport']=="Yes"){ $grp=$d3['gname']." - ".$section; }else{  $grp=$d3['gname']; }
 $row['date']=date('d M Y',strtotime($d3['rdate']));
 $row['group']=$grp;
 $row['ledger']=$d3['cname'];
 $row['byname']=$d3['userby'];
 $row['cancel']=$cancel;
 $row['mode']=$d3['mode']==""?" ":$d3['mode'];
 $row['debit']=$d3['debit'];
  $row['credit']=$d3['credit'];
    $arr['data'][]=$row;
    
}
     
$arr['debit']=$pay;
$arr['credit']=$rece;




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