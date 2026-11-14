<?php include("../../db.php"); 
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
$user=$branch;
 
 
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();

$s10=mysqli_query($host,"select * from time_table where status='Active' and cid='$user'");
while($d10=mysqli_fetch_array($s10)){
$tid=$d10['id'];
$dat=date("d-m-Y",strtotime($d10['fromdate']));
$row0['table_id']=$d10['id']; 
$row0['table_name']=$d10['name']; 
$row0['class']=array(); 

$s9=mysqli_query($host,"select standard_timetable.* from standard_timetable INNER JOIN standard ON standard.name=standard_timetable.name where standard_timetable.timetable='$tid' and standard_timetable.cid='$user' and standard.cid='$user' order by standard.num asc");
while($d9=mysqli_fetch_array($s9)){
$cname=$d9['name'];
$class=$d9['name'];
$section=$d9['section'];
if($d9['section']){ $cname.=" - ".$d9['section']; }
$row01['classes']=$cname; 
$row01['data']=array(); 
$s=mysqli_query($host,"select * from period where cid='$user' and timetable='$tid' order by period.ftime asc");  
while($d=mysqli_fetch_array($s)){ 
$name=$d['name'];
$row['name']=$name;
$row['type']=$d['type'];
$row['date']=$dat;
$row['ftime']=$d['ftime'];
$row['ttime']=$d['ttime'];
$row['subject']=array();
$s1=mysqli_query($host,"select timetabledata.*,staff.name as sname from timetabledata INNER JOIN staff ON staff.id=timetabledata.sid where timetabledata.cid='$user' and timetabledata.period='$name' and timetabledata.standard='$class' and timetabledata.section='$section' and timetabledata.timetable='$tid'"); 
while($d1=mysqli_fetch_array($s1)){ 
$row15['name']=$d1['subject'];
$row15['sname']=$d1['sname']; 
$row15['type']=$d1['type']; 
$row['subject'][]=$row15;        
}
$row01['data'][]=$row;
}    
$row0['classes'][]=$row01;
}

$arr['data'][]=$row0;

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