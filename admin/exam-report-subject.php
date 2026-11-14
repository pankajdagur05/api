<?php
include("../../db.php");  
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branch=$_POST['branch'];
$standard=$_POST['standard'];
$section=$_POST['section']; 
$exam=$_POST['exam'];
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
 
$arr['status']=true;
$arr['message']="success";
$arr['data']=array();


 $q="select standard,batch,stream,count(students.id) from students INNER JOIN standard ON standard.name=students.standard where students.cid='$branch' and (students.status='active' or students.status IS NULL) and students.session='$session' group by students.standard,students.batch order by standard.num asc";
    $s2=mysqli_query($host,$q);
while($d2=mysqli_fetch_array($s2)){ 
    $stream=$d2['stream'];
    $standard=$d2['standard'];
    $section=$d2['batch'];
     $s3=mysqli_query($host,"select count(students.id) from students INNER JOIN uploadresult ON uploadresult.sid=students.id where students.cid='$branch' and uploadresult.exam='$exam' and students.status<>'inactive' and students.stream='$stream' and students.standard='$standard' and students.batch='$section' and students.session='$session' group by uploadresult.sid");
    $d3=mysqli_fetch_array($s3);
    $row2['standard']=$d2['standard'];
    $row2['section']=$d2['batch'];
    $row2['students']=$d2[3];
    $row2['uploadstudents']= $d3[0];
     $row2['data']=array();
   
    
    
 $s4=mysqli_query($host,"select id,name from subject_link_with_class where user='$branch' and stream='$standard'");
    while($d4=mysqli_fetch_array($s4)){
        $subject=$d4['name'];
        $row3['name']=$d4['name'];
        $q="select count(students.id) from students INNER JOIN uploadresult ON uploadresult.sid=students.id where students.cid='$branch' and uploadresult.exam='$exam' and  students.session='$session' and students.status<>'inactive'  and students.standard='$standard' and uploadresult.subject='$subject' and students.batch='$section'";
         $s5=mysqli_query($host,$q);
         $d5=mysqli_fetch_array($s5);
        $row3['upload']=$d5[0];
        $row2['data'][]=$row3;
         
    }
 
$arr['data'][]=$row2;
    
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