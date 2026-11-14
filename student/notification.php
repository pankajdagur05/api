<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$obj['sid'];
$username=$obj['username'];
$token=$obj['token'];
$type=$obj['type'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    if(strtolower($type)=='student'){
        $ty='s_notification';
    }else{
        $ty='p_notification';
    }
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
        $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    
    $s=mysqli_query($host,"select cid from students where id='$sid'");
    $d=mysqli_fetch_array($s);
    
    $cid=$d[0];
    
    mysqli_query($host,"update students set $ty='$date' where id='$sid'");
    $s=mysqli_query($host,"SELECT * FROM `calender` where cid='$cid' order by id desc limit 10");
    while($d=mysqli_fetch_array($s)){
        $row['msg']=$d['msg'];
        $row['title']=$d['mode'];
        $row['date']=date('d M Y',strtotime($d['date']));
        $row['time']=$d['time'];
        $arr['data'][]=$row;
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