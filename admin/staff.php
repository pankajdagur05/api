<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_POST['delete'];
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token']; 
$date=date('Y-m-d');
$time=date('h:i:s'); 
$value=$_POST['value'];
 $branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
if($username){

    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
      
         
      $branch=$branchid;
      $user=$branch;
      $userby=$type;
      
       $name=$_POST['name'];
      $sid=$d3['id'];
      if($name){
         $gender=$_POST['gender'];
         $gname=$_POST['gname'];
         $mobile=$_POST['mobile'];
         $dob=$_POST['dob'];
         $amobile=$_POST['amobile'];
         $email=$_POST['email'];
         $address=$_POST['address'];
         $pincode=$_POST['pincode'];
         
         if($_FILES['uplm']['tmp_name']){
$file2=$_FILES["uplm"]["tmp_name"];
$ex2=$_FILES['uplm']['name'];
$extn2=pathinfo($ex2, PATHINFO_EXTENSION);	
$photo=date().time().".".$extn2;
$path3="../../cpanel/upload/profile/".$photo;
move_uploaded_file($file2,$path3);
}
  
          
      if($_POST['update']){
          $id=$_POST['update'];
             mysqli_query($host,"update staff set name='$name',gender='$gender',gname='$gname',dob='$dob',mobile='$mobile',pincode='$pincode',amobile='$amobile',mail='$email',address='$address',photo='$photo',date='$date',time='$time' where id='$id'");
           $arr['status']=true;
           $arr['message']="Successfully Updated";
          
      }else{
          $q="insert into staff set name='$name',gender='$gender',gname='$gname',dob='$dob',mobile='$mobile',amobile='$amobile',pincode='$pincode',mail='$email',address='$address',photo='$photo',date='$date',time='$time',status='Active',sub_domain='$subdomain',branch='$branchid'";
          mysqli_query($host,$q);
           
           $arr['status']=true;
           $arr['message']='Successfully Inserted';
      }
      
     
      
      }else{
          if($_POST['status']){
            $sid=$_POST['status'];
            $s=mysqli_query($host,"select status from staff where branch='$branchid' and id='$sid'");
            $d=mysqli_fetch_array($s);
            if(strtolower($d['status'])=='active'){ $st='inactive'; }else{   $st='active';  }
            mysqli_query($host,"update staff set status='$st' where branch='$branchid' and id='$sid'");
          }
          
            $q="select * from staff where branch='$branchid'";
          $arr['status']=true;
           $arr['message']=$q;
           $arr['data']=array();
        
       $s1=mysqli_query($host,$q);  
        while($d1=mysqli_fetch_array($s1)){
            
             
            $row['id']=$d1['id'];
         $row['name']=$d1['name'];
         $row['gender']=$d1['gender'];
         $row['dob']=date('d M Y',strtotime($d1['dob']));
         $row['dobb']=$d1['dob'];
         $row['gname']=$d1['gname'];
         $row['mobile']=$d1['mobile'];
         $row['amobile']=$d1['amobile'];
         $row['address']=$d1['address'];
         $row['email']=$d1['email'];
         $row['pincode']=$d1['pincode'];
         $row['status']=$d1['status'];
         $row['photo']=$d1['photo']==""?NULL:"https://edpluss.com/school/admin-panel/cpanel/upload/profile/".$d1['photo'];
         
                  
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