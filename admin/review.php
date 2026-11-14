<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['username'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$stype=$obj['type'];
 $branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$token=$obj['token'];

$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
  $arr['status']=true;
    $arr['message']='success';
     $arr['data']=array();
   $branch=$branchid;
      $user=$branch;
      $userby=$type;


$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
        
        
if($type=="admin")
{ 
    $qe="select *,create_review.id as sid from create_review where create_review.cid='$branch' and create_review.status='Active' order by id desc";
}else
{   
    $qe="select create_review.*,create_review.id as sid from create_review INNER JOIN staff ON create_review.res_person=staff.id where create_review.cid='$branch' and create_review.res_person='$username' and create_review.status='Active' order by create_review.id desc";
}
    $arr['message']='success'.$qe;
$s=mysqli_query($host,$qe);
while($d=mysqli_fetch_array($s)){
 
$sid=$d['sid'];
$slot=$d['slot'];
$ss=mysqli_query($host,"select name from qslot where id='$slot'");  
$dd=mysqli_fetch_array($ss);

$sss=mysqli_query($host,"select id from review_allot where rid='$sid'");  
$rr=mysqli_num_rows($sss);

$sss=mysqli_query($host,"select id from review_submit where review_id='$sid'");  
$rr2=mysqli_num_rows($sss);


$row['id']=$d['sid'];
$row['slotname']=$dd['name'];
$row['type']=$d['type'];

if($type=="admin")
{
$sdd=$d['res_person'];
$s3=mysqli_query($host,"select name from staff where id='$sdd'");
$d3=mysqli_fetch_array($s3);
$row['review_name']=$d3['name'];    
}
else
{
$row['review_name']=$d['name'];    
}


$row['totalfill']=$rr;
$row['completedfill']=$rr2;
$row['pendingfill']=$rr-$rr2;

if($d['category']=='Monthly'){
$status=$d['catevalue']==date('d')?'Active':'Inactive';   
}

if($d['category']=='Yearly'){
$status=$d['catevalue']==date('F')?'Active':'Inactive';   
}

if($d['category']=='Weekly'){
$status=$d['catevalue']==date('l')?'Active':'Inactive';   
}

if($d['category']=='Custom Date'){
$dt=explode (",", $d['catevalue']);    
$status=(strtotime($dt[0])<=strtotime($date) and strtotime($dt[1])>=strtotime($date))?'Active':'Inactive';   
}
if($d['category']=='Daily'){
  
$status='Active';  
}
$row['category']=$d['category'];
if($d['category']=='Daily'){ $row['catevalue']="Daily"; }else{ $row['catevalue']=$d['catevalue']; }

$row['date']=date('d M Y',strtotime($d['date']));
$row['status']=$status; 

$row['responsible']=array();
$sss=mysqli_query($host,"select staff.name,staff.id from review_reviewer INNER JOIN staff ON staff.id=review_reviewer.sid where review_reviewer.rid='$sid'");
while($ddd=mysqli_fetch_array($sss)){
$row2['name']=$ddd[0]; 
$row2['id']=$ddd[1]; 
$row['responsible'][]=$row2;
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