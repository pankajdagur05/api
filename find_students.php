<?php 
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$key=$obj['key'];
$value=$obj['value'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');



if($username){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){
$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffname=$d3['name'];
      $user=$branch;
      
      $staff=$d3['id'];
$sesss="select * from session where cid='$branch' order by id desc limit 1";
$s6=mysqli_query($host,$sesss);
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];

  if($search){
     $q="select *,name as sname,id as sid  from students where session='$session' and cid='$branch' and status='Active' and (name like '$search%' or fname like '$search%') and (";
     $s04=mysqli_query($host,"select * from staffaccess where staff='$staff' and (feature='fees' or feature='All')");
while($d04=mysqli_fetch_array($s04)){
    $stream=$d04['stream'];
    $class=$d04['class'];
    if($d04['type']=="class"){
$q.=" (stream='$stream' and standard='$class') or ";   
}else{
 $q.=" (batch='$class') or ";     
}
}
$q.=" stream='0') order by name asc limit 50";
}else{
    
    if($key=='standard'){
           $q="select *,name as sname,id as sid  from students where session='$session' and cid='$branch' and status='Active' and (";
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" standard='$class' or ";   
           }
           $q.=" stream='0') order by name asc";
           
           
           
$file2 = 'studentstest.txt';
$current = file_get_contents($file2);
file_put_contents($file2, $q);
           
           
           
    }else{
         if($key=='section'){
         $q="select *,name as sname,id as sid  from students where session='$session' and cid='$branch' and status='Active' and (";
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" batch='$class' or ";   
           }
           $q.=" stream='0') order by name asc";
    }else{
         if($key=='tag'){
        
         $q="select *,students.name as sname,students.id as sid from students INNER JOIN students_tag INNER JOIN create_status ON students_tag.sid=students.id and students_tag.statusid=create_status.id where students.session='$session' and students.cid='$branch' and students.status='Active' and (";
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" create_status.name='$class' or ";   
           }
           $q.=" students.stream='0') order by students.name asc";
        
        
    }else{
         $q="select *,name as sname,id as sid from students where session='$session' and cid='$branch' and status='Active' and (";
     $s04=mysqli_query($host,"select * from staffaccess where staff='$staff' and (feature='fees' or feature='All')");
while($d04=mysqli_fetch_array($s04)){
    $stream=$d04['stream'];
    $class=$d04['class'];
    if($d04['type']=="class"){
$q.=" (stream='$stream' and standard='$class') or ";   
}else{
 $q.=" (batch='$class') or ";     
}
}
$q.=" stream='0') order by name asc limit 50";
    }
    }
    }
    
}



$file2 = 'studentstest.txt';
$current = file_get_contents($file2);
file_put_contents($file2, $q);

$arr['status']=true;
    $arr['message']='success'.$q;
  $arr['data']=array();
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     $sid=$d['sid'];
      $row['sid']=$sid;
 $row['name']=$d['sname'];
 $row['fname']=$d['fname'];
 $row['stream']=$d['stream'];
 $row['standard']=$d['standard'];
  $row['number']=$d['smobile'];
   $row['anumber']=$d['gmobile'];
  $row['section']=$d['batch'];
 $row['recording']=array();
 $row['fees']=array();
  $row['tag']=array();
  $row['remark']=array();
$s04=mysqli_query($host,"SELECT SUM(case when `voucher`='due' then debit else 0 end) as dueamount, SUM(case when `voucher`='receive' then credit else 0 end) as receamount FROM `feesdue` WHERE sid='$sid' and cancel<>'yes'"); 
$d04=mysqli_fetch_array($s04);    
  $row['due']=$d04[0];
   $row['receive']=$d04[1];
 $s1=mysqli_query($host,"select * from students_tag INNER JOIN create_status ON create_status.id=students_tag.statusid where students_tag.sid='$sid'");  
while($d1=mysqli_fetch_array($s1)){
    $row21['name']=$d1['name'];
    $row['tag'][]=$row21;
  }
  
 $s1=mysqli_query($host,"select * from upload_call where sid='$sid' and branch='$branch'");  
while($d1=mysqli_fetch_array($s1)){
    $row2['date']=date('d M Y',strtotime($d1['date']));
    $row2['path']="https://smartechindia.in/leadpanel/cpanel/upload/call_recarding/".$d1['file'];
    $row['recording'][]=$row2;
  }
  
   $s1=mysqli_query($host,"select * from fees_status where sid='$sid' and cid='$branch'");  
while($d1=mysqli_fetch_array($s1)){
    $row3['changedate']=date('d M Y',strtotime($d1['idate']));
    $row3['date']=date('d M Y',strtotime($d1['date']));
    $row3['remark']=$d1['remark'];
    $row['fees'][]=$row3;
  }
  
    $s1=mysqli_query($host,"select * from remark where sid='$sid' and cid='$branch'");  
while($d1=mysqli_fetch_array($s1)){
if($d1['user']){ 
$id=$d1['user'];    
$s2=mysqli_query($host,"select * from staff where id='$id'");   
$d2=mysqli_fetch_array($s2);
$type=$d2['name'];
}else{
    $type='Admin';
}
    $row3['date']=date('d M Y',strtotime($d1['date']));
     $row3['type']=$type;
    $row3['remark']=$d1['remark'];
    $row['remark'][]=$row3;
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