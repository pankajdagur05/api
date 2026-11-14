<?php  
include("../db.php");
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
    $s=mysqli_query($host,"select id from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
        $s3=mysqli_query($host,"select id,branch from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
            $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
      if($standard){
          $leave=array();
          $absent=array();
$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
               $q="select name,fname,id from students 
            where cid='$branch' 
            and (status='active' or status IS NULL or status='')  
            and standard='$standard' 
            and session='$session'";
        
        $q2="select students.id from leave_application 
             INNER JOIN students ON students.id=leave_application.sid 
             where leave_application.fromdate<='$idate' 
             and leave_application.todate>='$idate' 
             and leave_application.status='Accepted' 
             and students.cid='$branch' 
             and students.status<>'inactive' 
             and students.standard='$standard' 
             and students.session='$session'";
        
        $q3="select students.id from students 
             INNER JOIN attendance ON attendance.sid=students.id 
             where students.cid='$branch' 
             and attendance.idate='$idate' 
             and (attendance.status='a' or attendance.status='l') 
             and students.status<>'inactive' 
             and students.standard='$standard' 
             and students.session='$session'";
        
        
        // ✅ Batch Filter Sirf Tab Lagega Jab Section Selected Ho
        if (!empty($section)) {
        
            if (strtolower($section) != 'no section') {
                $q  .= " and batch='$section' ";
                $q2 .= " and students.batch='$section'";
                $q3 .= " and students.batch='$section'";
            } 
            else {
                // Agar "no section" choose kiya
                $q  .= " and (batch='' or batch IS NULL)";
                $q2 .= " and (students.batch='' or students.batch IS NULL)";
                $q3 .= " and (students.batch='' or students.batch IS NULL)";
            }
        
        }
        
        // ❌ Yaha koi else nahi hoga
        
        $q .= " order by name asc";
         
         // isset() based lookup (O(1)) - pehle array_search se O(n) tha,
         // aur array_search(0 index) ka falsy-zero bug bhi tha
         $s2=mysqli_query($host,$q2);
         while($d2=mysqli_fetch_array($s2)){
             $leave[$d2[0]]=true;
         }
         
          $s3=mysqli_query($host,$q3);
         while($d3=mysqli_fetch_array($s3)){
             $absent[$d3[0]]=true;
         }
        
$s4=mysqli_query($host,$q); 
         while($d4=mysqli_fetch_array($s4)){
             $sid=$d4['id'];
              
             if(isset($leave[$sid])){
                 $status='L';
             }else{
             if(isset($absent[$sid])){
             $status='A';
             }else{
               $status='P';  
             }
             }
             $row3['sid']=$d4['id'];
             $row3['name']=$d4['name'];
             $row3['fname']=$d4['fname'];
             $row3['status']=$status;
             
              $arr['data'][]=$row3;
              ++$r;
         }

 

          
      }else{
         
         // ✅ Ek hi query se saari class+section pairs nikal li (pehle 2 queries
         // thi: ek grouped by class list ke liye, doosri har class ke liye
         // alag se section list ke liye - N+1 tha)
         $s4=mysqli_query($host,"select class,section from staffaccess where cid='$branch' and staff='$staff' and (feature='All' or feature='Attendance') and type='class'");
         $classMap=array(); // class => [section_value => true] (dedupe, empty/null => 'No Section')
         while($d4=mysqli_fetch_array($s4)){
             $clas=$d4['class'];
             $secVal=$d4['section'];
             if(!isset($classMap[$clas])){
                 $classMap[$clas]=array();
             }
             $secKey=($secVal===null || $secVal==='')?'':$secVal;
             $classMap[$clas][$secKey]=true;
         }
         
         // ✅ Punch counts saari classes ke liye ek hi GROUP BY query se
         // (pehle: har class ke liye alag COUNT() query, jiska result
         // num_rows>0 se galat check ho raha tha - COUNT() hamesha 1 row deta hai)
         $punchCounts=array();
         $classesList=array_keys($classMap);
         if(count($classesList)>0){
             $classesEsc=array();
             foreach($classesList as $c){
                 $classesEsc[]="'".mysqli_real_escape_string($host,$c)."'";
             }
             $inClause=implode(',',$classesEsc);
             $qPunch="select students.standard as standard, count(attendance.id) as cnt from attendance INNER JOIN students ON students.id=attendance.sid where attendance.subdomain='$branch' and attendance.idate='$date' and students.standard IN ($inClause) and students.cid='$branch' group by students.standard";
             $sPunch=mysqli_query($host,$qPunch);
             while($dP=mysqli_fetch_array($sPunch)){
                 $punchCounts[$dP['standard']]=(int)$dP['cnt'];
             }
         }
         
         foreach($classMap as $clas=>$sectionsAssoc){
             $cnt=isset($punchCounts[$clas])?$punchCounts[$clas]:0;
             $row3['punch']=$cnt>0?$clas.' - Punched ':$clas.' - Not Punch ';
             $row3['class']=$clas;
             $row3['section']=array();
             foreach($sectionsAssoc as $secKey=>$_){
                 $row4['name']=$secKey===''?'No Section':$secKey;
                 $row3['section'][]=$row4;
             }
             $arr['data'][]=$row3;
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