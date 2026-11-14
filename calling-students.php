<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$id=$obj['id'];
$sid=$obj['sid'];
$fstandard=$obj['fstandard'];
$sdd=$sid;
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');

if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select name,branch from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
       $branch=$d3['branch'];
       $user=$branch;
       $userby=$d3['name'];
       
 $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];

 
$arr['status']=true;
$arr['message']='success';  
$arr['data']=array();  
 $arr['data2']=array();  
 $arr['data3']=array();   
 $s0=mysqli_query($host,"select * from calling where id='$id'");
 $d0=mysqli_fetch_array($s0);
 if($d0['type']=='Finance'){
    $s01=mysqli_query($host,"select erp_category.id from erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type where erp_group.name='Balance B/D' and erp_group.cid='$user' and erp_category.cid='$user'"); 
$d01=mysqli_fetch_array($s01);
$cateid=$d01[0];
     
 }
$exam=array(); 
 $k=0;
   $qq="select val from calling_attachment where calling_attachment.cid='$user' and calling_attachment.type='exam' and calling_attachment.calling='$id'";
         $ss0=mysqli_query($host,$qq);
while($dd0=mysqli_fetch_array($ss0)){
 
 $exam[$k++]=trim($dd0['val']); 
    
}
  
 $q="select students.id as id ,students.name as name,students.standard as standard, students.fname as fname,staff.name as ssname,students.stream as stream,students.smobile as smobile,calling_students.staff_id as sid from students INNER JOIN calling_students INNER JOIN staff ON calling_students.stu_id=students.id and staff.id=calling_students.staff_id where students.status='Active' and students.cid='$user' and students.session='$session' and calling_students.rid='$id' ";
 if($sid){
     $q.=" and calling_students.staff_id='$sid' ";
 }

$q.=" and students.standard='$fstandard' order by students.name";
 
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
      
      if($d0['type']=='Finance')
      {
         
        if($d0['fodate']){
            $ff=explode(",",$d0['fodate']);
         $fdate=str_replace(' ', '', $ff[0]);
        $fdate=$fdate;
        }else{
        $fdate=$date;
        }
        
         $q="select sum(if(rdate<='$fdate',credit,0)),sum(if(rdate<='$fdate',debit,0)),sum(credit),sum(debit) from feesdue where cid='$user' and sid='$ssid' and cancel='No'";
        
        $qq=$q;
        if($cateid){
            $qq.=" and cateid='$cateid'";
            $s78=mysqli_query($host,$qq);
            $d78=mysqli_fetch_array($s78);
            if($d78[0]<$d78[1]){
               $pre=$d78[1]-$d78[0]; 
            }else{
                $pre=0;
            }
            
        }else{
            $pre=0;
        }
        
        $pop=0;
         $sss=mysqli_query($host,$q);
         $ddd=mysqli_fetch_array($sss);
         if($pre>0){
         $row['para1']="Privios Overdue : ".$pre;
         }
         if(($ddd[0]-$pre)>0){
          $row['para2']="Total Fees : ".$ddd[2].'/'.$ddd[3]; 
         }
           $row['para3']='Effected : '.date('d M Y',strtotime($d0['fodate']));
          $row11['ob']=$ddd[0];
          $row11['out']=$ddd[1];
          if($ddd[0]>=$ddd[1]){
              $xx=1;
          }else{
              $sss0=mysqli_query($host,"select id from fees_status where sid='$ssid' and idate>'$date'");
              if(mysqli_num_rows($sss0)>0){
                $pop=1;  
              }
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
              if($pop==1){
               $arr['data3'][]=$row;     
              }else{
               $arr['data'][]=$row;     
              }
              
          }
           
        ++$xm;
      }
      
}  



if($sdd=="" or $sdd==NULL){
  $q="select students.id as id ,students.name as name,students.standard as standard, students.fname as fname,students.stream as stream,students.smobile as smobile from students where students.status='Active' and students.cid='$user' and students.session='$session' and students.id NOT IN(select stu_id from calling_students where cid='$user' and rid='$id') ";
 $ss0=mysqli_query($host,"select val from calling_attachment where calling_attachment.cid='$user' and calling_attachment.type='standard' and calling_attachment.calling='$id'");

$q.=" and students.standard='$fstandard' order by students.name asc";

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
        
        $qq=$q;
        if($cateid){
            $qq.=" and cateid='$cateid'";
            $s78=mysqli_query($host,$qq);
            $d78=mysqli_fetch_array($s78);
            if($d78[0]<$d78[1]){
               $pre=$d78[1]-$d78[0]; 
            }else{
                $pre=0;
            }
            
        }else{
            $pre=0;
        }
        
        $pop=0;
         $sss=mysqli_query($host,$q);
         $ddd=mysqli_fetch_array($sss);
         if($pre>0){
         $row['para1']="Privios Overdue : ".$pre;
         }
          if(($ddd[0]-$pre)>0){
          $row['para2']="Currect Year Overdue : ".($ddd[0]-$pre);
          }
           $row['para3']='Effected : '.date('d M Y',strtotime($d0['fodate']));
          $row11['ob']=$ddd[0];
          $row11['out']=$ddd[1];
          if($ddd[0]>=$ddd[1]){
              $xx=1;
          }else{
              $sss0=mysqli_query($host,"select id from fees_status where sid='$ssid' and idate>'$date'");
              if(mysqli_num_rows($sss0)>0){
                $pop=1;  
              }
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