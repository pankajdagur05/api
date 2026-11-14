<?php
   include("../db.php");
   $json = file_get_contents('php://input');
   $obj = json_decode($json,true);
   $delete=$obj['delete'];
   $username=$obj['userid'];
   $subdomain=$obj['subdomain'];
   $token=$obj['token'];
   //$ver=$obj['ver'];
   $ver="4.0";
   $type='other';
   $date=date('Y-m-d');
   $time=date('h:i:s a');
   $noticolor=array("A"=>'#dedbf0fc','F'=>'#dedbf0fc','G'=>'#faf2da','R'=>'#e1f5fe','T'=>'#f3dcf8','M'=>'#cdf0ed');
   
      $file2 = 'api_log.txt';
   $current = file_get_contents($file2);
   $current.="\n dashboard_log_satrts \n"; 
   $current.=$obj['token']."\n"; 
   $current.=$json; 
   $current.="\n dashboard_log_ends"; 
   file_put_contents($file2, $current);
   
   if($username){
       $s=mysqli_query($host,"select id,last_visit from apk_token where token='$token' order by id desc");
       
   $current.="select id,last_visit from apk_token where token='$token' order by id desc"; 
   $current.="\n dashboard_log_ends"; 
   file_put_contents($file2, $current);
       
       $r=mysqli_num_rows($s);
       if($r==1){
           
           $mm=mysqli_query($host,"select expire from admin_login where sub_domain='$subdomain'");
           $dd=mysqli_fetch_array($mm);
           if(strtotime($dd[0])>time()){
           
         $s3=mysqli_query($host,"select branch,mobile,id,name,gender from staff where id='$username' and sub_domain='$subdomain'");
         $d3=mysqli_fetch_array($s3);
         $r_staff = mysqli_num_rows($s3);

            if ($r_staff == 1) {
                
         $branch=$d3['branch'];
         $user=$branch;
         $mobile=$d3['mobile'];
         mysqli_query($host,"update staff set ver='$ver' where id='$username'");
   mysqli_query($host,"update apk_token set last_visit='$date' where token='$token'");
   $d=mysqli_fetch_array($s);
   $tdate=$d['last_visit'];
   if($tdate==$date){
       
   }else{
       if(strtolower($d3['gender'])=='female'){
           $sir=' Mam';
       }else{
           if(strtolower($d3['gender'])=='male'){
             $sir=' Sir';
       } 
       }
       
   if(date('H')>=6 and date('H')<12){
   $voice="Good Morning ".$d3['name'].' '.$sir;    
   }else{
       if(date('H')>=12 and date('H')<17){
      $voice="Good Afternoon ".$d3['name'].' '.$sir; 
   }else{
       if(date('H')>=17 and date('H')<19){
      $voice="Good Evening ".$d3['name'].' '.$sir; 
   }else{
        $voice="Welcome ".$d3['name'].' '.$sir; 
   }    
   }    
       
   }
   }
    $arr['status']=true;
     $arr['location']=1;
     $arr['geotime']=2;
    
       $arr['version']="4.2";
       $arr['access']=array(); 
       $arr['lession']=array();
       
       
       $s0=mysqli_query($host,"select id,standard,subject from lession_slot where cid='$user' and staff='$username' and fromdate<='$date' and todate>='$date' and status='Approved'");
       if(mysqli_num_rows($s0)==0){
       
       $s0=mysqli_query($host,"select timetabledata.* from time_table INNER JOIN timetabledata INNER JOIN period ON timetabledata.timetable=time_table.id and period.timetable=time_table.id where timetabledata.cid='$user' and time_table.status='Active' and timetabledata.sid='$username' group by timetabledata.period order by period.ftime asc");    
       
          while($d0=mysqli_fetch_array($s0)){
           $id=$d0['id'];
           
           $rr['standard']=$d0['period'];
           $rr['subject']=$d0['standard'].' - '.$d0['subject'];
           $rr['id']=$d0['id'];
           $arr['lession'][]=$rr;
   
       }
       
       }else{
           
       while($d0=mysqli_fetch_array($s0)){
           $id=$d0['id'];
           $s9=mysqli_query($host,"select id from lession_slot_plan where cid='$user' and idate='$date' and slotid='$id'");
           if(mysqli_num_rows($s9)==0){
           $rr['standard']=$d0['standard'];
           $rr['subject']=$d0['subject'];
           $rr['id']=$d0['id'];
           $arr['lession'][]=$rr;
           }
       }
       }
   
        $arr['section']=array();
        
         $q="select name from batch where cid='$user'";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
      
       $row8['name']=$d3['name'];
       $arr['section'][]=$row8;
   }
         
         //////
         
         
         if($branch=="182")
         {
         $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1 OFFSET 1");
         }
        else
        {
         $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");    
         }
          $d6=mysqli_fetch_array($s6);
          $session=$d6['name'];
          
          
          $ss=mysqli_query($host,"select * from staff_attendance where subdomain='$branch' and status='P' and sid='$username' and idate='$date'");
          if(mysqli_num_rows($ss)==1){
           $dd=mysqli_fetch_array($ss);
           if(count(explode(",",$dd['time']))%2==0){
               $arr['myattend']=0; 
           }else{
            $arr['myattend']=1;    
           }
          }else{
             $arr['myattend']=0;  
          }
         
      
         $s=mysqli_query($host,"select feature from staffaccess where staff='$username' group by feature");
          $row0['name']='Calling Report';
       $arr['access'][]=$row0;
   while($d=mysqli_fetch_array($s)){
       $row0['name']=$d['feature'];
       $arr['access'][]=$row0;
   }
   
   $s2=mysqli_query($host,"select id from compaign_allot where cid='$branch' and sid='$username'");
   if(mysqli_num_rows($s2)>0){
   $row0['name']='Admission Inquery';
   $arr['access'][]=$row0;    
   }
   
   $arr['calender']=array();
    $s=mysqli_query($host,"select * from calender where cid='$user' and fdate>='$date' order by fdate asc limit 3");
   while($d=mysqli_fetch_array($s)){
   $now = time(); // or your date as well
   $your_date = strtotime($d['fdate']);
   $datediff = $your_date-$now;
   $days=round($datediff / (60 * 60 * 24));
   
   if($d['tdate']){
    $now = strtotime($d['tdate']);
   $your_date = strtotime($d['fdate']);
   $datediff = $now - $your_date;
   $gap=round($datediff / (60 * 60 * 24))+1;   
   }else{
     $gap="1";  
   }
   
   
   
   
   if($gap==0){ $mg="Today"; $color="#1ab69d"; }else{ if($gap==1){  $mg="Tommarow"; $color="#4d90fe";   }else{ if($gap<0){ $msg=""; }else{ if(date('Y-m-d')>=$d['fdate'] and date('Y-m-d')<=$d['tdate']){   $mg="On Going";  $color="#1ab69d";   }else{ $mg=($days)." Days Remaining";  $color="black";  } } }}       
   if($gap=="1"){
   $gap.=" Day";    
   }else{
   $gap.=" Days";     
   }
   $cal['subject']=$d['title'];
   $cal['descr']=$d['descr'];
   $cal['date']=date('d M Y',strtotime($d['date']));
   $cal['fdate']=date('d M Y',strtotime($d['fdate']));
   $cal['remaining']="<span style='color:".$color."'>".$mg."<span>";
   $cal['ffdate']=$d['fdate'];
   $cal['ttdate']=$d['tdate'];
   $cal['tdate']=$d['tdate']==$d['fdate']?"":date('d M Y',strtotime($d['tdate'])); 
   $cal['type']=$d['mode'];
   $cal['id']=$d['id'];
   $cal['tag']="Upcoming";
   $cal['gap']=$gap;
   $arr['calender'][]=$cal;
       
   }
   
   $arr['notifications']=array();
   $ss=mysqli_query($host,"select * from notification where sub_domain='$subdomain' and type='staff' and sid='$username' and date='$date' and seen IS NULL order by id desc limit 4");
     while($dd=mysqli_fetch_array($ss)){
   
   $rows['title']=$dd['msg'];
   $rows['mode']=$dd['mode'];
   $rows['date']=date('d M Y h:i A',$dd['time']);
   $rows['time']=$dd['time'];
   $rows['seen']=date('d M Y h:i A',$dd['seen']);
   if($noticolor[$dd['msg'][0]]){ $rows['color']=$noticolor[$dd['msg'][0]]; }else{ $rows['color']='#d6e4f1'; }
   $arr['notifications'][]=$rows;
   
       
       
    }
   
   
   $arr['attendances']=array();
   $row=array();
   
   $s=mysqli_query($host,"select class from staffaccess where staff='$username'  and (feature='All' or feature='attendance') limit 5");
   while($d=mysqli_fetch_array($s)){
   $standard=$d['class']; 
   $s3=mysqli_query($host,"select id from students  where session='$session' and cid='$user' and status='active' and standard='$standard'");
   
   
   $r2=mysqli_num_rows($s3);
   $s2=mysqli_query($host,"select sum(if(attendance.status='p',1,0)),sum(if(attendance.status='a',1,0)),sum(if(attendance.status='l',1,0)) from students inner join attendance on attendance.sid=students.id where students.session='$session' and students.cid='$user' and students.status='active' and students.standard='$standard' and attendance.idate='$date'");
   $d2=mysqli_fetch_array($s2);
   $row['standard']=$standard; 
   $row['present']=$d2[0];
   $row['absent']=$d2[1];
   $row['leave']=$d2[2];
   $row['student']=$r2; 
   $arr['attendances'][]=$row;
   
   }
   
   
   $arr['result']=array();
   $row=array();
   
   $s=mysqli_query($host,"SELECT timetabledata.standard as standard,timetabledata.subject as subject FROM timetabledata INNER JOIN time_table ON time_table.id=timetabledata.timetable where time_table.status='Active' and timetabledata.cid='$user' and timetabledata.sid='$username'  and time_table.access='Yes'");
   while($d=mysqli_fetch_array($s)){
   $standard=$d['standard'];
   $subject=$d['subject']; 
   if($subject){
   $s3=mysqli_query($host,"select count(students.id) from students  where students.session='$session' and students.cid='$user' and students.status='active' and standard='$standard'");
   $r2=mysqli_num_rows($s3);
   $q="select name from exam where user='$user' and etype='major' order by id desc limit 3";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
   $exam=$d3['name'];    
   $s2=mysqli_query($host,"select count(uploadresult.id) from students inner join uploadresult on uploadresult.sid=students.id where students.session='$session' and students.cid='$user' and students.status='active' and uploadresult.subject='$subject' and students.standard='$standard' and uploadresult.exam='$exam' group by uploadresult.sid");
    
   $row['standard']=$standard;
   $row['subject']=$subject;
   $row['upload']=mysqli_num_rows($s2);
   $row['student']=$r2;
   $row['exam']=$d3['name'];
   $arr['result'][]=$row;
   }
   }
   
   }
   
   $s=mysqli_query($host,"select class,subject from staffaccess where staff='$username'  and (feature='All' or feature='Exam Marks Upload' or feature='result')");
   while($d=mysqli_fetch_array($s)){
   $standard=$d['class'];
   $subject=$d['subject']; 
   if($subject){
       file_put_contents($file2, "select count(students.id) from students  where students.session='$session' and students.cid='$user' and students.status='active' and standard='$standard'");
   $s3=mysqli_query($host,"select count(students.id) from students  where students.session='$session' and students.cid='$user' and students.status='active' and standard='$standard'");
   
   $r2=mysqli_num_rows($s3);
   $q="select name from exam where user='$user' and etype='major' order by id desc limit 3";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
   $exam=$d3['name'];    
   $s2=mysqli_query($host,"select count(uploadresult.id) from students inner join uploadresult on uploadresult.sid=students.id where students.session='$session' and students.cid='$user' and students.status='active' and uploadresult.subject='$subject' and students.standard='$standard' and uploadresult.exam='$exam' group by uploadresult.sid");
    
   $row['standard']=$standard;
   $row['subject']=$subject;
   $row['upload']=mysqli_num_rows($s2);
   $row['student']=$r2;
   $row['exam']=$d3['name'];
   $arr['result'][]=$row;
   }
   }
   }
   
   $arr['message']='success';
   $arr['voice']=$voice;
   $arr['baseurl']="https://edpluss.com";
   
   $s=mysqli_query($host,"select id from calling_students where cid='$user' and staff_id='$username'");
   $arr['callings']=mysqli_num_rows($s);
   
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   
   $s = mysqli_query($host, "SELECT id FROM calling_status WHERE cid='$user' AND staff_id='$username' GROUP BY sid");
   
   if (!$s) {
       die("Query Failed: " . mysqli_error($host));
   }
   
   $arr['calling_done'] = mysqli_num_rows($s);
   
    $arr['feature1']="";
        $arr['feature2']="";
         $arr['feature3']="";
        $arr['feature4']="";
         $arr['feature1_url']="";
       $arr['feature2_url']="";
         $arr['feature3_url']="";
        $arr['feature4_url']="";
   
    $q="SELECT * FROM app_extras where cid='$user'";
   $s_extras=mysqli_query($host,$q);
   if(mysqli_num_rows($s_extras)>0)
   {
   $row_extras=mysqli_fetch_array($s_extras);
      
       $arr['feature1']=$row_extras['feature1'];
        $arr['feature2']=$row_extras['feature2'];
         $arr['feature3']=$row_extras['feature3'];
        $arr['feature4']=$row_extras['feature4'];
         $arr['feature1_url']=$row_extras['feature1_url'];
       $arr['feature2_url']=$row_extras['feature2_url'];
         $arr['feature3_url']=$row_extras['feature3_url'];
        $arr['feature4_url']=$row_extras['feature4_url'];
       
       
   }
   
   
   
   
         $s02=mysqli_query($host,"select * from branch_category where id='$branch' and sub_domain='$subdomain' and (status='Active' or status='active')");
         $d02=mysqli_fetch_array($s02);
               $arr['bname']=$d02['iname'];
               $arr['selected_branch']=$d02['name'];
                $arr['query']="select * from branch_category where id='$branch' and sub_domain='$subdomain' and (status='Active' or status='active')";
                 $arr['blogo']=$d02['logo']==""?"https://edpluss.com/school/admin-panel/cpanel/logo/24.png":"https://edpluss.com/school/admin-panel/cpanel/logo/".$d02['id'].".png";
                 $arr['baddress']=$d02['address'];
                 $arr['bemail']=$d02['email'];
                 $arr['bmobile']=$d02['mobile'];
                 $arr['lat']=$d02['lat'];
                 $arr['lon']=$d02['lon'];
                 $arr['meter']=$d02['meter'];
                // $arr['advance']=$d02['advance']==NULL?"Yes":$d02['advance'];
                $arr['advance']='No';
                $arr['login']=array();
        
        $s01=mysqli_query($host,"select admin_login.name,admin_login.sub_domain from staff INNER JOIN admin_login ON admin_login.sub_domain=staff.sub_domain where staff.status='Active' and staff.mobile='$mobile' group by admin_login.sub_domain");
        while($d01=mysqli_fetch_array($s01)){
            $ro['name']=$d01[0];
             $ro['subdomain']=$d01[1];
             $subd=$d01[1];
           
             $ro['data']=array();
             
             
              $s02=mysqli_query($host,"select branch_category.name,branch_category.id,staff.id,staff.mobile,staff.password from staff INNER JOIN branch_category ON branch_category.id=staff.branch where staff.mobile='$mobile' and staff.status='Active' and staff.sub_domain='$subd' and (branch_category.status='Active' or branch_category.status='active')");
             while($d02=mysqli_fetch_array($s02)){
                 $ro1['branch']=$d02[0];
                 $ro1['branchid']=$d02[1];
                  $ro1['id']=$d02[2];
                  $ro1['number']=$d02[3];
                  $ro1['pass']=$d02[4];
                  $ro['data'][]=$ro1;
             }
             
             $arr['login'][]=$ro;
             
        }
        
        
   
       $q="SELECT id FROM `lead` WHERE sub_domain='$subdomain' and type='other' and user='$username' and lead_close IS NULL";
        $s=mysqli_query($host,$q);
        $r1=mysqli_num_rows($s);
             $arr['lead']=$r1;
           
           
            /* 
          $ss=mysqli_query($host,"SELECT complaint.id FROM `complaint` INNER JOIN complaint_status ON complaint_status.comp_id=complaint.id WHERE complaint.sub_domain='$subdomain' and complaint.type='other' and complaint.user='$username' and complaint.upd_sts='pending'");
          $r2=mysqli_num_rows($ss);
           $arr['complaint']=$r2;
          
           $ss=mysqli_query($host,"select sum(if(type='notes',1,0)) as notes,sum(if(type='homework',1,0)) as homework,sum(if(type='classtest',1,0)) as classtest from notes where cid='$branch' and dele='0' and staffid='$username'");
          $r2=mysqli_fetch_array($ss);
           $arr['notes']=$r2[0];
           $arr['homework']=$r2[1];
           $arr['classtest']=$r2[2];
            */
           
           
   
           
           
           
           $ss=mysqli_query($host,"SELECT students.standard FROM `staffaccess` INNER JOIN students INNER JOIN attendance ON attendance.sid=students.id and students.standard=staffaccess.class where staffaccess.staff='$username' and (staffaccess.feature='All' or staffaccess.feature='Attendance') and staffaccess.cid='$branch' and attendance.idate='$date' group by students.standard");
          $r2=mysqli_num_rows($ss);
          
           $ss2=mysqli_query($host,"SELECT id FROM `staffaccess` where staffaccess.staff='$username' and (staffaccess.feature='All' or staffaccess.feature='Attendance') and staffaccess.cid='$branch'");
          $r3=mysqli_num_rows($ss2);
          
           $arr['attendance']=$r3-$r2;
           
             $s3=mysqli_query($host,"select * from staff where id='$username'");
             $d3=mysqli_fetch_array($s3);
             $sid=$d3['id'];
             
             $arr['task']=array();
             $s0=mysqli_query($host,"select task.* from task INNER JOIN taskmember ON task.id=taskmember.tid  where task.status='ongoing' and taskmember.sid='$username' and task.user='$branch'");
             while($d0=mysqli_fetch_array($s0)){
                 $row09['title']=$d0['title'];
                    $row09['descr']=$d0['descr'];
                       $row09['category']=$d0['category'];
                         $row09['fromdate']=date('d M Y',strtotime($d0['fromdate']));
                           $row09['todate']=date('d M Y',strtotime($d0['todate']));
                            $row09['date']=date('d M Y',strtotime($d0['date']));
                             $row09['type']=$d0['type'];
                             $arr['task'][]=$row09;
             }
   
        $arr['name']=$d3['name'];
         $arr['post']=$d3['post']==""?"-":$d3['post'];
          $arr['ca']=$d3['attendance']=="yes"?1:0;
        $arr['gender']=$d3['gender'];
        $arr['mobile']=$d3['mobile'];
        $arr['mail']=$d3['mail'];
        $arr['photo']=$d3['photo']==""?"http://mda.edpluss.com/admin-panel/cpanel/img/picm.png":"http://mda.edpluss.com/admin-panel/cpanel/upload/profile/".$d3['photo']; 
         $arr['erp_exp']=$d3['erp_exp'];
         $arr['calling']=$d3['calling'];
          $arr['paperslot']=$d3['paperslot'];
        $arr['filter']=array();
        $arr['fees-manage']=array();
        $arr['compaign']=array();
        $arr['stream']=array();
         $arr['zone']=array();
         $q="SELECT class,type FROM staffaccess where staff='$sid' and  (feature='fees' or feature='All')  group by class";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
      
       $row9['name']=$d3['class'];
       $row9['type']=$d3['type'];
       $arr['filter'][]=$row9;
   }
   
    $arr['feature1']="";
        $arr['feature2']="";
         $arr['feature3']="";
        $arr['feature4']="";
         $arr['feature1_url']="";
       $arr['feature2_url']="";
         $arr['feature3_url']="";
        $arr['feature4_url']="";
   
    $q="SELECT * FROM app_extras where sid='$sid'";
   $s_extras=mysqli_query($host,$q);
   if(mysqli_num_rows($s_extras)>0)
   {
   $row_extras=mysqli_fetch_array($s_extras);
      
       $arr['feature1']=$row_extras['feature1'];
        $arr['feature2']=$row_extras['feature2'];
         $arr['feature3']=$row_extras['feature3'];
        $arr['feature4']=$row_extras['feature4'];
         $arr['feature1_url']=$row_extras['feature1_url'];
       $arr['feature2_url']=$row_extras['feature2_url'];
         $arr['feature3_url']=$row_extras['feature3_url'];
        $arr['feature4_url']=$row_extras['feature4_url'];
       
       
   }
   
   
   
    $q="SELECT class,type FROM staffaccess where staff='$sid' and  (feature='Fees Manage' or feature='All')  group by class";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
      
       $row9['name']=$d3['class'];
       $row9['type']=$d3['type'];
       $arr['fees-manage'][]=$row9;
   }
   
   
   $q="SELECT id,name FROM stream where cid='$branch' order by num asc";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
      $ssid=$d3['id'];
       $row9['name']=$d3['name'];
       $row9['standard']=array();
        $q2="SELECT name FROM standard where cid='$branch' and type='$ssid' order by num asc";
   $s4=mysqli_query($host,$q2);
   while($d4=mysqli_fetch_array($s4)){
   $row10['name']=$d4['name'];   
   $row9['standard'][]=$row10;
      
   }
       $arr['stream'][]=$row9;
   }
   
   
   
   
    $q="SELECT compain.name as name,compain.id as id FROM compain inner join compaign_allot inner join staff on compain.name=compaign_allot.name and compaign_allot.sid=staff.id where compaign_allot.sid='$sid' and  compain.cid='$branch' group by compain.name order by compain.id desc";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
       $name=$d3['name'];
       $row3['name']=$d3['name'];
       $row3['id']=$d3['id'];
       $row3['data']=array();
       $s01=mysqli_query($host,"select name from lead_category where cid='$user' and comapign='$name'");
       while($d01=mysqli_fetch_array($s01)){
           $row4['name']=$d01['name'];
           $row3['data'][]=$row4;
       }
       $arr['compaign'][]=$row3;
   }
   
   
    $q="SELECT name,id FROM zone where cid='$branch' and (status IS NULL or status='' or status='Active') order by name asc";
   $s3=mysqli_query($host,$q);
   while($d3=mysqli_fetch_array($s3)){
      
       $row4['name']=$d3['name'];
       $row4['id']=$d3['id'];
       $arr['zone'][]=$row4;
   }
   
            } else {
                // AGAR STAFF NAHI MILA TOH YAHAN REDIRECT HOGA
                $arr['status'] = false;
                $arr['message'] = 'Staff record not found';
                echo json_encode($arr);
                ?>
                <script>window.location="http://edpluss.com";</script>
                <?php
                exit();
            }
   
   }else{
     $arr['status']=false;
       $arr['message']='Plan Expire Please Cordinate Admin';  
   }
   
   }else{
       $arr['status']=false;
       $arr['message']='Token Not Match';
   
   
   
   }
   
//   $file2 = 'log.txt';
//   $current = file_get_contents($file2);
//   $current.=  "\n".json_encode($arr);
//   file_put_contents($file2,  $current);
   echo $json = json_encode($arr);
   //echo $json = json_encode($arr);
   
   
   }else
   {
       
       ?>
<script>
   window.location="http://edpluss.com";        
</script>
<?php
   }
   ?>