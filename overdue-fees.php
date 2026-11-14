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
      

$s01=mysqli_query($host,"select * from session where cid='$user' order by id desc limit 1");
$d01=mysqli_fetch_array($s01);
$session=$d01['name'];




  if($search){
      $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.id as sid ,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and (students.status='Active' or  students.status='' or students.status IS NULL ) and ( students.name like '$search%' or  students.fname like '$search%' ) and students.session='$session' and ("; 
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
$q.=" students.stream='0') group by feesdue.sid order by students.name asc limit 50";
}else{
    
    if($key=='standard'){
          $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid as sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and (students.status='Active' or  students.status='' or students.status IS NULL ) and students.session='$session' and 
("; 
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" students.standard='$class' or ";   
           }
           $q.=" students.stream='0') group by feesdue.sid order by students.name asc";
    }else{
         if($key=='section'){
      
           $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid as sid ,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and (students.status='Active' or  students.status='' or students.status IS NULL ) and students.session='$session' and 
("; 
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" students.batch='$class' or ";   
           }
           $q.=" students.stream='0') group by feesdue.sid order by students.name asc";
    }else{
         if($key=='tag'){
        
         
           $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid as sid ,students.*,feesdue.* from feesdue INNER JOIN students INNER JOIN students_tag INNER JOIN create_status ON students_tag.sid=students.id and students_tag.statusid=create_status.id and students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and students.status='Active' and students.session='$session' and 
(";
          
           for($a=0; $a<count($value); $a++){
               $class=$value[$a]['name'];
               $q.=" create_status.name='$class' or ";   
           }
           $q.=" students.stream='0') group by feesdue.sid order by students.name asc ";
           
        
        
    }else{
    $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid as sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid  where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and students.status='Active' and students.session='$session' and 
(";


  
$section=array();
 array_push($section,"popo");
$s04=mysqli_query($host,"select * from staffaccess where staff='$staff'");
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
$q.=" students.stream='0') group by feesdue.sid limit 50";

}
    }
    }
    
}

 
$arr['status']=true;
$arr['message']='Success';
$arr['data']=array();
    
$s=mysqli_query($host,$q); 

while($d=mysqli_fetch_array($s))
{

$stype='Standard';
$stream=$d['stream'];
 $sid=$d['sid'];
$ans="No";
$s033=mysqli_query($host,"select * from fees_status where sid='$sid' and idate>='$date' order by id desc limit 1");
if(mysqli_num_rows($s033)>0){
$ans="yes";
}

$s0=mysqli_query($host,"select sum(debit),sum(credit) from feesdue where sid='$sid' and cancel='No' and rdate<='$date' group by sid");
$d0=mysqli_fetch_array($s0);


$s11=mysqli_query($host,"select sum(debit),sum(credit) from feesdue where sid='$sid' and cancel='No' group by sid");
$d11=mysqli_fetch_array($s11);

 ///edit on 28 april 2025
if(is_array($section))
{
if($d['batch']){  $b=array_search($d['batch'],$section);
if($b>0){   $standard=$d['batch']; $stream=$d['stream']."-".$d['standard']; $stype='Section'; }else{ $standard=$d['standard']; }  }else{ $standard=$d['standard']; }     
}  //////
$over=$d[0]-$d[1];
if($over>0 and $ans=="No"){ $res=1; }else{ $res=2;  }
$parent=$d['parentflag'];
$fees=$d['feesflag'];
if($parent){
$s03=mysqli_query($host,"select * from lable where name='$parent'");
$d03=mysqli_fetch_array($s03);
}
if($fees){
$s04=mysqli_query($host,"select * from lable where name='$fees'");
$d04=mysqli_fetch_array($s04);
}
$row['text1']=$d11[0];
$row['text2']=$d11[1];
$row['text3']=$d0[0]-$d0[1];
$row['date']=$d['rdate'];
$row['sid']=$d['sid'];
$row['name']=$d['name'];
$row['type']=$stype;
$row['fname']=$d['fname'];
$row['stream']=$stream;
$row['standard']=$standard;
$row['smobile']=$d['smobile'];
$row['param']=$res;
$row['feesflag']=$d04['color'];
$row['parentflag']=$d03['color'];
$row['amount']=$over;
$row['tag']=array();

   $s1=mysqli_query($host,"select * from students_tag INNER JOIN create_status ON create_status.id=students_tag.statusid where students_tag.sid='$sid'");  
while($d1=mysqli_fetch_array($s1)){
    $row21['name']=$d1['name'];
    $row['tag'][]=$row21;
  }
  
  
   $s1=mysqli_query($host,"select * from upload_call where sid='$sid' and branch='$branch'");  
      $row['recording']=array();
while($d1=mysqli_fetch_array($s1)){
    $row2['date']=$d1['date'];
    $row2['path']="https://smartechindia.in/leadpanel/cpanel/upload/call_recarding/".$d1['file'];
    $row['recording'][]=$row2;
  }
  

  
    $s1=mysqli_query($host,"select * from remark where sid='$sid' and cid='$branch'");  
     $row['remark']=array();
while($d1=mysqli_fetch_array($s1)){
if($d1['user']){ 
$id=$d1['user'];    
$s2=mysqli_query($host,"select * from staff where id='$id'");   
$d2=mysqli_fetch_array($s2);
$type=$d2['name'];
}else{
    $type='Admin';
}
    $row3['date']=$d1['date'];
     $row3['type']=$type;
    $row3['remark']=$d1['remark'];
    $row['remark'][]=$row3;
  }
     
  
     $s123=mysqli_query($host,"select * from fees_status where sid='$sid' and cid='$branch'");  
      $row['fees']=array();
while($d123=mysqli_fetch_array($s123)){
   
    $row222['changedate']=$d123['idate'];
    $row222['date']=$d123['date'];
    $row222['remark']=$d123['remark'];
     if($d123['uid']==""){ $row222['name']="Admin"; }else{ $row222['name']=$d123['uid']; }
    $row['fees'][]=$row222;
  }

$arr['data'][]=$row;


$pay+=$over;

   
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