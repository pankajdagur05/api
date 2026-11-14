<?php
include("../../db.php");  
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
$sid=$obj['sid'];
$weeks=array('Sunday'=>7,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6); 
$range=array(0,31,29,31,30,31,30,31,31,30,31,30,31);
$month=array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s1=mysqli_query($host,"select * from students where id='$sid'"); 
$d1=mysqli_fetch_array($s1);
$session=$d1['session'];
$branch=$d1['cid'];
$standard=$d1['standard'];
$user=$branch;
$arr['status']=true;
$arr['message']='success';
$arr['session']=array();
$arr['consolidate']=array();
$arr['scorepoint']=array();
$arr['attendance']=array();
$arr['finance']=array();
$arr['review']=array();
$arr['exam']=array();
$arr['complaint']=array();
     
$ss1=mysqli_query($host,"select * from scoresetting where cid='$user'");  $dd1=mysqli_fetch_array($ss1); 
$s=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p from attendance where sid='$sid' and subdomain='$user' ");
$d=mysqli_fetch_array($s);
if($d[0]=="" || $d[1]=="")
{
$per=0;
}else
{
 $per=round(($d[1]*100)/$d[0]);
} 
$row2['attendance_obtain']=$d[1];
$row2['attendance_outoff']=$d[0];
$row2['attendance_per']=$per;

$s=mysqli_query($host,"select sum(if(voucher = 'due',debit,0)) as due,sum(if(voucher = 'receive',credit,0)) as rece from feesdue where sid='$sid' and cid='$user' and cancel='No' ");
$d=mysqli_fetch_array($s);
if($d[0]==0 || $d[1]==0)
{
$per=0;
$finance0=0;
}else
{
 $per=round(($d[1]*100)/$d[0]);
$finance0=$per;   
}
$row2['finance_obtain']=$d[1];
$row2['finance_outoff']=$d[0];
$row2['finance_per']=$per;

$s=mysqli_query($host,"SELECT sum(review_answer.outoff),sum(review_answer.score) FROM `review_submit` INNER JOIN review_answer ON review_answer.reviewsubmit_id=review_submit.id WHERE review_submit.student_id='$sid' ");
$d=mysqli_fetch_array($s);
if($d[0]==0 || $d[0]==0)
{
    $per=0;
}else
{
$per=round(($d[1]*100)/$d[0]);
}
$row2['review_obtain']=$d[1];
$row2['review_outoff']=$d[0];
$row2['review_per']=$per;

$s=mysqli_query($host,"select sum(outoff),sum(obtain) from uploadresult where sid='$sid' and cid='$user'  ");
$d=mysqli_fetch_array($s);
if($d[0]==0 || $d[0]==0)
{
    $per=0;
}else
{
$per=round(($d[1]*100)/$d[0]);
}
$row2['exam_obtain']=$d[1];
$row2['exam_outoff']=$d[0];
$row2['exam_per']=$per;


$s=mysqli_query($host,"select sum(if(perm_comp.type = 'parents',1,0)) as parents,sum(if(perm_comp.type = 'student',1,0)) as student from perm_comp INNER JOIN complaint ON complaint.id=perm_comp.complain_id where perm_comp.user='$user' and perm_comp.sid='$sid' and complaint.archive IS NULL"); $d=mysqli_fetch_array($s);
$row2['byparents']=$d[0];
$row2['bystudent']=$d[0];
$s=mysqli_query($host,"select count(*) from complaint_resposible INNER JOIN complaint ON complaint.id=complaint_resposible.complaint_id where complaint_resposible.cid='$user' and complaint_resposible.sid='$sid' and complaint.archive IS NULL"); $d=mysqli_fetch_array($s);
$row2['forstudent']=$d[0];



$arr['consolidate'][]=$row2;


$q="select count(id),sum(if(status = 'P',1,0)) as p from attendance where subdomain='$user' and sid='$sid' "; if($dd1['attenfrom']){ $fr=$dd1['attenfrom']; $to=$dd1['attento']; $q.=" and date between '$fr' and '$to'"; } 
$s=mysqli_query($host,$q);
$d=mysqli_fetch_array($s);
if($d[0]==0 || $d[0]==0)
{
    $attendance0=0;
}else
{
$attendance0=round(($d[1]*100)/$d[0]);
}
$q="SELECT sum(uploadresult.obtain),sum(uploadresult.outoff) FROM `uploadresult`INNER JOIN exam ON exam.name=uploadresult.exam where uploadresult.sid='$sid' and uploadresult.cid='$user' and (exam.include='' or exam.include IS NULL or exam.include='yes')"; 
$s=mysqli_query($host,$q); 
$d=mysqli_fetch_array($s);
if($d[0]==0 || $d[0]==0)
{
   $exam0=0;
}else
{
$exam0=round(($d[0]*100)/$d[1]);  
}

