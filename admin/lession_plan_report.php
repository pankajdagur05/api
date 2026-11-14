<?php
include("../../db.php");  
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$type='admin';
$date=date('Y-m-d');
$time=date('h:i:s');
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$idate=$_POST['idate']==""?$date:$_POST['idate'];
$s=mysqli_query($host,"select * from standard where cid='$user' order by num asc");
while($d=mysqli_fetch_array($s)){ 
    
    
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