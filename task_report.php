<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$taskid=$_POST['taskid'];
$type='admin';
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

  $s0=mysqli_query($host,"select * from branch_select where sub_domain='$subdomain'");
$d0=mysqli_fetch_array($s0);
$branch=$d0['branch'];        
$user=$branch;    

 $s0=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");
$d0=mysqli_fetch_array($s0);
$sid=$d0['id'];        
   

function rows($h,$qr){
$s=mysqli_query($h,$qr);
return mysqli_num_rows($s);
}
 
 
 

 
$arr['status']=true;
$arr['message']="success";

if($taskid){
$q="select * from task where id='$taskid' and user='$user' ";
$s=mysqli_query($host,$q);
$d=mysqli_fetch_array($s);
$arr['title']=$d['title'];
$arr['category']=$d['category'];
$arr['fromdate']=$d['fromdate'];
$arr['todate']=$d['todate'];
$arr['descr']=$d['descr'];
$arr['status']=$d['status'];
$arr['date']=$d['date'];
$arr['finishdate']=$d['finishdate'];
$arr['data']=array(); 
$s1=mysqli_query($host,"select * from taskmember INNER JOIN staff ON staff.id=taskmember.sid where taskmember.tid='$taskid' and taskmember.user='$user'"); while($d1=mysqli_fetch_array($s1)){ $sid=$d1['sid'];  

$row['name']=$d1['name'];
$row['sid']=$d1['sid'];
$row['type']=$d1['type'];
$row['data']=array();

 $s2=mysqli_query($host,"select * from tasksms where resp='$sid' and user='$user' and tid='$taskid'"); while($d2=mysqli_fetch_array($s2)){
$row2['msg']=$d2['msg'];
$row2['date']=date('D M Y',$d2['date']);
$row2['cdate']=$d2['cdate']==''?'':date('D M Y',strtotime($d2['cdate']));
$row2['finish']=$d2['fdate']==''?'':date('D M Y',strtotime($d2['fdate']));
$row['data'][]=$row2;
}
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