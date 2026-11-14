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
$username=$obj['username'];
$cid=$branchid; 
$user=$branchid;
$branch=$branchid;
$id=$obj['id'];
$sid=$obj['sid'];
$standard=$obj['fstandard'];
$sdd=$sid;
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');

if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){


 $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];

 
$arr['status']=true;
$arr['message']='success';  
$arr['data']=array();  
 $arr['data2']=array();  
 
 $s0=mysqli_query($host,"select * from calling where id='$id'");
 $d0=mysqli_fetch_array($s0);
 
$exam=array(); 
 $k=0;
   $qq="select val from calling_attachment where calling_attachment.cid='$user' and calling_attachment.type='exam' and calling_attachment.calling='$id'";
         $ss0=mysqli_query($host,$qq);
while($dd0=mysqli_fetch_array($ss0)){
 
 $exam[$k++]=trim($dd0['val']); 
    
}
  
  if(strtolower($d0['type'])=='finance'){
    $coon=" and (students.rte='No' or students.rte IS NULL or students.rte='')";
}else{
     $coon=" and (students.status='Active' or students.status=NULL or students.status='')";
}
  
  
 $q="select students.id as id ,students.name as name,students.standard as standard,staff.name as ssname, students.fname as fname,students.stream as stream,students.smobile as smobile,calling_students.staff_id as sid from students INNER JOIN calling_students INNER JOIN staff ON calling_students.stu_id=students.id and staff.id=calling_students.staff_id where  students.cid='$user' and students.session='$session' $coon and calling_students.rid='$id' ";
 if($sid){
     $q.=" and calling_students.staff_id='$sid' ";
 }

$q.=" and students.standard='$standard' order by students.name asc";
 
