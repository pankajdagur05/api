<?php error_reporting(0);
include("../../db.php");
$username=$_POST['username'];
$user=$username; 
$subdomain=$_POST['subdomain'];
$password=md5($_POST['old_password']);
$npass=md5($_POST['new_password']);
$type=$_POST['type'];
$date=date('Y-m-d');
$time=date('h:i:s');
    
if($type=='student'){ $field="mobile"; $ps="s_pass"; $dev="s_device"; }else{ $field="gmobile"; $ps="p_pass"; $dev="p_device"; }
if($username){
$q="select $ps from students where $field='$username' and $ps='$password'";
$q.=" and status='Active' order by id desc ";
$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
if($r>0){ 
mysqli_query($host,"update students set $ps='$npass' where $field='$username'");  
$ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']='Password Has Been Changed';
		     
		       echo $json = json_encode($ar1);
}
else{
  	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']='Old password Did Not Match';
		     
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