$q="SELECT sum(review_answer.score),sum(review_answer.outoff) FROM `review_submit` INNER JOIN review_answer INNER JOIN create_review INNER JOIN qslot ON qslot.id=create_review.slot and create_review.id=review_submit.review_id and review_answer.reviewsubmit_id=review_submit.id where review_submit.student_id='$sid' and review_submit.cid='$user' and (qslot.include='Yes' or qslot.include='' or qslot.include IS NULL)"; 
$s=mysqli_query($host,$q);
$d=mysqli_fetch_array($s); 
if($d[0]==0 || $d[1]==0)
{
 $review0=0;   
}else
{
$review0=round(($d[0]*100)/$d[1]);  
}
if($attendance0>0){ $tot2+=($attendance0*$dd1['aw'])/100; $out+=$dd1['aw']; }
if($review0>0){  $tot2+=($review0*$dd1['rw'])/100; $out+=$dd1['rw']; }
if($finance0>0){  $tot2+=($finance0*$dd1['fw'])/100; $out+=$dd1['fw']; }
if($exam0>0){  $tot2+=($exam0*$dd1['ew'])/100; $out+=$dd1['ew']; }
if($tot2==0 || $out==0)
{
$tot3=0;
}else
{
$tot3=round(($tot2*100)/$out);  
}

$row3['scorepoint']=$tot3;
$row3['scoreoutoff']='100';
$row3['attendance_actual']=$attendance0; 
$row3['attendance_w']=$dd1['aw']==NULL?0:$dd1['aw'];


if($attendance0==0)
{
$row3['attendance_score']=0;     
}else
{
$row3['attendance_score']=($attendance0*$dd1['aw'])/100;    
}
 
$row3['finance_actual']=$finance0;
$row3['finance_w']=$dd1['fw']==NULL?0:$dd1['fw'];
if($finance0==0)
{
$row3['finance_score']=0;     
}else
{
$row3['finance_score']=($finance0*$dd1['fw'])/100;    
}

$row3['review_actual']=$review0;
$row3['review_w']=$dd1['rw']==NULL?0:$dd1['rw'];
if($finance0==0)
{
$row3['review_score']=0;     
}else
{
$row3['review_score']=($review0*$dd1['rw'])/100;    
}

$row3['exam_actual']=$exam0;
$row3['exam_w']=$dd1['ew']==NULL?0:$dd1['ew'];
if($exam0==0)
{
$row3['exam_score']=0;    
}else
{
$row3['exam_score']=($exam0*$dd1['ew'])/100; 
}

$arr['scorepoint'][]=$row3;
 


$s=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p,sum(if(status = 'A',1,0)) as ab,sum(if(status = 'L',1,0)) as lv from attendance where sid='$sid' and subdomain='$user' ");
$d=mysqli_fetch_array($s);
$per=round(($d[1]*100)/$d[0]);
if($sid2){
$s2=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p,sum(if(status = 'A',1,0)) as ab,sum(if(status = 'L',1,0)) as lv from attendance where sid='$sid2' and subdomain='$user' ");
$d2=mysqli_fetch_array($s2);
}
$row4['total_parsent']=$d['p'];
$row4['last_total_parsent']=$d1['p']>0?round(($d['p']*100)/$d1['p']):0;
$row4['total_absent']=$d['ab'];
$row4['last_total_absent']=$d1['ab']>0?round(($d['ab']*100)/$d1['ab']):0;
$row4['total_leave']=$d['lv'];
$row4['last_total_leave']=$d1['lv']>0?round(($d['lv']*100)/$d1['lv']):0;
$row4['monthwise']=array();

