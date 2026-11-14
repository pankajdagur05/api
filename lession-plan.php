<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];  
$slot=$obj['slot']; 
$slotid=$obj['slotid'];
$dates=$obj['date'];
$close=$obj['close'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
$arr['date']='0';
$s3=mysqli_query($host,"select * from staff where id='$username'");
$d3=mysqli_fetch_array($s3);
$branch=$d3['branch'];
$user=$branch;
$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
$d6=mysqli_fetch_array($s6);
$session=$d6['name'];
$dt=array();
$tit=array();
$sstype=array();
if($slot){

            $arr['status']=true;
    $arr['message']='success'; 
    $arr['data']=array(); 
    $holidays = array();
 
         $s=mysqli_query($host,"select * from lession_slot where id='$slot' and cid='$user'");
         $d=mysqli_fetch_array($s);
         
          $s8=mysqli_query($host,"select * from lession_exam where cid='$user' and slotid='$slot'");
         while($d8=mysqli_fetch_array($s8)){
             array_push($dt,$d8['idate']);
             array_push($tit,$d8['title']);
             array_push($sstype,$d8['type']);
         }
         
    
    $start = strtotime($d['fromdate']);
$end = strtotime($d['todate']);
$notes = $d['notes1'];
$qa = $d['qa1'];
$task = $d['task1'];
$count = 0;
while(date('Y-m-d', $start) <= date('Y-m-d', $end)){
    $mydate=date('Y-m-d',$start);
  $count += date('N', $start) <=6 ? 1 : 0;
  $start = strtotime("+1 day", $start);
   $s0=mysqli_query($host,"select * from calender where fdate<='$mydate' and tdate>='$mydate' and cid='$user' and mode='holiday'");
    if(mysqli_num_rows($s0)>0){
    array_push($holidays,$mydate);    
    }
}
$days=$count-count($holidays);
$arr['days']=$days;
         
         $subject=$obj['subject'];
         $standard=$obj['standard'];
         
         
         
         
$s=mysqli_query($host,"select * from subject_link_with_class where stream='$standard' and name='$subject' and user='$user' order by num asc");  
while($d=mysqli_fetch_array($s)){ 
$subject=$d['name'];
$row['subject']=$d['name'];
$row['notes']=$notes;
$row['qa']=$qa;
$row['task']=$task;
$book=$d['book'];
$row['book']=array();
if($book){
$s1=mysqli_query($host,"select book.id as bid,book.book as bookname from publisher INNER JOIN book ON book.type=publisher.id where publisher.cid='$user' and publisher.name='$book' and book.standard='$standard' and book.subject='$subject'"); 
while($d1=mysqli_fetch_array($s1)){
$bid=$d1['bid'];    
$row2['name']=$d1['bookname'];
$row2['id']=$d1['bid'];
$row2['chapter']=array();

$s2=mysqli_query($host,"select * from chapter where cid='$user' and under='$bid' order by num asc");
while($d2=mysqli_fetch_array($s2)){
$row3['name']=$d2['title'];
$row3['marks']=$d2['marks'];
$row3['id']=$d2['id'];
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
$row4['done']=$vl;
$row4['idate']=$d90['idate']==NULL?NULL:date('d M Y',strtotime($d90['idate']));
$row4['days']=$d90['days'];
$row4['submitby']=$d90['submitby'];
$row3['topic'][]=$row4;
} 

    
    
}


$row2['chapter'][]=$row3;
    
}
 $row['book'][]=$row2;   
}
}
$arr['data'][]=$row;
}   
            
        
    
}else{    
    
 if($slotid){
     $days=explode("*",$obj['days']);
     $topicid=explode("*",$obj['topicid']);
    for($a=0; $a<count($topicid); $a++){
       $day=$days[$a];
       $topic=$topicid[$a];
        
      if($dates){
     
      $c=mysqli_query($host,"select id from lession_slot_plan where topic='$topic' and slotid='$slotid' and cid='$user' and idate IS NULL");  
      if(mysqli_num_rows($c)>0)
      {
      mysqli_query($host,"update lession_slot_plan set idate='$dates' where topic='$topic' and slotid='$slotid' and cid='$user'");   
      }
      }else{
       if($day){
            
       mysqli_query($host,"update lession_slot_plan set days='$day' where topic='$topic' and slotid='$slotid' and cid='$user'");
       }
       mysqli_query($host,"update lession_slot set status='approval' where id='$slotid' and cid='$user'");
      }
      
    }
    
    $arr['status']=true;
$arr['message']="update lession_slot_plan set idate='$dates' where topic='$topic' and slotid='$slotid' and cid='$user'";
 }else{
     
if($close){
$arr['status']=true;
$arr['message']='success';     
$notes=$obj['notes'];
$qa=$obj['qa'];
$task=$obj['task'];
mysqli_query($host,"update lession_slot set status='closed',notes='$notes',qa='$qa',task='$task' where id='$close'");    
}else{     
     
    
$arr['status']=true;
$arr['message']='success'; 
$arr['data']=array(); 
$s=mysqli_query($host,"select lession_slot.*,lession_standard.name as sname from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.cid='$user' and lession_slot.session='$session' and lession_slot.status<>'pending' and (lession_slot.staff='$username' or lession_slot.manager='$username') order by lession_slot.seq asc");
if(mysqli_num_rows($s)==0){
  

 
    $s0=mysqli_query($host,"select timetabledata.*,period.ftime as ptime,period.ttime as ttime from time_table INNER JOIN timetabledata INNER JOIN period ON timetabledata.timetable=time_table.id and period.timetable=time_table.id where timetabledata.cid='$user' and time_table.status='Active' and timetabledata.sid='$username' group by timetabledata.period order by period.ftime asc");    
    
       while($d0=mysqli_fetch_array($s0)){
           ++$xx;
           
           
         $date1 = "2024-08-01 ".$d0['ptime'];
$date2 = "2024-08-03 ".$d0['ttime'];

// Convert them to Unix timestamps
$timestamp1 = strtotime($date1);
$timestamp2 = strtotime($date2);

// Calculate the difference in seconds
$timeDifference = $timestamp2 - $timestamp1;
 
$hours = floor(($timeDifference % (60 * 60 * 24)) / (60 * 60));
$minutes = floor(($timeDifference % (60 * 60)) / 60); 
$minutes+=$hours*60;

$row['standard']=$d0['standard'];  
$row['subject']=$d0['subject'];  
$row['fromdate']=date('d-M-Y',strtotime($d0['ptime']));  
$row['todate']=date('d-M-Y',strtotime($d0['ttime'])); 
$row['seq']=$xx;  
$row['status']='Active'; 
$row['sts']="On Going"; 
$row['ptime']=$minutes;
$row['period']=$d0['period'];
$row['remark']="";
$row['notes']="";
$row['qa']="";
$row['task']="";
$row['id']=$d['id'];  
$row['topic']="";
$row['finish']="";
$row['date']=date('d-M-Y',time());
$arr['data'][]=$row;
           
       }
    
}else{
while($d=mysqli_fetch_array($s)){
if($d['fromdate']<=$date and $d['todate']>=$date){ $sts='On Going';
}else{ if($d['fromdate']>$date){ $sts='Upcoming';  }else{ $sts='Past';  } }   
$sid=$d['id'];    
$s1=mysqli_query($host,"select count(id),sum(if(length(idate)>0,1,0)) as finish from lession_slot_plan where slotid='$sid' and cid='$user' and session='$session'"); 
$d1=mysqli_fetch_array($s1);

$row['standard']=$d['sname'];  
$row['subject']=$d['subject'];  
$row['fromdate']=date('d-M-Y',strtotime($d['fromdate']));  
$row['todate']=date('d-M-Y',strtotime($d['todate'])); 
$row['seq']=$d['seq'];  
$row['status']=$d['status']; 
$row['sts']=$sts; 
$row['ptime']=$d['ptime']; 
$row['period']=$d['period']; 
$row['remark']=$d['remark'];
$row['notes']=$d['notes1'];
$row['qa']=$d['qa1'];
$row['task']=$d['task1'];
$row['id']=$d['id'];  
$row['topic']=$d1[0];  
$row['finish']=$d1[1];  
$row['date']=date('d-M-Y',strtotime($d['date']));
$arr['data'][]=$row;
}
}
}
}
}

}else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
    $arr['data']='';
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