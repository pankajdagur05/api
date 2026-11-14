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
            $arr['status']=true;
    $arr['message']='success';
    
     $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
       $session=$d6['name'];
       
       
      if($standard){
          $arr['datas']=array();
          $subject=$obj['subject'];
           $exam=$obj['exam'];

       
        $q="select id,name,fname from students where cid='$branch' and status<>'inactive' and standard='$standard' and session='$session' ";
        
        if($section){
            if(strtolower($section)!='no section'){
            $q.=" and batch='$section' ";
            }else{
                $q.=" and (batch='' or batch IS NULL)";
            }
        }
        $q.=" order by name asc";
        
         $s1=mysqli_query($host,"select overall,oral,practical,theory,sectional from subject_with_exam_marks where cid='$branch' and exam='$exam' and standard='$standard' and subject='$subject' and session='$session'");
          $d1=mysqli_fetch_array($s1);
          
$s4=mysqli_query($host,$q); 
         while($d4=mysqli_fetch_array($s4)){
             $sid=$d4['id'];
             $s5=mysqli_query($host,"select * from uploadresult where cid='$branch' and subject='$subject' and exam='$exam' and sid='$sid'");
             $d5=mysqli_fetch_array($s5);
             $row3['sid']=$d4['id'];
             $row3['name']=$d4['name'];
             $row3['fname']=$d4['fname'];
             $row3['marks']=$d5['obtain'];
              $row3['outoff']=$d1['overall'];
              $row3['oral']=$d5['oral'];
               $row3['outoff_oral']=$d1['oral'];
              
              $row3['practical']=$d5['practical'];
              $row3['outoff_practical']=$d1['practical'];
              $row3['theory']=$d5['theory'];
               $row3['outoff_theory']=$d1['theory'];
              $row3['sectional']=$d5['sectional'];
               $row3['outoff_sectional']=$d1['sectional'];
             
              $arr['datas'][]=$row3;
         }


          
      }else{
         $arr['data']=array();
          $arr['exam']=array();
          
          
         $s4=mysqli_query($host,"select class from staffaccess where cid='$branch' and staff='$staff' and (feature='All' or feature='Result') and type='class' group by class"); 
         while($d4=mysqli_fetch_array($s4)){
             $stream=$d4['class'];
             
             $row3['class']=$d4['class'];
             $row3['section']=array();
             
              $s41=mysqli_query($host,"select section from staffaccess where cid='$branch' and staff='$staff' and (feature='All' or feature='Result') and type='class' and class='$stream' group by section"); 
         while($d41=mysqli_fetch_array($s41)){
             $row5['name']=$d41['section']==""?'No Section':$d41['section'];
             $row3['section'][]=$row5;
         }
         
                $row3['subject']=array();
                
                $s0=mysqli_query($host,"select name from subject_link_with_class where user='$branch' and stream='$stream'");
                while($d0=mysqli_fetch_array($s0)){
                    $row4['name']=$d0['name'];
                    $row3['subject'][]=$row4;
                }
                
              $arr['data'][]=$row3;
              
              
         }
         
          $s4=mysqli_query($host,"select name from exam where user='$branch' and todate>='$date' and session='$session' order by id desc"); 
         while($d4=mysqli_fetch_array($s4)){
           
             $row5['name']=$d4['name'];
              
                
                
              $arr['exam'][]=$row5;
              
              
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