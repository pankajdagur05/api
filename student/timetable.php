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
    
    $s0=mysqli_query($host,"select cid,standard,batch from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $user=$d0[0];
    $class=$d0['standard'];
    
    if(strtolower($d0['batch'])=="select section"){ $section="";  }else{ $section=$d0['batch']; }
     $q="select * from timetabledata where cid='$user' and standard='$class' and section='$section' order by id desc limit 1";
 $s=mysqli_query($host,$q);  
$d=mysqli_fetch_array($s);
$tid=$d['timetable'];
$si=mysqli_query($host,"select fromdate from time_table where id='$tid'");
$di=mysqli_fetch_array($si);
if($di[0]){
$arr['date']=date('d M Y',strtotime($di[0]));    
}else{
$arr['date']='';    
}
$dat=date("d-m-Y",strtotime($d['fromdate']));
$s=mysqli_query($host,"select * from period where cid='$user' and timetable='$tid' order by period.ftime asc");  
while($d=mysqli_fetch_array($s)){ 
$name=$d['name'];
$row['name']=$name;
$row['type']=$d['type'];
$row['date']=$dat;
$row['ftime']=date('h:i A',strtotime($d['ftime']));
$row['ttime']=date('h:i A',strtotime($d['ttime']));
$row['subject']=array();
$s1=mysqli_query($host,"select timetabledata.*,staff.name as sname from timetabledata INNER JOIN staff ON staff.id=timetabledata.sid where timetabledata.cid='$user' and timetabledata.period='$name' and timetabledata.standard='$class' and timetabledata.section='$section' and timetabledata.timetable='$tid'"); 
while($d1=mysqli_fetch_array($s1)){ 
$row15['name']=$d1['subject'];
$row15['sname']=$d1['sname']; 
$row15['type']=$d1['type']; 
$row['subject'][]=$row15;        
}
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