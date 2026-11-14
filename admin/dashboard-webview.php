<?php error_reporting(4);

include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s');
$noticolor=array("A"=>'#dedbf0fc','F'=>'#dedbf0fc','G'=>'#faf2da','R'=>'#e1f5fe','T'=>'#f3dcf8','M'=>'#cdf0ed');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){

 $mm=mysqli_query($host,"select expire from admin_login where sub_domain='$subdomain'");
        $dd=mysqli_fetch_array($mm);
        if(strtotime($dd[0])>time()){

$arr['status']=true;
    $arr['message']='success';
    $arr['baseurl']="https://edpluss.com";
        $arr['supporturl']='https://edpluss.com';
    $arr['version']="1.2";
     $arr['message']="success";
   	 $arr['erp_report']=array();
		 $arr['lead']=array();
		 $arr['complaint']=array();
		 	 $arr['stream']=array();
		 	 $arr['staff']=array();
		 	 $arr['attendance']=array();
		 	 $arr['student']=array();
		 	 $arr['finance']=array();
		 	 $arr['stats']=array();
		 	  $arr['task']=array();
		 	   $arr['calender']=array();
		 	    $arr['access']=array();
		 	    $arr['account']=array();
		 	    
		 	    
		 	    
$arr['notifications']=array();
 if($type=='other'){
$ss=mysqli_query($host,"select * from notification where sub_domain='$subdomain' and type='staff' and sid='$userid' and date='$date' and  seen IS NULL order by id desc limit 4");
}else{
   $ss=mysqli_query($host,"select * from notification where sub_domain='$subdomain' and type='admin' and date='$date' and seen IS NULL  order by id desc limit 4"); 
}

  while($dd=mysqli_fetch_array($ss)){

$rows['title']=$dd['msg'];
$rows['mode']=$dd['mode'];
$rows['date']=date('d M Y h:i A',$dd['time']);
$rows['time']=$dd['time'];
$rows['seen']=date('d M Y h:i A',$dd['seen']);
if($noticolor[$dd['msg'][0]]){ $rows['color']=$noticolor[$dd['msg'][0]]; }else{ $rows['color']='#d6e4f1'; }
$arr['notifications'][]=$rows;

}
		 	    
		 	   $s=mysqli_query($host,"select photo from staff where branch='$user' and id='$userid'");
		 	   $d=mysqli_fetch_array($s);
		 	    $row8['photo']=$d['photo']==""?"":"https://edpluss.com/school/admin-panel/cpanel/upload/profile/".$d['photo'];
		 	    
 $s01=mysqli_query($host,"select * from branch_category where id='$user' and (status='Active' or status IS NULL)");
		           $d8=mysqli_fetch_array($s01);
		            $row8['facebook']=$d8['fb'];
		             $row8['lat']=$d8['lat'];
		            $row8['logo']="https://edpluss.com/school/admin-panel/cpanel/logo/".$d8['id'].".png";
		              $row8['lon']=$d8['lon'];
		               $row8['meter']=$d8['meter'];
		            $row8['branchname']=$d8['name'];
	                 $row8['instagram']=$d8['insta'];
		            $row8['youtube']=$d8['yt'];
		           $row8['twitter']=$d8['tw'];
		           $row8['bwebsite']=$d8['website'];
		           $row8['blat']=$d8['blat'];
		           $row8['blon']=$d8['blon'];
		           $row8['bnumber']=$d8['mobile'];
		           $row8['bwnumber']=$d8['amobile'];
		           $row8['bemail']=$d8['email'];
		            $row8['baddress']=$d8['address'];
		            $arr['account'][]=$row8;		 	    
		 	 if($type=='other'){
		 	  
		 	     $s=mysqli_query($host,"select features.*,staffaccessadmin.edited from staffaccessadmin INNER JOIN features ON features.name=staffaccessadmin.name where staffaccessadmin.sid='$userid'");
while($d=mysqli_fetch_array($s)){
    $row0['name']=$d['name'];
    $row0['edit']=$d['edited'];
    $row0['delete']=$d['dele'];
    $arr['access'][]=$row0;
    
}
		 	 }
$s=mysqli_query($host,"SELECT sum(if(status='active' or status IS NULL or status='',1,0)) as active,count(id),sum(if(admission='new' and status='active' and (rte='no' or rte IS NULL or rte=''),1,0)) as rte FROM `students` WHERE cid='$user' and session='$session' and tc_date IS NULL");	
$d=mysqli_fetch_array($s);
$s3=mysqli_query($host,"select * from students where session='$session' and cid='$user' and status='inactive' and length(tc_date)>0");  $r3=mysqli_num_rows($s3);
$tot=$d[1];

$stu['active']=$d[0];
$stu['inactive']=$d[1]-$d[0];
$stu['rte']=$d[2];
$stu['tc']=$r3;


   
$s=mysqli_query($host,"select * from stream where cid='$user' order by num asc"); while($d=mysqli_fetch_array($s)){ 
$gid=$d['id'];

$row8['stream']=$d['name'];
$row8['standard']=array();
$s2=mysqli_query($host,"select * from standard where cid='$user' and type='$gid' order by num asc"); $stnd=mysqli_num_rows($s2); while($d2=mysqli_fetch_array($s2)){ 
    
$row9['name']=$d2['name'];
$row8['standard'][]=$row9;
}

$arr['stream'][]=$row8;
}   


//attendance start
   
 
$s=mysqli_query($host,"select students.standard from attendance INNER JOIN students ON students.id=attendance.sid where students.cid='$user' and students.session='$session' and attendance.idate='$date' group by students.standard");  
$pclass=mysqli_num_rows($s);
$s1=mysqli_query($host,"select count(DISTINCT(standard.id)),count(students.id) from students INNER JOIN standard INNER JOIN stream ON standard.name=students.standard and stream.id=standard.type where standard.cid='$user' and (standard.status='active' or standard.status IS NULL ) and (standard.attendance='active' or standard.attendance IS NULL ) and students.cid='$user' and students.session='$session'");
$r1=mysqli_fetch_array($s1);
 
$s=mysqli_query($host,"select sum(if(status='p',1,0)) as p from attendance where idate='$date' and subdomain='$user'");   
$d=mysqli_fetch_array($s);
$atte['punch_stu']=$d[0]; 
$atte['punch_class']=$pclass;
$atte['total_stand']=$r1[0];
$stu['total']=$r1[1];
$s03=mysqli_query($host,"SELECT * FROM `staff` WHERE (consi IS NULL or consi='No') and branch='$user' and status='Active' order by name asc");
$r=mysqli_num_rows($s03);
while($d03=mysqli_fetch_array($s03)){
 $row17['id']=$d03['id'];
 $row17['name']=$d03['name'];
 $arr['staff'][]=$row17;
}
$atte['total_staff']=$r;
$s=mysqli_query($host,"select * from staff_attendance where subdomain='$user' and idate='$date' group by sid");
$atte['punch_staff']=mysqli_num_rows($s);
$arr['attendance'][]=$atte;

$arr['student'][]=$stu;

//attendance end


//finance start

	$s4=mysqli_query($host,"select sum(credit) from feesdue where cid='$user' and cancel='No' and session='$session' and rdate='$date'"); 
	$d4=mysqli_fetch_array($s4);
	$dpo=$d4[0];
$fee['deposit']=$d4[0]; 
	$s4=mysqli_query($host,"select rdate from feesdue where cid='$user' and cancel='No' and session='$session' and rdate<'$date' order by rdate desc limit 1"); 
	$d4=mysqli_fetch_array($s4);
	$dt=$d4['rdate'];
	$s4=mysqli_query($host,"select sum(credit) from feesdue where cid='$user' and cancel='No' and session='$session' and rdate='$dt'"); 
	$d4=mysqli_fetch_array($s4);
	if($d4[0]=="" || $dpo=="" || $d4[0]=="0" || $dpo=="0")
	{
$fee['last_deposit']=0;	    
	}else
	{
$fee['last_deposit']=round(($d4[0]*100)/$dpo); 
}
	$s4=mysqli_query($host,"select sum(amount) from accounts where cid='$user' and side='debit' and mode<>'due' and  idate='$date' "); 
	$d4=mysqli_fetch_array($s4);
	$texp=$d4[0];
$fee['today_expenses']=$d4[0];

	$s4=mysqli_query($host,"select idate from accounts where cid='$user' and side='debit' and mode<>'due' and idate<'$date' order by idate desc limit 1"); 
	$d4=mysqli_fetch_array($s4);
	$dt=$d4['idate'];
	$s4=mysqli_query($host,"select sum(amount) from accounts where cid='$user' and side='debit' and mode<>'due' and  idate='$dt' "); 
	$d4=mysqli_fetch_array($s4);
		if($d4[0]=="" || $texp=="" || $d4[0]=="0" || $texp=="0")
	{
$fee['last_expenses']=0;	    
	}else
	{
$fee['last_expenses']=round(($d4[0]*100)/$texp); 
}

	$s4=mysqli_query($host,"select count(id) from fees_status where cid='$user' and date='$date'"); 
	$d4=mysqli_fetch_array($s4);
	$trem=$d4[0];
$fee['today_remark']=$d4[0];
	$s4=mysqli_query($host,"select date from fees_status where cid='$user' and date<'$date' order by date desc limit 1"); 
	$d4=mysqli_fetch_array($s4);
	$dt=$d4['idate'];
	$s4=mysqli_query($host,"select count(id) from fees_status where cid='$user' and date='$dt'"); 
	$d4=mysqli_fetch_array($s4);
		if($d4[0]=="" || $trem=="" || $d4[0]=="0" || $trem=="0")
	{
$fee['last_remark']=0;	    
	}else
	{
$fee['last_remark']=round(($d4[0]*100)/$trem); 
}
$arr['finance'][]=$fee;

// finance end


 
//this is old apk work start 
	$s4=mysqli_query($host,"select sum(debit),sum(credit) from feesdue where cid='$user' and cancel='No' and session='$session'");
	$d4=mysqli_fetch_array($s4);
$q="select sum(debit),sum(credit) from feesdue where cid='$user' and rdate<='$date' and cancel='No' and session='$session' ";
	$s5=mysqli_query($host,$q);
	$d5=mysqli_fetch_array($s5);
	$balance=$d4[0]-$d4[1];
	$over=$d5[0]-$d5[1];
$row6['totaldue']=$d4[0];
$row6['totalrecevied']=$d4[1];
$row6['overdue']=$over;
$row6['balance']=$balance;
$row6['overpayment']="0";
$arr['erp_report'][]=$row6;
// old apk work finish


$s=mysqli_query($host,"SELECT COUNT(id),sum(if((checkout='' or checkout IS NULL),1,0)) FROM `gatepass_data` WHERE cid='$user' and date='$date'");
$d=mysqli_fetch_array($s);
$stats['gatepass1']=$d[0];
$stats['gatepass2']=$d[1];
$stats['gatepass3']=$d[0]-$d[1];
$s=mysqli_query($host,"SELECT sum(if(etype='minor',1,0)) as minor,sum(if(etype='major',1,0)) as major FROM exam where user='$user' and name IN (select exam from uploadresult where cid='$user')");
$d=mysqli_fetch_array($s);
$stats['minor']=$d[0];
$stats['major']=$d[1];
$s=mysqli_query($host,"SELECT  id FROM notes where cid='$user' and type='classtest'");
$stats['classtest']=mysqli_num_rows($s);
$s=mysqli_query($host,"SELECT count(id),sum(if(lead_convert='yes',1,0)) as lead,sum(if(lead_close='yes',1,0)) as close FROM `lead` WHERE branch='$user'");
$d=mysqli_fetch_array($s);
$stats['lead1']=$d[0];
$stats['lead2']=$d[2];
$stats['lead3']=$d[1];




$cc = "select * from complaint where user='$user' and ( mode='complaint' or mode='complain') and archive is NULL ";
	$cq = mysqli_query($host,$cc);
 $stats['appeal1']=mysqli_num_rows($cq);
 $cc1 = "SELECT * FROM complaint INNER JOIN complaint_status2 ON complaint.id = complaint_status2.comp_id where complaint_status2.upd_sts='complete' and complaint.user='$user' and ( complaint.mode='complaint' or complaint.mode='complain') and complaint.archive is NULL ";

	$cq1 = mysqli_query($host,$cc1);
	$rr2 = mysqli_num_rows($cq1);

 $stats['appeal2']=$rr2;
 
 $stats['appeal3']=mysqli_num_rows($cq)-$rr2;
 
$s=mysqli_query($host,"select * from task where status='ongoing' and user='$user'");
$stats['task']=mysqli_num_rows($s);
$arr['stats'][]=$stats;

 $s=mysqli_query($host,"select * from calender where cid='$user' and fdate>='$date' order by fdate desc limit 3");
while($d=mysqli_fetch_array($s)){
$cal['name']=$d['title'];    
$cal['fdate']=date('d M Y',strtotime($d['fdate']));
$cal['tdate']=$d['tdate']==NULL?"":date('d M Y',strtotime($d['tdate']));
$cal['mode']=$d['mode'];  
$arr['calender'][]=$cal;
    
}

$s03=mysqli_query($host,"SELECT count(*),leadstatus FROM `lead` WHERE sub_domain='$subdomain' and branch='$bid' group by leadstatus");
while($d03=mysqli_fetch_array($s03)){
 $row7['label']=$d03[1];
 $row7['num']=$d03[0];
 $arr['lead'][]=$row7;
}



$row7['name']="Pending";
 $arr['complainstatus'][]=$row7;
$row7['label']="Pending";
 $arr['complaint'][]=$row7;
 
  $row7['name']="Reject";
 $arr['complainstatus'][]=$row7;
  $row7['label']="Reject";
 $arr['complaint'][]=$row7;
 
  $row7['name']="Complete";
 $arr['complainstatus'][]=$row7;
  $row7['label']="Complete";
 $arr['complaint'][]=$row7;
 
$s03=mysqli_query($host,"SELECT count(*),complaintstatus FROM `complaint` WHERE sub_domain='$subdomain' and branch='$bid' group by complaintstatus");
while($d03=mysqli_fetch_array($s03)){
 $row7['name']=$d03[1];
 $row7['color']=$d03[0];
 $arr['complainstatus'][]=$row7;
}



$s03=mysqli_query($host,"SELECT count(*),complaintstatus FROM `complaint` WHERE sub_domain='$subdomain' and branch='$bid' group by complaintstatus");
while($d03=mysqli_fetch_array($s03)){
 $row7['label']=$d03[1];
 $row7['num']=$d03[0];
 $arr['complaint'][]=$row7;
}


 $row=array();
 
           $row['name']="Staff Attendance";
           $row['group']="Attendance";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/staff-attendance.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
       $row['name']="Teacher Attendance";
           $row['group']="Attendance";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/staff-attendance.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
       
       
       $row['name']="Day Book";
           $row['group']="Finance Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/day-book.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
        $row['name']="OverDue Payments";
           $row['group']="Finance Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/overduee.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
       
          $row['name']="ClassWise Due Report";
           $row['group']="Finance Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/classwisefeesreportt.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
      $row['name']="Fee D. Class Wise ";
           $row['group']="Finance Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/feedetails.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
      
       $row['name']="Day Book";
           $row['group']="Transport Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/day-book.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
        $row['name']="OverDue Payments";
           $row['group']="Transport Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/overduee.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
       
          $row['name']="ClassWise Due Report";
           $row['group']="Transport Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/classwisefeesreportt.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
      $row['name']="Fee D. Class Wise ";
           $row['group']="Transport Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/feedetails.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
      
       $row['name']="Fee D. Class Wise ";
           $row['group']="Transport Report";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/school/admin-panel/api/admin/webview/feedetails.php?subdomain=".$subdomain."&branchid=".$branchid."&token=".$token."&userid=".$userid."&type=".$type."&session=".$session;
      $arr['features'][]=$row;
        
        


}else{
  $arr['status']=false;
    $arr['message']='Plan Expire Please Cordinate Admin';  
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