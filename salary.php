<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$fr=$obj['fr'];
$to=$obj['to'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $user=$branch;
      $userby=$d3['name'];
      
  
$ar1=array();	
	 
$s2=mysqli_query($host,"select * from accounts where cid='$user'  and ( idate between '$fr' and '$to' ) and (dueid IS NULL or dueid=0)");

while($d2=mysqli_fetch_array($s2)){
$row2 = array();
		 $row2['date']=$d2['idate'];
		  $row2['descr']=$d2['type'];
		   $row2['amount']=$d2['amount'];
		    $row2['side']=$d2['side'];
			$row2['mode']=$d2['mode'];

		     $ar1[]=$row2;	
	
}

function date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}    
usort($ar1, 'date_compare');
 $arr3['status']=true;
 $arr3['message']='success'; 
 $arr3['data']=array();
 for($a=0; $a<count($ar1); $a++){
     $dt=$ar1[$a]['date']; $timestamp = strtotime($dt);
     $row['date']= date("d-M-Y", $timestamp);
   
     $row['mode']=$ar1[$a]['mode'];
      $row['descr']=$ar1[$a]['descr'];
       $row['side']=$ar1[$a]['side'];
       $row['amount']=$ar1[$a]['amount'];
       $arr3['data'][]=$row;
     
 }

}else{
    $arr3['status']=false;
    $arr3['message']='Token Not Match';
 
}

echo $json = json_encode($arr3);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>