for($a=1; $a<=12; $a++){
    
$s=mysqli_query($host,"select count(*),sum(if(status = 'P',1,0)) as p,sum(if(status = 'A',1,0)) as ab,sum(if(status = 'L',1,0)) as lv,year(idate) as year from attendance where sid='$sid' and subdomain='$user' and month(idate)='$a' ");
$d=mysqli_fetch_array($s);
if($d[0]>0){
$m=$month[$a];    
$row5['month']=$a;
$row5['monthname']=$month[$a];
$row5['year']=$d['year'];
$row5['p']=$d['p'];
$row5['ab']=$d['ab'];
$row5['lv']=$d['lv'];
$row5['datewise']=array();
 

$q2="select status,idate from attendance where sid='$sid' and subdomain='$user' and month(date)='$a' order by date asc";
$s2=mysqli_query($host,$q2); 
while($d2=mysqli_fetch_array($s2)){
$row6['date']=date("d",strtotime($d2['idate'])); 
$row6['status']=$d2['status']; 
$row5['datewise'][]=$row6;
}
$row4['monthwise'][]=$row5;

} } 



$arr['attendance'][]=$row4;
 
$s=mysqli_query($host,"select sum(if(voucher = 'due',debit,0)) as due,sum(if(voucher = 'receive',credit,0)) as rece from feesdue where sid='$sid' and cid='$user' and cancel='No' ");
$d=mysqli_fetch_array($s);
$per=round(($d[1]*100)/$d[0]);
$row7['total_fees']=$d[0]>0?$d[0]:0;
$row7['deposit_fees']=$d[1]>0?$d[1]:0;
$row7['pending_fees']=$d[0]-$d[1];
$s=mysqli_query($host,"select sum(if(voucher = 'due',debit,0)) as due,sum(if(voucher = 'receive',credit,0)) as rece from feesdue where sid='$sid' and cid='$user' and cancel='No' and rdate<='$date' ");
$d=mysqli_fetch_array($s);
$over=$d[0]-$d[1];
$row7['overdue_fees']=$over>0?$over:0;
$row7['fees_ladger']=array();
$row7['group_fees']=array();
$row7['fees_extend']=array();
$row7['remark']=array();

$q2="select *,feesdue.id as idd,erp_group.name as gname,erp_category.name as cname from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.cid='$user' and feesdue.sid='$sid'  order by feesdue.rdate asc"; $s3=mysqli_query($host,$q2);  
while($d3=mysqli_fetch_array($s3)){
$row8['date']=date("d M Y", strtotime($d3['rdate'])); 
$row8['gname']=$d3['gname'];
$row8['cname']=$d3['cname'];
$row8['remark']=$d3['remark'];
$row8['cancel']=$d3['cancel'];
$row8['cancelremark']=$d3['cancelremark'];
$row8['credit']=$d3['credit'];
$row8['debit']=$d3['debit'];
$row7['fees_ladger'][]=$row8;
    
}

$q2="select *,sum(if(feesdue.voucher = 'due',debit,0)) as due,sum(if(feesdue.voucher = 'receive',credit,0)) as rece,feesdue.id as idd,erp_group.name as gname,erp_category.name as cname from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.cid='$user' and feesdue.sid='$sid' group by feesdue.cateid order by feesdue.rdate asc";
$s3=mysqli_query($host,$q2);
while($d3=mysqli_fetch_array($s3)){

$cateid=$d3['cateid'];
$q="select sum(if(voucher = 'due',debit,0)) as due,sum(if(voucher = 'receive',credit,0)) as rece from feesdue where sid='$sid' and cid='$user' and cancel='No' and rdate<='$date' and cateid='$cateid' ";
$s=mysqli_query($host,$q);
$d=mysqli_fetch_array($s);
$over=$d[0]-$d[1]; 
$row9['cname']=$d3['cname'];
$row9['due']=$d3['due'];
$row9['rece']=$d3['rece'];
$row9['over']=$over>0?$over:0;
$row7['group_fees'][]=$row9;

}

$s03=mysqli_query($host,"select * from fees_status where sid='$sid'");
while($d03=mysqli_fetch_array($s03)){
$row10['date']=date("d M Y", strtotime($d03['date']));  
$row10['uid']=$d03['uid']; 
$row10['type']=$d03['type']; 
$row10['idate']=date("d M Y", strtotime($d03['idate'])); 
$row10['remark']=$d03['remark']; 
$row7['fees_extend'][]=$row10;
}

