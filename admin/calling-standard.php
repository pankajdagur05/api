<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$id=$obj['id'];
$sid=$obj['sid'];
$num=$obj['num'];
$data=$obj['data'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s'); 
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

 
$arr['status']=true;
$arr['message']='success';  
$arr['data']=array();   
 
 $s0=mysqli_query($host,"select * from calling where id='$id'");
 $d0=mysqli_fetch_array($s0);


if($sid){
 
if(strtolower($d0['type'])=='finance')
{
    $coon=" and (students.rte='No' or students.rte IS NULL or students.rte='')";
}
else
{
     $coon=" and (students.status='Active' or students.status=NULL or students.status='')";
}
   
  $q="select count(students.id),students.standard from students INNER JOIN calling_students INNER JOIN staff ON calling_students.stu_id=students.id and staff.id=calling_students.staff_id where students.status='Active' and students.cid='$user' and students.session='$session' and calling_students.rid='$id' ";
  
     $q.=" and calling_students.staff_id='$sid' ";
 

$q.=" order by students.standard";
 
$ss=mysqli_query($host,$q); 

$arr['message']='success'.$q; 
while($dd=mysqli_fetch_array($ss)){
  $stand=$dd[1];



 
     if($d0['type']=='Exam'){
     $obb=0;
     $outb=0;
         $row11=array();
           
for($r=0; $r<count($exam); $r++){
    $exa=$exam[$r];
    $row11['name']=$exam[$r];
    $row11['marks']=array();
    $ob=0;
     $out=0;
      $row12=array();
      $per=$d0['eper'];
      $qq="select uploadresult.obtain,uploadresult.outoff from uploadresult INNER JOIN students INNER JOIN calling_students ON calling_students.stu_id=students.id and students.id=uploadresult.sid where  calling_students.rid='$id' and calling_students.staff_id='$sid' and students.standard='$stand' and students.session='$session' and exam='$exa'  HAVING  ((uploadresult.obtain*100)/ uploadresult.outoff)<=$per";
    $ss1=mysqli_query($host,$qq);
    $roo=mysqli_num_rows($sss);
 
    
    
}
     
      }
      
      if($d0['type']=='Attendance'){
         
           $row11=array();
          $q="select SUM(CASE WHEN attendance.status='P' THEN 1 ELSE 0 END) as p,count(attendance.id) from attendance INNER JOIN students INNER JOIN calling_students ON calling_students.stu_id=students.id and students.id=attendance.sid where calling_students.rid='$id' and calling_students.staff_id='$sid' and attendance.subdomain='$user' group by attendance.sid HAVING (p*100/count(attendance.id))<$per";
          if($d0['afdate']){
              $fdate=$d0['afdate'];
              $tdate=$d0['atdate'];
              $q.=" and idate BETWEEN '$fdate' AND '$tdate'";
          }
           $sss=mysqli_query($host,$q);
           $roo=mysqli_num_rows($sss);
            
      }
      
      if(strtolower($d0['type'])=='finance'){
          $q="select sum(credit),sum(debit) from feesdue INNER JOIN students INNER JOIN calling_students ON calling_students.stu_id=students.id and students.id=feesdue.sid where calling_students.rid='$id' and calling_students.staff_id='$sid' and feesdue.cid='$user' and students.standard='$stand' and cancel='No'  and students.session='$session' ";
        if($d0['fodate']){
            $ff=explode(",",$d0['fodate']);
         $fdate=str_replace(' ', '', $ff[0]);
        $q.=" and rdate<='$fdate'";
        }else{
      $q.=" and rdate<='$date'";
        }
        $q.=" group by students.id HAVING sum(debit)-IFNULL(sum(credit),0)>0";
          $sss=mysqli_query($host,$q);
         $roo=mysqli_num_rows($sss);
       
      }
      
    
    
     $intra+=$roo;

$row['name']=$dd[1];
$row['counts']=$dd[0]; 
$row['intraction']=$roo==""?0:$roo; 
$arr['data'][]=$row;
   
}  
  
   
    
}else{
 

if(strtolower($d0['type'])=='finance')
{
    $coon=" and (students.rte='No' or students.rte IS NULL or students.rte='')";
}
else
{
     $coon=" and (students.status='Active' or students.status=NULL or students.status='')";
}
   
$q="select count(students.id),students.standard from students where students.cid='$user' $coon and students.session='$session'   and (";
$ss0=mysqli_query($host,"select * from calling_attachment where calling_attachment.cid='$user' and calling_attachment.type='standard' and calling_attachment.calling='$id'");

while($dd0=mysqli_fetch_array($ss0)){
    $nn=$dd0['val'];
$q.=" students.standard='$nn' or ";  
}
$q.=" students.standard='') group by students.standard";
 
$ss=mysqli_query($host,$q); 

$arr['message']='success'; 
while($dd=mysqli_fetch_array($ss)){
  $stand=$dd[1];



 
     if($d0['type']=='Exam'){
     $obb=0;
     $outb=0;
         $row11=array();
           
for($r=0; $r<count($exam); $r++){
    $exa=$exam[$r];
    $row11['name']=$exam[$r];
    $row11['marks']=array();
    $ob=0;
     $out=0;
      $row12=array();
      $per=$d0['eper'];
      $qq="select uploadresult.obtain,uploadresult.outoff from uploadresult INNER JOIN students ON students.id=uploadresult.sid where students.standard='$stand' and students.session='$session' and exam='$exa'  HAVING  ((uploadresult.obtain*100)/ uploadresult.outoff)<=$per";
    $ss1=mysqli_query($host,$qq);
    $roo=mysqli_num_rows($sss);
 
    
    
}
     
      }
      
      if($d0['type']=='Attendance'){
         
           $row11=array();
          $q="select SUM(CASE WHEN attendance.status='P' THEN 1 ELSE 0 END) as p,count(attendance.id) from attendance INNER JOIN students ON students.id=attendance.sid where attendance.subdomain='$user' group by attendance.sid HAVING (p*100/count(attendance.id))<$per";
          if($d0['afdate']){
              $fdate=$d0['afdate'];
              $tdate=$d0['atdate'];
              $q.=" and idate BETWEEN '$fdate' AND '$tdate'";
          }
           $sss=mysqli_query($host,$q);
           $roo=mysqli_num_rows($sss);
            
      }
      
      if(strtolower($d0['type'])=='finance'){
          $q="select sum(credit),sum(debit) from feesdue INNER JOIN students on students.id=feesdue.sid where feesdue.cid='$user' and students.standard='$stand' and cancel='No'  and students.session='$session' ";
        if($d0['fodate']){
            $ff=explode(",",$d0['fodate']);
         $fdate=str_replace(' ', '', $ff[0]);
        $q.=" and rdate<='$fdate'";
        }else{
      $q.=" and rdate<='$date'";
        }
        $q.=" group by students.id HAVING sum(debit)-IFNULL(sum(credit),0)>0";
          $sss=mysqli_query($host,$q);
         $roo=mysqli_num_rows($sss);
       
      }
      
    
    
     $intra+=$roo;

$row['name']=$dd[1];
$row['counts']=$dd[0]; 
$row['intraction']=$roo==""?0:$roo; 
$arr['data'][]=$row;
   
}  
  
}


$q="select id from calling_status where cid='$user' and rid='$id' and close='1'";
$s90=mysqli_query($host,$q); 

$q="select id from calling_status where cid='$user' and rid='$id'  group by sid";
$s91=mysqli_query($host,$q); 

$arr['text1']=$intra;
$arr['text2']=mysqli_num_rows($s90);
$arr['text3']=mysqli_num_rows($s91)-mysqli_num_rows($s90);
$arr['text4']=$intra-mysqli_num_rows($s91);  
 
}
else{
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