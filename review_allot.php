<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$rid=$obj['rid'];
$standard=$obj['standard'];
$data=$obj['data'];
$staffidd=$obj['staffid'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
  $arr['status']=true;
    $arr['message']="Success";
     $arr['data']=array();
     $arr['data2']=array(); 
   
   $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffid=$d3['name'];

      

if($standard=='Staff List'){      
$s=mysqli_query($host,"select staff.name,staff.gname,review_allot.staffid,staff.staff_id,review_allot.id,staff.id from review_allot INNER JOIN staff ON staff.id=review_allot.allot_id where rid='$rid' and type='teacher' and cid='$branch' and review_allot.staffid='$username'");
while($d=mysqli_fetch_array($s)){ 
$sid=$d[5];    
$row['name']=$d[0];
$row['gname']=$d[1];
$row['sr']=$d[3];
$row['rid']=$d[4];
$row['id']=$d[5]; 
$row['data']=array();
$ss=mysqli_query($host,"select id,score from review_submit where review_id='$rid' and student_id='$sid'");    
if(mysqli_num_rows($ss)==0){

      
$row['score']="";
$row['aname']="";
$row['data'][]=null; 

    
$arr['data'][]=$row;
}else{
    
       $d011=mysqli_fetch_array($ss);  
       $xid=$d011['id'];
       $row['score']=$d011['score'];
       $row['aname']="Score : ".$d011['score'];
      
       $s0111=mysqli_query($host,"select * from review_answer where reviewsubmit_id='$xid' order by id desc");
       while($d0111=mysqli_fetch_array($s0111))
       {
           
            $row2['qtn']=$d0111['qtn'];
            $row2['ans']=$d0111['ans'];
            $row2['remark']=$d0111['remark'];
            $row['data'][]=$row2;
            
       }
       
$arr['data2'][]=$row;    
}

  
}
}else{

$s=mysqli_query($host,"select students.name,students.fname,review_allot.staffid,students.stu_id,review_allot.id,students.id from review_allot INNER JOIN students ON students.id=review_allot.allot_id where review_allot.rid='$rid' and review_allot.type<>'teacher' and review_allot.cid='$branch' and students.standard='$standard' and review_allot.staffid='$username'");
while($d=mysqli_fetch_array($s)){ 
    $sid=$d[5];
$row['sr']=$d[3]; 
$row['rid']=$d[4]; 
$row['id']=$d[5]; 
$row['name']=$d[0];
$row['gname']=$d[1];
$row['data']=array();

$ss=mysqli_query($host,"select id,score from review_submit where review_id='$rid' and student_id='$sid'");    
if(mysqli_num_rows($ss)==0){
 $row['score']="";
 $row['aname']="";
   $row['data'][]=null; 

    
$arr['data'][]=$row;
}else{
  $d011=mysqli_fetch_array($ss);  
       $xid=$d011['id'];
       $row['score']=$d011['score'];
       $row['aname']="Score : ".$d011['score'];
      
       $s0111=mysqli_query($host,"select * from review_answer where reviewsubmit_id='$xid' order by id asc");
       while($d0111=mysqli_fetch_array($s0111))
       {
            $row2['qtn']=$d0111['qtn'];
            $row2['ans']=$d0111['ans'];
             $row2['remark']=$d0111['remark'];
           $row['data'][]=$row2;
       }
        
$arr['data2'][]=$row;    
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