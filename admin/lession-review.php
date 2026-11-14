<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$slotno=$_POST['slotno'];
$close=$_POST['close'];
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
$slot=$_POST['slot'];
$subject=$_POST['subject'];
$standard=$_POST['standard'];
$most=$_POST['most'];
$dates=$_POST['date'];
$dt=array();
$tit=array();
$sstype=array();
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token' and type='$type' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
        if($slot){
            $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array(); 
    
         $s=mysqli_query($host,"select * from lession_slot where id='$slot' and cid='$user'");
         $d=mysqli_fetch_array($s);
         
         $s8=mysqli_query($host,"select * from lession_exam where cid='$user' and slotid='$slot'");
         while($d8=mysqli_fetch_array($s8)){
             array_push($dt,$d8['idate']);
             array_push($tit,$d8['title']);
             array_push($sstype,$d8['type']);
         }
         
         
            
$s=mysqli_query($host,"select * from subject_link_with_class where stream='$standard' and name='$subject' and user='$user' order by num asc");  
while($d=mysqli_fetch_array($s)){ 
$subject=$d['name'];
$row['subject']=$d['name'];
$row['standard']=$standard;
$book=$d['book'];
$row['book']=array();
if($book){
$s1=mysqli_query($host,"select book.id as bid,book.book as bookname from publisher INNER JOIN book ON book.type=publisher.id where publisher.cid='$user' and publisher.name='$book' and book.standard='$standard' and book.subject='$subject'"); 
while($d1=mysqli_fetch_array($s1)){
$bid=$d1['bid'];    
$row2['name']=$d1['bookname'];
$row2['id']=$d1['bid'];
$row2['chapter']=array();

$s2=mysqli_query($host,"select * from chapter where cid='$user'   and under='$bid' order by num asc");
while($d2=mysqli_fetch_array($s2)){
$under=$d2['id'];    
$row3['name']=$d2['title'];
$row3['marks']=$d2['marks'];
$row3['id']=$d2['id'];
if($most==1){
$row3['upto']=date('d M Y',strtotime($d2['idate']));
$s09=mysqli_query($host,"select id from chapter where cid='$user' and under='$under' and type='topic'");
$r09=mysqli_num_rows($s09);
$s08=mysqli_query($host,"SELECT * FROM `chapter` RIGHT JOIN lession_slot_plan ON lession_slot_plan.topic=chapter.id WHERE under='$under' and type='topic' and chapter.cid='$user' GROUP by lession_slot_plan.topic order by lession_slot_plan.id desc");
$r08=mysqli_num_rows($s08);
 if($r08==$r09){
 $d08=mysqli_fetch_array($s08);
 $row3['complete']=date('d M Y',strtotime($d08['idate']));
                  
 }else{
     $row3['complete']="";
 }
 $date1 = strtotime($d2['idate']);
$date2 = strtotime($d08['idate']);
$diff = $date2 - $date1;
$days = floor($diff / (60 * 60 * 24));
if($days>0){
    $row3['diff']=$days;
}else{
     $row3['diff']="";
}

}


$row3['topic']=array();
$tid=$d2['id'];
$s3=mysqli_query($host,"select * from chapter where cid='$user' and under='$tid' order by num asc");
while($d3=mysqli_fetch_array($s3)){
    
if(count($dt)>0){
$stop_date=date('Y-m-d H:i:s', strtotime($d90['idate'] . ' +1 day'));
if(array_search($stop_date,$dt)){
$index=array_search($stop_date,$dt);
$row4['name']=$sstype[$index]." - ".$tit[$index]; 
$row4['id']=0; 
$row4['marks']=$d3['marks']; 
$row4['done']=1;
$row4['idate']=$stop_date;
$row4['days']=1;
$row4['submitby']=$d90['submitby'];

if($most==1){
  $under=$d3['id'];
                 $s09=mysqli_query($host,"select * from lession_slot_plan where cid='$user' and topic='$under' and length(idate)>0 order by id desc limit 1");
                 if(mysqli_num_rows($s09)==1){
                     $d09=mysqli_fetch_array($s09);
                     $row4['days']=$d09['days'];
                     $row4['cdate']=date('d M Y',strtotime($d09['idate']));
                     
                 }else{
                     $row4['days']="";
                     $row4['cdate']="";
                 }
}



$row3['topic'][]=$row4;
}
}

$stid=$d3['id'];
$row4['name']=$d3['title']; 
$row4['id']=$d3['id']; 
$row4['marks']=$d3['marks']; 
$s90=mysqli_query($host,"select * from lession_slot_plan where slotid='$slot' and topic='$stid' and cid='$user'"); 
if(mysqli_num_rows($s90)>0){
$vl="1";  
$d90=mysqli_fetch_array($s90);
}else{
$vl="0";    
}



$row4['done']=$vl;
$row4['idate']=$d90['idate'];
$row4['days']=$d90['days'];
$row4['submitby']=$d90['submitby'];
$row3['topic'][]=$row4;
    
}


$row2['chapter'][]=$row3;
    
}
 $row['book'][]=$row2;   
}
}
$arr['data'][]=$row;
}   
            
        }else{
            
            if($slotno){
            $remark=$_POST['remark'];
            if($remark){
            mysqli_query($host,"update lession_slot set status='Under Review',remark='$remark' where id='$slotno' and cid='$user'"); 
            }else{
                mysqli_query($host,"update lession_slot set status='Approved'  where id='$slotno' and cid='$user'"); 
            }
                $arr['status']=true;
    $arr['message']='success';  
                
            }else{
                
                 $slotno=$_POST['slotid2'];
                if($slotno){
                    $arr['status']=true;
                    $arr['message']='success'; 
                     $topicid=explode(",",$_POST['data']);
    for($a=0; $a<count($topicid); $a++){
       $day=$days[$a];
       $topic=$topicid[$a];
        
      if($dates){
     
      $c=mysqli_query($host,"select id from lession_slot_plan where topic='$topic' and slotid='$slotno' and cid='$user' and idate IS NULL");  
      if(mysqli_num_rows($c)>0)
      {
      mysqli_query($host,"update lession_slot_plan set idate='$dates' where topic='$topic' and slotid='$slotno' and cid='$user'");   
      }
      } 
      
    }
                
                }else{
                    
                    
                    if($close){
$arr['status']=true;
$arr['message']='success close';     
$notes=$_POST['notes'];
$qa=$_POST['qa'];
$task=$_POST['task'];
mysqli_query($host,"update lession_slot set status='closed',notes='$notes',qa='$qa',task='$task' where id='$close'");    
}else{ 
                
            $slotno=$_POST['slotid'];
            
            
            
            $data=explode(",",$_POST['data']);
            $data2=explode(",",$_POST['data2']);
            for($x=0; $x<count($data); $x++){
                $topic=$data[$x];
                if($topic){
                $days=$data2[$x];    
                if($days){
                    $stas='Approved';
                }else{
                    $stas='Under Review';
                }
               $q.="('$topic','$slotno','$user','$session','$days','$date')";
               if($x<(count($data)-1)){
                  $q.=","; 
               }
                }
            }
            if(mysqli_query($host,"insert into lession_slot_plan(topic,slotid,cid,session,days,date) VALUES $q")){
                mysqli_query($host,"update lession_slot set status='$stas' where id='$slotno' and cid='$user'");
                 $arr['status']=true;
    $arr['message']='success';
            }else{
                 $arr['status']=false; 
                 $arr['message']="insert into lession_slot_plan(topic,slotid,cid,session,days) VALUES $q"; 
            }
            
            
            }
        
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