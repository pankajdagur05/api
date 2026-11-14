<?php
include("../db.php");
$json=file_get_contents('php://input');
$obj=json_decode($json,true);
$username=$obj['username'];
$ppass=$obj['sid'];
$device=$obj['device'];
$imino=$obj['imino'];
$sid=$obj['ids'];
if($ppass){
$password=$ppass; 
}else{
if($sid){
$password=$obj['password']; 
}else{
$password=md5($obj['password']);  
}
}
function token($sub,$t,$user,$h){
    $date=date('Y-m-d');
    $time=date('h:i:s');
    $ip=$_SERVER['REMOTE_ADDR'];
    $token=$sub;
     $token.=uniqid();
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

//$q="select * from staff where status='Active' and mobile='$username' and password='$password'";

$q="select * from staff where status='Active' and mobile='$username'";
/*if($sid){
    $q.=" and id='$sid'";
}*/
$current.="\n ".$q ."\n"; 

$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
$like=mysqli_fetch_array($sql);
if($r>0){
if($like['status']=='Active'){

 

mysqli_query($host,"update staff set device='$device',imino='$imino' where mobile='$username'  and status='Active'");    
    
$tok=token($like['sub_domain'],'staff',$username,$host);
$bid=$like['branch'];
$sid=$like['id'];
$subdomain=$like['sub_domain'];
$s=mysqli_query($host,"select * from branch_category where id='$bid' and type='branch'");
$d=mysqli_fetch_array($s);


   $row2 = array();
		 $row2['status']=true;
		 $row2['message']='success';
		 $row2['data']=array();
		 
	
		$arr['subdomain']=$like['sub_domain'];
		$arr['userid']=$sid;
		 $arr['name']=$like['name'];
		          
		       $arr['logo']=$d['logo']==""?"":"https://edpluss.com/school/admin-panel/cpanel/logo/".$bid.".png";
		       $arr['photo']=$d['photo']==""?"":"https://edpluss.com/school/admin-panel/cpanel/upload/profile/".$like['photo'];
		       $arr['branchid']=$like['branch'];
		       $arr['branchname']=$d['name'];
		       $arr['token']=$tok;
		       $arr['gender']=$like['gender'];
		       $arr['mail']=$like['mail'];
		       $arr['admission']=$like['sm'];
		       $arr['lead_manage']=$like['lm'];
		       $arr['lead_manage_edit']=$like['lme'];
		       $arr['lead_manage_delete']=$like['lmd'];
		       $subdomain=$like['sub_domain'];
	$ss123=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");
      $rrrr=mysqli_fetch_array($ss123);
		       $arr['complaint_manage']=$like['cm'];
		       $arr['complaint_manage_edit']=$like['cme'];
		       $arr['complaint_manage_delete']=$like['cmd'];
		       $arr['pickup_location']=$like['pl'];
		       
		       
		       
		        $arr['school_name']=$rrrr['name'];
		        $arr['school_logo']="https://smartechindia.in/leadpanel/cpanel/logo/".$rrrr['logo'];
		        $arr['school_address']=$rrrr['address'];
		       
		       $arr['data']=array();
		        $arr['complaintstatus']=array();
		        $arr['lable']=array();
		           $arr['standard']=array();
		         $arr['statuslist']=array();
		          $arr['section']=array();
		           $arr['filter']=array();
		           
		               $arr['document']=array();
		        
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

$s3=mysqli_query($host,"select * from create_status where ty='Lead' and sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row4['name']=$d3['name'];
    $row4['color']=$d3['color'];
    $arr['statuslist'][]=$row4;
}

$s3=mysqli_query($host,"select * from create_status where ty='Complaint' and sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row7['name']=$d3['name'];
    $row7['color']=$d3['color'];
    $arr['complaintstatus'][]=$row7;
}

 $q="select * from students where cid='$bid' group by standard";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row5['name']=$d3['standard'];
    $arr['standard'][]=$row5;
}

 $q="select * from batch where cid='$bid'";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row8['name']=$d3['name'];
    $arr['section'][]=$row8;
}


 $row8['name']='photo';
    $arr['document'][]=$row8;
    $row8['name']='sign';
    $arr['document'][]=$row8;
    $row8['name']='Adhar Card';
    $arr['document'][]=$row8;
    
    
 $q="select * from document where cid='$bid' order by id desc";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row8['name']=$d3['name'];
    $arr['document'][]=$row8;
}



 
 $q="SELECT * FROM staffaccess where staff='$sid'";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row9['name']=$d3['class'];
    $row9['type']=$d3['type'];
    $arr['filter'][]=$row9;
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



  
 

 
		     $row2['data']=$arr;
		     
		
		     
 		      
}else{
   
		  $row2['status']=false;
		 $row2['message']='Your Account Has Been Inactive';
    
}
}else{
       $row2['status']=false;
		 $row2['message']="Userid & Password Did Not Match"; 
}
    
echo $json = json_encode($row2);
}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}

file_put_contents($file2, $current);








?>