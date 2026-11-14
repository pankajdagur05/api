<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type=$_POST['type'];
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
    $arr['exam']=array();
    
    $s0=mysqli_query($host,"select cid,standard from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0[0];
    $standard=$d0['standard'];
    $dt=array("local");
    
    if($user == 200){
    $arr['exam'] = array();
    echo json_encode($arr);
    exit;
}


    $ss=mysqli_query($host,"select * from subject_link_with_class where type='Non Complosary' and stream='$standard' and user='$user'");
    while($dd=mysqli_fetch_array($ss)){
        
        array_push($dt,strtolower($dd['name'])); 
    }
    
 $s=mysqli_query($host,"select sum(uploadresult.outoff),sum(uploadresult.obtain),uploadresult.exam,uploadresult.etype from uploadresult INNER JOIN exam ON exam.name=uploadresult.exam where uploadresult.sid='$sid' and uploadresult.cid='$user' and exam.user='$user' and (exam.app='active' or exam.app IS NULL) group by uploadresult.exam order by exam.id desc");  
while($d=mysqli_fetch_array($s)){ 
$dd1=0;
$dd2=0;
$exam=$d[2];


if(++$i==1){ $m="Major";  }else{ $m="Minor";}
$row14['etype']=$d['etype']."".$m;
$row14['table']=array(); 
$cc=0;
$xx=0;
$s1=mysqli_query($host,"select * from uploadresult where exam='$exam' and sid='$sid' and cid='$user' and length(obtain)>0 group by subject"); 
while($d1=mysqli_fetch_array($s1)){ 

    
$row15['subject']=$d1['subject'];
$row15['obtain']=$d1['obtain'];
$row15['outoff']=$d1['outoff'];
$row14['table'][]=$row15; 



if(array_search(strtolower($d1['subject']),$dt)==""){
    ++$xx;
}else{
    $dd1+=$d1['obtain'];
     $dd2+=$d1['outoff'];  
}
}

$row14['obtain']=$d[1]-$dd1;    
$row14['outoff']=$d[0]-$dd2;
$row14['name']=$exam;

$arr['exam'][]=$row14;
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