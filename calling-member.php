<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$condi=$obj['condi'];
$cid=$obj['cid'];
$type=$obj['type'];
$delete=$obj['delete'];
$date=date('Y-m-d');
$dates=date('Y-m-d');
$time=date('h:i:s');
$today=date('Y-m-d');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffname=$d3['name'];
      $user=$branch;
        
        if($type=='responsible'){
        
        mysqli_query($host,"update calling set respo='$condi' where id='$cid' and cid='$user'");
        $arr['status']=true;
    $arr['message']='Successfully Update Resposible Person ';
            
        }else{
        if($delete){
            mysqli_query($host,"delete from calling_attachment where type='condi' and val='$delete' and calling='$cid' and cid='$user'");  
            $arr['status']=true;
    $arr['message']='Successfully Removed';
        }
        
        if($condi){
    
   $val=$condi;
   if($val){
    $s3=mysqli_query($host,"select * from calling_attachment where type='condi' and val='$val' and calling='$cid' and cid='$user'");  
    if(mysqli_num_rows($s3)==0){
   mysqli_query($host,"insert into calling_attachment set type='condi',val='$val',calling='$cid',cid='$user'");
   $arr['status']=true;
    $arr['message']='Successfully';
    }else
    {
           $arr['status']=false;
    $arr['message']='Already Added'; 
    }
   }
 
 
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