$ss=mysqli_query($host,$q); 
while($dd=mysqli_fetch_array($ss)){
     $ob=0; $out=0;
$xx=0;
     $sid=$dd['id'];
     $ssid=$dd['id'];
     $q="select sum(close),count(id) from calling_status where cid='$user' and rid='$id' and sid='$sid'";
     $s90=mysqli_query($host,$q);
     $d90=mysqli_fetch_array($s90);
     
     if($d0['review']>0){
         $rv=$d0['review'];
       $q="select score from review_submit where cid='$user' and review_id='$rv' and student_id='$sid'";
     $s900=mysqli_query($host,$q);
     $d900=mysqli_fetch_array($s900);
    $score=$d900[0];
     }else{
         $score=NULL;
     }
    
     $row['id']=$dd['id']; 
   $row['name']=$dd['name'];
   $row['ssname']=$dd['ssname'];
     $row['status']=$d90[1];
    $row['number']=$dd['smobile'];
    $row['fname']=$dd['fname'];
     $row['score']=$score; 
    $row['sid']=$dd['sid']; 
     $row['standard']=trim($dd['standard']); 
      $row['stream']=$dd['stream']; 
      $row['exam']=array();
      $row['finance']=array();
      $row['review']=array();
      $row['attendance']=array();
 
 
 
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
      $qq="select subject,outoff,obtain from uploadresult where sid='$sid' and exam='$exa'";
    $ss1=mysqli_query($host,$qq);
    while($dd1=mysqli_fetch_array($ss1)){
        $ob+=$dd1['obtain'];
          $out+=$dd1['outoff'];
        $row12['subject']=$dd1['subject'];
        $row12['outoff']=is_numeric($dd1['outoff'])?$dd1['outoff']:0;
        $row12['obtain']=is_numeric($dd1['obtain'])?$dd1['obtain']:0;
        $row11['marks'][]=$row12;
        $obb+=$dd1['obtain'];
        $outb+=$dd1['outoff'];
    }
    

$row11['ob']=$ob;
    $row11['out']=$out;
    $row['exam'][]=$row11;
    
    
}
    if($d0['eper']){
      $per=$d0['eper'];
    if((($obb*100)/$outb)>$per){
     $xx=1;    
    }
    }
         
      }
      
      if($d0['type']=='Attendance'){
         
           $row11=array();
          $q="select SUM(CASE WHEN status='P' THEN 1 ELSE 0 END) as p,SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as a from attendance where sid='$sid' ";
          if($d0['afdate']){
              $fdate=$d0['afdate'];
              $tdate=$d0['atdate'];
              $q.=" and idate BETWEEN '$fdate' AND '$tdate'";
          }
           $sss=mysqli_query($host,$q);
           $ddd=mysqli_fetch_array($sss);
              
           $row11['ob']=$ddd[0];
            $row11['out']=$ddd[0]+$ddd[1];
            $row['attendance'][]=$row11;
            
             if($d0['aper']){
    $per=$d0['aper'];
    if((($ob*100)/$out)>$per){
    $xx=1;    
    }
    }
            
      }
      
      if($d0['type']=='Finance'){
        $q="select sum(credit),sum(debit) from feesdue where cid='$user' and sid='$ssid' and cancel='No'";
        if($d0['fodate']){
            $ff=explode(",",$d0['fodate']);
         $fdate=str_replace(' ', '', $ff[0]);
        $q.=" and rdate<='$fdate'";
        }else{
      $q.=" and rdate<='$date'";
        }
        
         $sss=mysqli_query($host,$q);
         $ddd=mysqli_fetch_array($sss);
         
          $row11['ob']=$ddd[0];
          $row11['out']=$ddd[1];
          if($ddd[0]>=$ddd[1]){
              $xx=1;
          }
     
          $row['finance'][]=$row11;
      }
      
    
   
     if($d0['type']=='Review'){
          
          $ob="4";
          $out="10";
          $row11['ob']=$ob;
          $row11['out']=$out;
    if($ob>=$out){
    $xx=1;    
    }
          
          $row['review'][]=$row11;
      }
     
      if($xx==1){
          
      }else{
          if($d90[0]>0 and $sdd>0){
             $arr['data2'][]=$row;  
             ++$cls;
          }else{
              $arr['data'][]=$row;  
          }
           
        ++$xm;
      }
      
}  



    if($sdd=="" or $sdd==NULL){
  $q="select students.id as id ,students.name as name,students.standard as standard, students.fname as fname,students.stream as stream,students.smobile as smobile from students where students.cid='$user' $coon and students.session='$session' and students.id NOT IN(select stu_id from calling_students where cid='$user' and rid='$id')   and ";

$q.=" students.standard='$standard' order by students.name asc";

$ss=mysqli_query($host,$q); 
while($dd=mysqli_fetch_array($ss)){

  
$xx=0;
$ssid=$dd['id'];
  $row['id']=$dd['id']; 
   $row['name']=$dd['name']; 
    $row['fname']=$dd['fname']; 
    $row['number']=$dd['smobile']; 
     $row['standard']=trim($dd['standard']); 
      $row['stream']=$dd['stream']; 
       $row['exam']=array();
      $row['finance']=array();
      $row['review']=array();
      $row['attendance']=array();
      
      
 
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
      $qq="select subject,outoff,obtain from uploadresult where sid='$ssid' and exam='$exa'";
    $ss1=mysqli_query($host,$qq);
    while($dd1=mysqli_fetch_array($ss1)){
        $ob+=$dd1['obtain'];
          $out+=$dd1['outoff'];
        $row12['subject']=$dd1['subject'];
        $row12['outoff']=is_numeric($dd1['outoff'])?$dd1['outoff']:0;
        $row12['obtain']=is_numeric($dd1['obtain'])?$dd1['obtain']:0;
        $row11['marks'][]=$row12;
        $obb+=$dd1['obtain'];
        $outb+=$dd1['outoff'];
    }
    

$row11['ob']=$ob;
    $row11['out']=$out;
    $row['exam'][]=$row11;
    
    
}
    if($d0['eper']){
      $per=$d0['eper'];
    if((($obb*100)/$outb)>$per){
     $xx=1;    
    }
    }
         
      }
      
      if($d0['type']=='Attendance'){
           $row11=array();
          $q="select SUM(CASE WHEN status='P' THEN 1 ELSE 0 END) as p,SUM(CASE WHEN status='A' THEN 1 ELSE 0 END) as a from attendance where sid='$ssid' ";
          if($d0['afdate']){
              $fdate=$d0['afdate'];
              $tdate=$d0['atdate'];
              $q.=" and idate BETWEEN '$fdate' AND '$tdate'";
          }
           $sss=mysqli_query($host,$q);
           $ddd=mysqli_fetch_array($sss);
              $ob="4";
          $out="10";
           $row11['ob']=$ddd[0];
            $row11['out']=$ddd[0]+$ddd[1];
            $row['attendance'][]=$row11;
            
             if($d0['aper']){
    $per=$d0['aper'];
    if((($ob*100)/$out)>$per){
    $xx=1;    
    }
    }
            
      }
      
      if($d0['type']=='Finance'){
        $q="select sum(credit),sum(debit) from feesdue where cid='$user' and sid='$ssid' and cancel='No'";
        if($d0['fodate']){
            $ff=explode(",",$d0['fodate']);
         $fdate=str_replace(' ', '', $ff[0]);
        $q.=" and rdate<='$fdate'";
        }else{
      $q.=" and rdate<='$date'";
        }
        
         $sss=mysqli_query($host,$q);
         $ddd=mysqli_fetch_array($sss);
         
          $row11['ob']=$ddd[0];
          $row11['out']=$ddd[1];
          if($ddd[0]>=$ddd[1]){
              $xx=1;
          }
     
          $row['finance'][]=$row11;
      }
      
    
   
     if($d0['type']=='Review'){
          
          $ob="4";
          $out="10";
          $row11['ob']=$ob;
          $row11['out']=$out;
    if($ob>=$out){
    $xx=1;    
    }
          
          $row['review'][]=$row11;
      }
     
      if($xx==1){
          
      }else{
          $arr['data2'][]=$row;
            ++$xm;
      }
      
}  
  
}

$q="select id from calling_status where cid='$user' and rid='$id' and close='1'";
$s90=mysqli_query($host,$q); 

$q="select id from calling_status where cid='$user' and rid='$id'  group by sid";
$s91=mysqli_query($host,$q); 

$arr['text1']=$xm;
$arr['text2']=mysqli_num_rows($s90);
$arr['text3']=mysqli_num_rows($s91)-mysqli_num_rows($s90);
$arr['text4']=$xm-mysqli_num_rows($s91);  
 
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