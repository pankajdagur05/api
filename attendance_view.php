<?php
include("../db.php");


$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$remark=$_POST['remark'];
$idate=$_POST['todate'];
$reason=$_POST['reason'];
$sid=$_POST['sid'];
$date=date('Y-m-d');
$time=date('h:i:s');
$stnd=array();
$stnds=array();
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
          $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $cid=$branch;
        
          $dates=date('Y-m-d');   
      if($reason)
      {
        $s3=mysqli_query($host,"update attendance set reason='$reason',remark='$remark' where sid='$sid' and idate='$dates'");
        if($idate){
            $remark=$reason." - ".$remark;
        mysqli_query($host,"insert into leave_application set sid='$sid',type='student',subject='Attendendace Absent Call',description='$remark',reason='other',fromdate='$date',todate='$idate',status='Accepted',date='$date',cid='$cid'");
        }
      }
      
    
      
     $s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name']; 
      
      $arr['status']=true;
      $arr['message']='Success';
      $arr['data']=array();
      $arr['data2']=array();
    
     $tt.="";
      $tt.=" ( ";
      $p=0;
    $s4=mysqli_query($host,"select * from staffaccess where cid='$branch' and staff='$staff' and (feature='All' or feature='Attendance') and type='class'"); 
         $d41=mysqli_num_rows($s4);
         while($d4=mysqli_fetch_array($s4)){
             ++$p;  
            $standard=$d4['class']; 
            $section=$d4['section']; 
            if($section=="" or $section==NULL){ $sect=""; }else{ $sect=" students.batch='$section'";  }
            if($p==$d41)
            {
                if($sect){
                     $tt.=" (students.standard='$standard' and $sect) ) ";
                }else{
                 $tt.=" students.standard='$standard' ) ";  
                }
             
            }else
            {
                if($sect){
                    $tt.=" (students.standard='$standard' and $sect) or ";
                }else{
              $tt.=" students.standard='$standard' or "; 
                }
            }
            array_push($stnds,strtolower($standard));
         }
      
      
   
    
         $s4=mysqli_query($host,"select students.*,attendance.remark as remarks,attendance.status as st from students inner join attendance on students.id=attendance.sid where students.cid='$branch' and students.status<>'inactive' and $tt and attendance.idate='$dates' and attendance.remark IS NULL and ( attendance.status='L' or  attendance.status='A' ) and students.session='$session' group by students.id order by name asc"); 
         while($d4=mysqli_fetch_array($s4)){
              array_push($stnd,strtolower($d4['standard']));
              $sid=$d4['id'];
            $row3['sid']=$d4['id'];
            $s2=mysqli_query($host,"select * from docs where sid='$sid' and name='photo'");
            if(mysqli_num_rows($s2)>0){
            $d2=mysqli_fetch_array($s2);
            $row3['photo']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d2['filename'];
            }else{
              $row3['photo']='https://edpluss.com/school/admin-panel/cpanel/access/img/student-list.png';  
            }
            $row3['name']=$d4['name'];
            $row3['standard']=$d4['standard'];
            $row3['remark']=$d4['remarks'];
            $row3['mobile']=$d4['smobile'];
            $row3['status']=$d4['st'];
            $row3['num']=$t;
            $arr['data'][]=$row3;   
         }
         
         for($p=0;$p<count($stnds);$p++)
         {
             $k=0;
           for($pp=0;$pp<count($stnd);$pp++)
           {
               if($stnds[$p]==$stnd[$pp])
               {
                $k=1;   
               }
           }  
           if($k==0)
           {
            $row3['name']=$stnds[$p];  
            $arr['data2'][]=$row3;
           }
         }
         
          
          
         
        
         $arr['total']=$d41;
         $s444=mysqli_query($host,"select * from students inner join attendance on students.id=attendance.sid where students.cid='$branch' and students.status<>'inactive' and $tt and attendance.idate='$dates'  and students.session='$session' group by students.standard"); 
         $arr['attendance']=mysqli_num_rows($s444);
         $s44=mysqli_query($host,"select sum(case when attendance.status='P' then 1 else 0 end),sum(case when attendance.status='A' then 1 else 0 end),sum(case when attendance.status='L' then 1 else 0 end) from students inner join attendance on students.id=attendance.sid where students.cid='$branch' and students.status<>'inactive' and $tt and attendance.idate='$dates'  and students.session='$session'"); 
         $d444=mysqli_fetch_array($s44);
         $arr['present']=$d444[0];
         $arr['absent']=$d444[1];
         $arr['leave']=$d444[2];
          
          

        
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