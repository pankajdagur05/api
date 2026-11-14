<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$obj['sid'];
$username=$obj['username'];
$token=$obj['token'];
$type=$obj['type'];
$typed=$obj['typed'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    $s=mysqli_query($host,"Select * from certificate_student where sid='$sid' group by cateid order by id desc ");
    while($d=mysqli_fetch_array($s)){
        $row['link']="https://edpluss.com/school/admin-panel/api/students/certificate-print.php?cate=".$d['cateid']."&stuid=".$sid;
        $row['img']="https://edpluss.com/school/img/certificate.jpeg";
        $row['certificate_name']=$d['catename'];
        $row['date']=$d['date'];
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