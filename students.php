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

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
        if($branch=='139')
{
  $session='2024-25' ;  
    
}

$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
  $section=array();
 array_push($section,"popo");
 
 
  if($search){
      $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and students.status='Active' and students.session='$session' and 
("; 
     $s04=mysqli_query($host,"select * from staffaccess where staff='$staff' and  (feature='fees' or feature='All')");
while($d04=mysqli_fetch_array($s04)){
    $stream=$d04['stream'];
    $class=$d04['class'];
    if($d04['type']=="class"){
$q.=" (students.stream='$stream' and students.standard='$class') or ";   
}else{
 $q.=" (students.batch='$class') or ";     
}
}
$q.=" students.stream='0') order by students.name asc limit 50";
}else{
    
    if($key=='standard'){
          $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and students.status='Active' and students.session='$session' and 
("; 
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" students.standard='$class' or ";   
           }
           $q.=" students.stream='0')  group by feesdue.sid order by students.name asc";
    }else{
         if($key=='section'){
      
           $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and students.status='Active' and students.session='$session' and 
("; 
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" students.batch='$class' or ";   
           }
           $q.=" students.stream='0')  group by feesdue.sid order by students.name asc";
    }else{
         if($key=='tag'){
        
        
           $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.* from feesdue INNER JOIN students INNER JOIN students_tag INNER JOIN create_status ON students_tag.sid=students.id and students_tag.statusid=create_status.id and students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and students.status='Active' and students.session='$session' and 
(";
          
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" create_status.name='$class' or ";   
           }
           $q.=" students.stream='0')  group by feesdue.sid order by students.name asc";
           
        
        
    }else{
 
    $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and students.status='Active' and  feesdue.rdate<='$date' and feesdue.cancel='No' and students.session='$session' and 
(";

$s04=mysqli_query($host,"select * from staffaccess where staff='$staff' and feature='fees'");
while($d04=mysqli_fetch_array($s04)){
    $stream=$d04['stream'];
    $class=$d04['class'];
  if($d04['type']=="class"){
$q.=" (students.stream='$stream' and students.standard='$class') or ";
}else{
 $q.=" (students.batch='$class') or ";     
  array_push($section,$class);
 
}
}
$q.=" students.stream='0') group by feesdue.sid";
}
    }
    }
    
}



 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
    
 $stype='Standard';
$stream=$d['stream'];
if($d['batch']){  $b=array_search($d['batch'],$section); if($b>0){   $standard=$d['batch']; $stream=$d['stream']."-".$d['standard']; $stype='Section'; }else{ $standard=$d['standard']; }  }else{ $standard=$d['standard']; }     
     
     
$over=$d[0]-$d[1];
$parent=$d['parentflag'];
$fees=$d['feesflag'];
$s03=mysqli_query($host,"select * from lable where name='$parent'");
$d03=mysqli_fetch_array($s03);
$s04=mysqli_query($host,"select * from lable where name='$fees'");
$d04=mysqli_fetch_array($s04);
$row['type']=$stype;
$row['date']=$d['rdate'];
$row['sid']=$d['sid'];
$row['name']=$d['name'];
$row['fname']=$d['fname'];
$row['stream']=$stream;
$row['standard']=$standard;
$row['smobile']=$d['smobile'];
$row['section']=$d['batch'];
$row['feesflag']=$d04['color'];
$row['parentflag']=$d03['color'];
 $row['amount']=$over;
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