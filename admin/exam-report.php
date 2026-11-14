<?php
include("../../db.php");  
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$type='admin';
$date=date('Y-m-d');
$time=date('h:i:s');
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$class=array();
$section=array();

$s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");
$d6=mysqli_fetch_array($s6);
$session=$d6['name'];


$s=mysqli_query($host,"select * from exam where user='$branch' and session='$session' order by id desc limit 10");
while($d=mysqli_fetch_array($s)){  
    $exam=$d['exam'];
$row['name']=$d['name']; 
$row['data']=array();
$q="select * from stream where cid='$branch' and (status IS NULL or status='active') order by num asc";
$s1=mysqli_query($host,$q);
while($d1=mysqli_fetch_array($s1)){
    $stream=$d1['name'];
    $row1['name']=$d1['name'];
   
 $q="select standard,batch,count(students.id) from students INNER JOIN standard ON standard.name=students.standard where students.cid='$branch' and (students.status='active' or students.status IS NULL) and students.stream='$stream' and students.session='$session' group by students.standard,students.batch order by standard.num asc";
    $s2=mysqli_query($host,$q);
    $d2=mysqli_fetch_array($s2);
$row1['standard']=mysqli_num_rows($s2);
$row1['students']=$d2[2];
$s3=mysqli_query($host,"select uploadresult.id from uploadresult INNER JOIN students ON students.id=uploadresult.sid where students.cid='$branch' and students.session='$session' and (students.status='active' or students.status IS NULL) and uploadresult.exam='$exam' group by uploadresult.sid");
$row1['upload']=mysqli_num_rows($s3);
    
    $row['data'][]=$row1;
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