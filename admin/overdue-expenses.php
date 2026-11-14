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

  
$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
$q="select *,sum(amount) as atype from accounts where cid='$user' and mode='Due' group by type order by type asc ";

 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
$aid=$d['id'];
$a1=$d['atype'];
$type=$d['type'];
$s1=mysqli_query($host,"select sum(amount) from accounts where type='$type' and  dueid>0 and cid='$user'");
$d1=mysqli_fetch_array($s1);
$over=$a1-$d1[0];
if($a1>$d1[0]){
    
$row['date']=date('d M Y',strtotime($d['idate']));
$row['name']=$d['type'];
$row['descr']=$d['descr'];
$row['amount']=$over;
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