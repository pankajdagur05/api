<?php include("../../db.php");
include("../save_token.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$ppass=$obj['sid'];
$username=$obj['username'];
if($ppass){
$password=$ppass; 
}else{
$password=md5($obj['password']); 
} 
$device=$obj['device'];
if(is_numeric($username)){
 $type='other';   
}else{
    $type='admin';
}
 
function token($sub,$t,$user,$h){
    $date=date('Y-m-d');
    $time=date('h:i:s');
    $ip=$_SERVER['REMOTE_ADDR'];
    $token=$sub;
     $token.=uniqid().time(); 
    if(mysqli_query($h,"insert into apk_token set token='$token',date='$date',ip='$ip',type='$t',user='$user',sub_domain='$sub'")){
        
        return $token;
    }
    
}
if($username){

$arr=array();
if(strtolower($type)=='admin'){

   $ss=mysqli_query($host,"select * from global where name='masterlogin' and val='$password'");
    if(mysqli_num_rows($ss)==1){
    $s1=mysqli_query($host,"select * from admin_login where username='$username'"); 
    $d1=mysqli_fetch_array($s1);
    $password=$d1['password'];
    }
    
 $q="select * from admin_login where username='$username' and password='$password'";   
}else{
 $q="select staff.* from staff INNER JOIN staffaccessadmin ON staffaccessadmin.sid=staff.id where staff.mobile='$username' and staff.password='$password' and staff.status='Active'";   
}

$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
if($r>0){
   
$like=mysqli_fetch_array($sql);
$subdomain=$like['sub_domain'];
$id=$like['id'];
if(strtolower($type)=='admin'){
mysqli_query($host,"update admin_login set device='$device' where sub_domain='$subdomain'"); 
$q2="select * from branch_category where sub_domain='$subdomain' and type='branch' and (status='Active' or status IS NULL) order by selected desc";
$s3=mysqli_query($host,"select * from branch_category where sub_domain='$subdomain' and type='branch' and (status='Active' or status IS NULL) order by selected desc limit 1");
$d3=mysqli_fetch_array($s3);
$user=$d3['id'];
}else{
mysqli_query($host,"update staff set admin_device='$device' where  mobile='$username'");
$q2="select branch_category.*,staff.id as sid from staff INNER JOIN branch_category INNER JOIN staffaccessadmin ON branch_category.id=staff.branch and staffaccessadmin.sid=staff.id where staff.mobile='$username'  and staff.status='Active' and (branch_category.status='Active' or branch_category.status IS NULL) group by staff.branch";
$user=$like['branch'];
}
$tok=token($like['sub_domain'],$type,$username,$host);
savePushToken($host, $subdomain, $type, $id, $obj['fcm_token'] ?? '');
   $row2 = array();
   $row2['status']=true;
   $row2['message']='success';
   $row2['data']=array();
   $row2['branch']=array();
   $row2['document']=array();
		  
 $row8['name']='photo';
    $row2['document'][]=$row8;
    $row8['name']='sign';
    $row2['document'][]=$row8;
    $row8['name']='Adhar Card';
    $row2['document'][]=$row8;
    
$q="select * from document where cid='$bid' order by id desc";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row8['name']=$d3['name'];
    $row2['document'][]=$row8;
}
///////////////////

 $s01=mysqli_query($host,"select * from branch_category where id='$bid' and (status='Active' or status IS NULL)");

		           $d8=mysqli_fetch_array($s01);
		            $row8['facebook']=$d8['fb'];
		             $row8['lat']=$d8['lat'];
		              $row8['lat']="26.9123584";
		              
		            $row8['logo']="https://edpluss.com/school/admin-panel/cpanel/logo/".$d8['id'].".png";
		              $row8['lon']=$d8['lon'];
		                    $row8['lon']="70.9165056";
		               $row8['meter']=$d8['meter'];
		            $row8['branchname']=$d8['name'];
	                 $row8['instagram']=$d8['insta'];
		            $row8['youtube']=$d8['yt'];
		           $row8['twitter']=$d8['tw'];
		           $row8['bwebsite']=$d8['website'];
		           $row8['blat']=$d8['blat'];
		           $row8['blon']=$d8['blon'];
		           $row8['bnumber']=$d8['mobile'];
		           $row8['bwnumber']=$d8['amobile'];
		           $row8['bemail']=$d8['email'];
		            $row8['baddress']=$d8['address'];
		            $arr['account'][]=$row8;
 

/////////////////////////////////
	
		 
$s=mysqli_query($host,$q2);
while($d=mysqli_fetch_array($s)){
$cid=$d['id'];    
$row4['name']=$d['name']; 
$row4['id']=$d['id']; 
$row4['sid']=$d['sid']; 
$row4['session']=array();
$s2=mysqli_query($host,"select * from session where cid='$cid' order by id desc");
while($d2=mysqli_fetch_array($s2)){
$row5['name']=$d2['name'];
$row4['session'][]=$row5;
}
$row2['branch'][]=$row4;
}
 
		$arr['subdomain']=$like['sub_domain'];
		          $arr['name']=$like['name'];
		          $arr['userid']=$like['id'];
		          $arr['type']=$type;
		          $arr['logo']="https://www.edpluss.com/school/admin-panel/cpanel/upload/profile/".$like['photo'];
		       $arr['branchid']=$user;
		       $arr['token']=$tok;
		     $row2['data']=$arr;
		     
		     
		      
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

?>