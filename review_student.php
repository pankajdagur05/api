<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$standard=$obj['standard'];
$rid=$obj['rid'];
$type=$obj['type'];
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

$s01=mysqli_query($host,"select * from session where cid='$branch' order by id desc");
$d01=mysqli_fetch_array($s01);
$session=$d01['name'];

   if($standard=="other")
   {
      $s3=mysqli_query($host,"select * from review_allot where rid='$rid' and type='other' order by allot_id asc");
        $arr['status']=true;
        $arr['message']="select * from review_allot where rid='$rid' and type='other' order by allot_id asc";
        $arr['data']=array();
        while($d=mysqli_fetch_array($s3)){

 $row['name']=$d['allot_id'];
 $row['fname']="other";
 $row['sid']=$d['id'];
 $row['id']=$d['id'];
 $row['data']=array();
 $row['score']="";
 $row['data'][]=null; 
 $arr['data'][]=$row;

 } 
   }else
{
   if($standard!="teacher") 
   {
    
           $q="select *,name as sname,id as sid  from students where session='$session' and cid='$branch' and status='Active' and standard='$standard' order by name asc";   
          

$arr['status']=true;
    $arr['message']=''.$q;
  $arr['data']=array();
   
 $s=mysqli_query($host,$q); 
 while($d=mysqli_fetch_array($s)){
     $sid=$d['sid'];
 $row['sid']=$sid;
 $row['name']=$d['sname'];
 $row['fname']=$d['fname'];
 $row['id']=$d['sid'];
 $row['stream']=$d['stream'];
 $row['data']=array();
 $s011=mysqli_query($host,"select * from review_submit where student_id='$sid' and review_id='$rid' and cid='$branch'");
       if(mysqli_num_rows($s011)>0)
       {
       $d011=mysqli_fetch_array($s011);  
       $xid=$d011['id'];
       $row['score']=$d011['score'];
      
       $s0111=mysqli_query($host,"select * from review_answer where reviewsubmit_id='$xid' order by id desc");
       while($d0111=mysqli_fetch_array($s0111))
       {
            $row2['qtn']=$d0111['qtn'];
            $row2['ans']=$d0111['ans'];
             $row2['remark']=$d0111['remark'];
           $row['data'][]=$row2;
       }
       }else
       {
            $row['score']="";
           $row['data'][]=null; 
       }
     $arr['data'][]=$row;
           
       
 }

}
   else
   {
    
$s3=mysqli_query($host,"select *,staff.id as sid from staff inner join review_allot on staff.id=review_allot.allot_id where review_allot.rid='$rid' order by staff.name asc");
$arr['status']=true;
    $arr['message']='success_';
  $arr['data']=array();
     while($d=mysqli_fetch_array($s3)){
          
    
 $row['name']=$d['name'];
 $row['fname']=$d['gname'];
 $row['sid']=$d['sid'];
 $sid=$d['sid'];
  $row['id']=$d['sid'];
   $row['data']=array();
    
    $s0=mysqli_query($host,"select * from create_review where id='$rid' and create_review.cid='$branch'");
    $d0=mysqli_fetch_array($s0);
    
    if($d0['category']=='Monthly'){
        $m=date('m');
$q2=" and month(date)='$m'";  
}

if($d['category']=='Yearly'){
     $y=date('Y');
$q2=" and year(date)='$y'"; 
}

if($d['category']=='Weekly'){
 
 if(date('D')!='Mon')
{    
 //take the last monday
  $staticstart = date('Y-m-d',strtotime('last Monday'));    

}else{
    $staticstart = date('Y-m-d');   
}

//always next saturday

if(date('D')!='Sat')
{
    $staticfinish = date('Y-m-d',strtotime('next Saturday'));
}else{

        $staticfinish = date('Y-m-d');
}
    
  
$q2=" and date BETWEEN $staticstart AND $staticfinish";    
    
}

if($d['category']=='Custom Date'){
    
$dt=explode (",", $d0['catevalue']);    
$q2=" and date BETWEEN $dt[0] AND $dt[1]"; 
 
}
if($d0['category']=='Daily'){
     $date=date('y-m-d');
$q2=" and date='$date'";   
 
}
    
    $q="select * from review_submit where student_id='$sid' and review_id='$rid' and cid='$branch'";
    $q.=$q2;
 $s011=mysqli_query($host,$q);
       if(mysqli_num_rows($s011)>0)
       {
       $d011=mysqli_fetch_array($s011);  
       $xid=$d011['id'];
       $row['score']=$d011['score'];
       $s0111=mysqli_query($host,"select * from review_answer where reviewsubmit_id='$xid' order by qtn asc");
       while($d0111=mysqli_fetch_array($s0111))
       {
            $row2['qtn']=$d0111['qtn'];
            $row2['ans']=$d0111['ans'];
            $row2['remark']=$d0111['remark'];
           $row['data'][]=$row2;
       }
       }else
       {
            $row['score']="";
           $row['data'][]=null; 
       }
     $arr['data'][]=$row;
 }
   }
    
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