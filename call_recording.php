<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_REQUEST['delete'];
$username=$_REQUEST['userid'];
$subdomain=$_REQUEST['subdomain'];
$token=$_REQUEST['token'];
$mode=$_REQUEST['mode'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
    
$sid=$_REQUEST['sid'];

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
$staffname=$d3['name'];

if($mode=="lead")
{
if($_FILES['uplm']['name']){
$token=$_REQUEST['token'];
$file2=$_FILES["uplm"]["tmp_name"];
$ex2=$_FILES['uplm']['name'];
$extn2=pathinfo($ex2, PATHINFO_EXTENSION);	
$path=date().time().$sid;
$path3="../cpanel/upload/call_recarding/lead/".$path.".mp3";
move_uploaded_file($file2,$path3);
$file=$path.".mp3";
mysqli_query($host,"insert into complaint_status set comp_id='$sid',update_date='$date',upd_sts='Recording',sts_remark='$file',user='$branch',staffid='$username',staffname='$staffname',date='$date',time='$time' ");
$arr['status']=true;
$arr['message']='success';
}else
{
$arr['status']=false;
$arr['message']='failed';
}

}else
{
if($_FILES['uplm']['name']){
$token=$_REQUEST['token'];
$file2=$_FILES["uplm"]["tmp_name"];
$ex2=$_FILES['uplm']['name'];
$extn2=pathinfo($ex2, PATHINFO_EXTENSION);	
$path=date().time().$sid;
$path3="../cpanel/upload/call_recarding/".$path.".mp3";
move_uploaded_file($file2,$path3);
$file=$path.".mp3";
mysqli_query($host,"insert into upload_call set file='$file',date='$date',time='$time',branch='$branch',sid='$sid'");  
$arr['status']=true;
$arr['message']='success';
}else
{
$arr['status']=false;
$arr['message']='failed';
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