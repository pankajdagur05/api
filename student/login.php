<?php include("../../db.php");
include("../save_token.php");
$username=$_POST['username'];
$user=$username;
$otp=$_POST['otp'];
$subdomain=$_POST['subdomain'];
$password=md5($_POST['password']);
$type=$_POST['type'];
$mode=$_POST['mode'];
$date=date('Y-m-d');
$time=date('h:i:s');
$ip=$_SERVER['REMOTE_ADDR'];   
$device=$_POST['device'];

if($type=='student'){ 
    $field="smobile"; 
    $ps="s_pass"; 
    $dev="s_device"; 
    
}else{ 
    $field="smobile"; 
    $ps="p_pass"; 
    $dev="p_device"; 
}
 
if($username){
$q="select students.mname,students.fname,students.stu_id,students.cid,students.name,students.id,students.standard,students.stream,branch_category.iname as nn,branch_category.sub_domain as sub_domain from students INNER JOIN branch_category ON branch_category.id=students.cid where students.$field='$username' and students.$ps='$password' and branch_category.type='branch'";
if($subdomain){ 
    $q.=" and branch_category.sub_domain='$subdomain'"; 
}
$q.=" and students.status='Active' order by students.id desc ";
$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
if($r>0){
     $like=mysqli_fetch_array($sql);
         $ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']='success';
         $sub=$like['sub_domain'];
           $token=time().uniqid();
		    mysqli_query($host,"insert into apk_token set token='$token',date='$date',ip='$ip',type='$type',user='$username',sub_domain='$sub'"); 
		    mysqli_query($host,"update students set $dev='$device' where $field='$username' and status='Active'");
		    //savePushToken($host, $sub, ($type=='student' ? 'student' : 'parent'), $like['id'], $_POST['fcm_token'] ?? '');
		    //savePushToken($host, $sub, ($type=='student' ? 'student' : 'parent'), $like['id'], $_POST['fcm_token'] ?? '', $_POST['platform'] ?? '');
		     savePushToken($host, $sub, ($type=='student' ? 'student' : 'parent'), $like['id'], $_POST['fcm_token'] ?? '', $username, $_POST['platform'] ?? '');   // 👈 Fixed
		     $ar1['token']=$token; 
		       $ar1['type']=$type; 
		        $ar1['sid']=$like['id']; 
		     
		       echo $json = json_encode($ar1);
         
}
else{
  	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']='Username & password Did Not Match';
		     
		       echo $json = json_encode($ar1);  
}
 
}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}
 
?>