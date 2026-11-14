<?php
include("../db.php");

$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['username'];
$sid=$obj['sid'];
$password=md5($obj['password']);
$device=$obj['device'];
function token($sub,$t,$user,$h){
    $date=date('Y-m-d');
    $time=date('h:i:s');
    $ip=$_SERVER['REMOTE_ADDR'];
    $token=$sub;
     $token.=uniqid();
     mysqli_query($h,"delete from apk_token where user='$user'");
    if(mysqli_query($h,"insert into apk_token set token='$token',date='$date',ip='$ip',type='$t',user='$user',sub_domain='$sub'")){
        
        return $token;
    }
    
}
  $file2 = 'api_log.txt';
$current = file_get_contents($file2);
$current.="\n login_log_satrts \n"; 

$current.=$json; 


if($username){

$arr=array();
//$q="select * from staff where mobile='$username' and ";
$q="select * from staff where mobile='$username' ";
if($sid){
 $q.=" id='$sid'";      
}else{
//$q.=" password='$password'";    
}
$current.="\n ".$q ."\n"; 
$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
if($r==1){
$like=mysqli_fetch_array($sql);
$tok=token($like['sub_domain'],'staff',$username,$host);
$bid=$like['branch'];
$sid=$like['id'];
$subdomain=$like['sub_domain'];
$s=mysqli_query($host,"select * from branch_category where id='$bid' and type='branch'");
$d=mysqli_fetch_array($s);


   $row2=array();
		 $row2['status']=true;
		 $row2['message']='success';
		 $row2['data']=array();
	
		$arr['subdomain']=$like['sub_domain'];
		$arr['userid']=$sid;
		          $arr['name']=$like['name'];
		          $arr['logo']="https://www.smartechindia.in/leadpanel/cpanel/upload/profile/".$like['photo'];
		       $arr['branchid']=$like['branch'];
		       $arr['branchname']=$d['name'];
		       $arr['token']=$tok;
		       $arr['gender']=$like['gender'];
		       $arr['lead_manage']=$like['lm'];
		       $arr['lead_manage_edit']=$like['lme'];
		       $arr['lead_manage_delete']=$like['lmd'];
		       $arr['complaint_manage']=$like['cm'];
		       $arr['complaint_manage_edit']=$like['cme'];
		       $arr['complaint_manage_delete']=$like['cmd'];
		       $arr['pickup_location']=$like['pl'];
		       $arr['data']=array();
		        $arr['lable']=array();
		         $arr['statuslist']=array();
		       $s1=mysqli_query($host,"SELECT * FROM category_assign INNER JOIN branch_category ON branch_category.id=category_assign.category where category_assign.staff='$sid' and branch_category.status='Active'
");

while($d2=mysqli_fetch_array($s1)){
    
    $row['name']=$d2['name'];
    $row['categoryid']=$d2['category'];
    $arr['data'][]=$row;
}

$s3=mysqli_query($host,"select * from lable where sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row4['name']=$d3['name'];
    $row4['color']=$d3['color'];
    $arr['lable'][]=$row4;
}



 $arr['feature1']="";
     $arr['feature2']="";
      $arr['feature3']="";
     $arr['feature4']="";
      $arr['feature1_url']="";
    $arr['feature2_url']="";
      $arr['feature3_url']="";
     $arr['feature4_url']="";

 $q="SELECT * FROM app_extras where sid='$sid'";
$s_extras=mysqli_query($host,$q);
if(mysqli_num_rows($s_extras)>0)
{
$row_extras=mysqli_fetch_array($s_extras);
   
    $arr['feature1']=$row_extras['feature1'];
     $arr['feature2']=$row_extras['feature2'];
      $arr['feature3']=$row_extras['feature3'];
     $arr['feature4']=$row_extras['feature4'];
      $arr['feature1_url']=$row_extras['feature1_url'];
    $arr['feature2_url']=$row_extras['feature2_url'];
      $arr['feature3_url']=$row_extras['feature3_url'];
     $arr['feature4_url']=$row_extras['feature4_url'];
    
    
}





 $row4['name']='Interested';
    $row4['color']='green';
    $arr['statuslist'][]=$row4;
    
     $row4['name']='Not Interested';
    $row4['color']='red';
    $arr['statuslist'][]=$row4;
    
     $row4['name']='Nutural';
    $row4['color']='orange';
    $arr['statuslist'][]=$row4;

$s3=mysqli_query($host,"select * from create_status where sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row4['name']=$d3['name'];
    $row4['color']=$d3['color'];
    $arr['statuslist'][]=$row4;
}
		     $row2['data'][]=$arr;
		     
		
		     
		      
}else{
    $row2['status']=false;
		 $row2['message']='Username & password Did not match';
    
}
    
echo $json = json_encode($row2);
}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}


$current.="\n login_log_ends \n"; 
file_put_contents($file2, $current);






?>