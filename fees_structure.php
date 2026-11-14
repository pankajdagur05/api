<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
$param=$obj['type'];

if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $user=$branch;
      $staffid=$d3['name'];
$arr['status']=true;
$arr['message']='success';
$arr['data']=array();

$s1=mysqli_query($host,"select * from fees_structure where trans='$param' and cid='$user' group by standard");
while($d1=mysqli_fetch_array($s1))
{
$standard=$d1['standard'];
$row['name']=$standard;
$row['group']=array();
$s2=mysqli_query($host,"select erp_group.name from fees_structure inner join erp_category inner join erp_group on fees_structure.ladger=erp_category.name and erp_category.type=erp_group.id where fees_structure.standard='$standard' and fees_structure.trans='$param' and fees_structure.cid='$user' and erp_group.cid='$user' group by erp_group.name");
while($d2=mysqli_fetch_array($s2))
 {
   $row1['name']=$d2[0];
   $row1['subgroup']=array(); 
   $names=$d2[0];
   $s3=mysqli_query($host,"select fees_structure.* from fees_structure inner join erp_category inner join erp_group on fees_structure.ladger=erp_category.name and erp_category.type=erp_group.id where fees_structure.standard='$standard' and fees_structure.trans='$param' and fees_structure.cid='$user' and erp_group.name='$names' group by fees_structure.ladger order by num asc");
while($d3=mysqli_fetch_array($s3))
 {  $row2=array(); 
    $row2['ladger']=$d3['ladger'];
    $row2['due']=date('d M',strtotime($d3['due']));
    $row2['amount']=$d3['amount'];
    $row1['subgroup'][]=$row2;
 }
 $row['group'][]=$row1;
 }   
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