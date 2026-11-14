<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);

$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;

$remark=$_POST['remark'];
$id=$_POST['id'];
$status=$_POST['status'];

$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
     $s=mysqli_query($host,"select * from apk_token where token='$token' and type='$type' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
 


if($id)
{
$q="update leave_application set status='$status',remark='$remark' where id='$id'";
mysqli_query($host,$q);
}
        
    $arr['status']=true;
    $arr['message']="success".$q;
    $arr['data']=array();
    
   
    
$s=mysqli_query($host,"select leave_application.*,students.name,students.standard,students.fname from leave_application inner join students on students.id=leave_application.sid where leave_application.cid='$user' order by leave_application.id desc"); while($d=mysqli_fetch_array($s)){ 

    
    
if($d['todate']){ $tdate=date("d M Y", strtotime($d['todate'])); }
$row16['date']=date("d/m/Y", strtotime($d['date'])); 
$row16['subject']=$d['subject'];

$row16['descr']=$d['description'];  
$row16['type']=$d['type'];
$row16['status']=$d['status'];
$row16['name']=$d['name'];
$row16['standards']=$d['standard'];
$row16['fname']=$d['fname'];
$row16['reason']=$d['reason'];
$row16['remark']=$d['remark'];
$row16['id']=$d['id'];
$row16['fdate']=date("d M Y", strtotime($d['fromdate']));
$row16['tdate']=$tdate;
$arr['data'][]=$row16;
       
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