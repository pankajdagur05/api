<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$name=$obj['name'];

$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

$arr['status']=true;

  $q="select * from accounts where type='$name' and cid='$branch'";
    $arr['message']='success'.$q;
  $arr['data']=array();
$s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
if($d['dueid']==NULL or $d['dueid']<1){  $debit=$d['amount']; $db+=$debit; }
if($d['dueid']>1 or $d['mode']!='Due'){   $credit=$d['amount']; $cb+=$credit;  }
 $row['date']=date('d M Y',strtotime($d['idate']));
 $row['type']=$d['type'];
 $row['mode']=$d['mode'];
 $row['descr']=$d['descr'];
  $row['debit']=$debit;
  $row['credit']=$credit;
     
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