<?php
include("../db.php");
error_reporting(0);
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$standard=$obj['standard'];
$section=$obj['section'];

$examname=$obj['examname'];


$type='other';
$idate=$obj['idate'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select token from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
        $s3=mysqli_query($host,"select branch,id from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      
       $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
       $session=$d6['name'];
       
       
      
            $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    $examdata=array();
     
        
        
        if($examname){
            
             $s1=mysqli_query($host,"select name,cordinator,etype from exam where user='$branch' and name='$examname' and session='$session'");
        while($d1=mysqli_fetch_array($s1)){
            $sid=$d1['cordinator'];
            if($sid){
                $ss=mysqli_query($host,"select name from staff where id='$sid'");
                $dd=mysqli_fetch_array($ss);
                $name=$dd['name'];
            }else{
                $name="Not Alloted";
            }
            
            $data=$d1['name'].'*'.$name.'*'.$d1['etype'];
            array_push($examdata,$data);
            
            
            
            
        
        }
            
            
            
    $s=mysqli_query($host,"select * from staffaccess where cid='$branch' and staff='$staff' and feature='Result' group by class,section,subject");
    while($d=mysqli_fetch_array($s)){
      $standard=$d['class'];
      $subject=$d['subject'];
      $section=$d['section'];
      for($a=0; $a<count($examdata); $a++){   
          $mydata=explode("*",$examdata[$a]);
         $row['stream']=$d['stream'];
        $row['standard']=$d['class'];
         $row['subject']=$d['subject'];
          $row['section']=$d['section'];
          $exam=$mydata[0];
          $row['exam']=$mydata[0];
          $row['cordinator']=$mydata[1];
          $row['examtype']=$mydata[2];
          $row['upload']=array();
          $s1=mysqli_query($host,"select overall,oral,practical,theory,sectional from subject_with_exam_marks where cid='$branch' and exam='$exam' and standard='$standard' and subject='$subject'");
          $d1=mysqli_fetch_array($s1);
          if($d1[0]){
              $row['overall']=$d1['overall'];
              $row['oral']=$d1['oral'];
              $row['practical']=$d1['practical'];
              $row['theory']=$d1['theory'];
              $row['sectional']=$d1['sectional'];
               
          }
          $q="select id from students where standard='$standard' and cid='$branch' and (status='Active' or status IS NULL or status='') and session='$session'";
          if($section){
              $q.=" and batch='$section'";
          }
          $s4=mysqli_query($host,$q);
          $row['students']=mysqli_num_rows($s4); 
          $s2=mysqli_query($host,"select count(id),sum(if(oral>0 or oral='ab' or oral='nc',1,0)) as oral,sum(if(practical>0 or practical='ab' or practical='nc',1,0)) as practical,sum(if(theory>0 or theory='ab' or theory='nc',1,0)) as theory,sum(if(sectional>0 or sectional='ab' or sectional='nc',1,0)) as sectional from uploadresult where exam='$exam' and standard='$standard' and subject='$subject' and cid='$branch'");
          $d2=mysqli_fetch_array($s2); 
          $row['overallupload']=$d2[0]==""?0:$d2[0];
          $row['oralupload']=$d2['oral']==""?0:$d2['oral'];
          $row['practicalupload']=$d2['practical']==""?0:$d2['practical'];
          $row['theoryupload']=$d2['theory']==""?0:$d2['theory'];
          $row['sectionalupload']=$d2['sectional']==""?0:$d2['sectional'];
           if($d1[0]){
          $arr['data'][]=$row;
           }
      }
          
        
    }
        }else{
            
            
             $s1=mysqli_query($host,"select name,cordinator,etype from exam where user='$branch' and (status='Active' or status IS NULL or status='') and todate>='$date' and session='$session' group by name");
        while($d1=mysqli_fetch_array($s1)){
            $sid=$d1['cordinator'];
            if($sid){
                $ss=mysqli_query($host,"select name from staff where id='$sid'");
                $dd=mysqli_fetch_array($ss);
                $name=$dd['name'];
            }else{
                $name="Not Alloted";
            }
            
            $data=$d1['name'].'*'.$name.'*'.$d1['etype'];
            array_push($examdata,$data);
            $row['exam']=$d1['name'];
             $arr['data'][]=$row;
        
        }
            
        }
         
    
      
        
    }else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}

echo $json = json_encode($arr);


}else
{
    
    ?>
    <script>
        window.location="http://edpluss.com";        
    </script>
    <?php
}
?>