$s04=mysqli_query($host,"select * from remark where sid='$sid'");
while($d04=mysqli_fetch_array($s04)){
if($d04['user']){
$uid=$d04['user'];
$s=mysqli_query($host,"select * from staff where id='$uid'");    
$d=mysqli_fetch_array($s);
$type=$d['name'];
}else{
    $type="Admin";
}

$row11['date']=date("d M Y", strtotime($d04['date']));
$row11['type']=$type;
$row11['remark']=$d04['remark'];
$row7['remark'][]=$row11;

}
 
$arr['finance'][]=$row7;



$s=mysqli_query($host,"SELECT qslot.name,sum(review_answer.outoff) as outoff,sum(review_answer.score) as score,review_submit.id as rid FROM `review_submit` INNER JOIN review_answer INNER JOIN create_review INNER JOIN qslot ON qslot.id=create_review.slot and create_review.id=review_submit.review_id and review_answer.reviewsubmit_id=review_submit.id where review_submit.student_id='$sid' and review_submit.cid='$user' GROUP by review_submit.review_id"); 
while($d=mysqli_fetch_array($s)){  
$row12['obtain']=$d[2];
$row12['outoff']=$d[1];
$row12['name']=$d[0];
$rid=$d['rid'];
$row12['table']=array();
$s2=mysqli_query($host,"SELECT * FROM `review_submit` INNER JOIN review_answer INNER JOIN review_question ON review_question.id=review_answer.qtn and review_answer.reviewsubmit_id=review_submit.id where review_submit.student_id='$sid' and review_submit.cid='$user' and review_submit.id='$rid'"); 
while($d2=mysqli_fetch_array($s2)){

$row13['qstn']=$d2['qstn'];
$row13['score']=$d2['score'];
$row13['outoff']=$d2['outoff'];
$row12['table'][]=$row13;

}
$arr['review'][]=$row12;
}



 $dt=array("local");
    $ss=mysqli_query($host,"select * from subject_link_with_class where type='Non Complosary' and stream='$standard' and user='$user'");
    while($dd=mysqli_fetch_array($ss)){
        
        array_push($dt,strtolower($dd['name'])); 
    }
    

$s=mysqli_query($host,"select sum(outoff),sum(obtain),exam from uploadresult where sid='$sid' and cid='$user' group by exam");  
while($d=mysqli_fetch_array($s)){ 
$exam=$d[2];


$dd1=0; 
$dd2=0;
$row14['table']=array();

$s1=mysqli_query($host,"select * from uploadresult where exam='$exam' and sid='$sid' and cid='$user' and length(obtain)>0 group by subject"); 
while($d1=mysqli_fetch_array($s1)){ 
$row15['subject']=$d1['subject'];
$row15['obtain']=$d1['obtain'];
$row15['outoff']=$d1['outoff'];
$row14['table'][]=$row15;  



if(array_search(strtolower($d1['subject']),$dt)==""){
    ++$xx;
}else{
    $dd1+=$d1['obtain'];
     $dd2+=$d1['outoff'];  
}

}


$row14['obtain']=$d[1]-$dd1;;    
$row14['outoff']=$d[0]-$dd2;
$row14['name']=$exam;
$arr['exam'][]=$row14;
}    

$s=mysqli_query($host,"select complaint.date as date,complaint.com_subject,complaint.com_desc,complaint.id,perm_comp.type as type from perm_comp INNER JOIN complaint ON complaint.id=perm_comp.complain_id where perm_comp.user='$user' and perm_comp.sid='$sid'   and  complaint.archive IS NULL"); while($d=mysqli_fetch_array($s)){ 
    
       $id=$d[3];
       
        $q="select * from complaint_status where comp_id='$id' order by id desc limit 1";
       $s1=mysqli_query($host,$q);
       $d1=mysqli_fetch_array($s1);
       if(mysqli_num_rows($s1)==0){
         $update="No Updates";  
         $udate=date("d/m/y", strtotime($d[0]));
         $ustatus="under review";
       }else{
          $update=$d1['sts_remark'];   
         $udate=date("d/m/y", strtotime($d1['update_date']));  
         $ustatus=$d1['upd_sts'];
       }

$row16['date']=date("d/m/y", strtotime($d[0])); 
$row16['subject']=$d[1];
$row16['desc']=$d[2];
$row16['update']=$update;
$row16['ustatus']=$ustatus;
$row16['type']=$d['type'];
$arr['complaint'][]=$row16;
       
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