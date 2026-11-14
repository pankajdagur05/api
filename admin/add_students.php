<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_POST['delete'];
$username=$_POST['userid'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token']; 
$date=date('Y-m-d');
$time=date('h:i:s');
$name=$_POST['name'];
$search=$_POST['search'];
$value=$_POST['value'];
 $branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
if($username){

    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
      
         
      $branch=$branchid;
      $user=$branch;
      $userby=$type;
      
      
        $s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];
      $sid=$d3['id'];
      if($name){
         $standard=$_POST['standard'];
         $section=$_POST['section'];
         $sr=$_POST['sr'];
         $doa=$_POST['doa'];
         $fname=$_POST['fname'];
         $mname=$_POST['mname'];
         $dob=$_POST['dob']==""?$date:$_POST['dob'];
         $caste=$_POST['caste'];
         $rel=$_POST['rel'];
         $gender=$_POST['gender'];
         $smobile=$_POST['smobile'];
         $gmobile=$_POST['gmobile'];
          $adhar=$_POST['adhar'];
           $hno=$_POST['hno'];
            $streat=$_POST['streat'];
             $land=$_POST['land'];
              $area=$_POST['area'];
                $city=$_POST['city'];
                  $dict=$_POST['dict'];
                  $pin=$_POST['pin'];
                  $address=$hno." ".$streat." ".$land." ".$area." ".$city." ".$dict." ".$pin; 
          
          $s9=mysqli_query($host,"select *,stream.name as str from standard INNER JOIN stream ON standard.type=stream.id where standard.cid='$branch' and standard.name='$standard' ");
          $d9=mysqli_fetch_array($s9);
          $stream=$d9['str'];
          
      if($_POST['update']){
          $id=$_POST['update'];
             mysqli_query($host,"update students set name='$name',batch='$section',stu_id='$sr',doa='$doa',gender='$gender',dob='$dob',adhar='$adhar',stream='$stream',standard='$standard',mname='$mname',fname='$fname',smobile='$smobile',gmobile='$gmobile',status='Active',hno='$hno',add1='$streat',add2='$land',area='$area',city='$city',dict='$dict',pin='$pin',caste='$caste',reli='$rel' where id='$id'");
           $arr['status']=true;
           $arr['message']="Successfully Updated";
          
      }else{
          $q="insert into students set name='$name',batch='$section',stu_id='$sr',doa='$doa',gender='$gender',dob='$dob',adhar='$adhar',stream='$stream',standard='$standard',mname='$mname',fname='$fname',smobile='$smobile',gmobile='$gmobile',status='Active',hno='$hno',add1='$streat',add2='$land',area='$area',city='$city',dict='$dict',pin='$pin',caste='$caste',reli='$rel',cid='$branch',session='$session',date='$date',time='$time'";
          mysqli_query($host,$q);
          $s0=mysqli_query($host,"select * from students where cid='$branch' order by id desc limit 1");
          $d0=mysqli_fetch_array($s0);
          $id=$d0['id'];
           $arr['status']=true;
           $arr['message']='success';
      }
      
     
      
      }else{
            $x=$_POST['x'];
            if($x==""){
                $x=0;
            }
        $start=25*$x;
         
       $q="SELECT * FROM staffaccess where staff='$username' and  (feature='Fees Manage' or feature='All')  group by class";   
          $arr['status']=true;
          $arr['message']="Success";
          $arr['access']=array();


 
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3))
{
    $row9['name']=$d3['class'];
    $row9['type']=$d3['type'];
    $arr['access'][]=$row9;
}
           
          if($value){
             
             if($search)
             {
                 $q="select * from students where cid='$branch' and session='$session' and status='Active' and  name like '$search%'";
             }
             else
             {
              $value=explode(",",$value);
            for($a=0; $a<count($value); $a++){
                $q="select * from students where cid='$branch' and session='$session' and (";
               $class=$value[$a];
               $q.="  standard='$class' or ";   
           }
           $q.=" standard='not') and status='Active'";
           
             }
           
           
          $arr['message']="Success";
           $arr['data']=array();
        
       $s1=mysqli_query($host,$q);  
        while($d1=mysqli_fetch_array($s1)){
            
            
            
            $sid=$d1['id'];
            
            $s3=mysqli_query($host,"select sum(if(voucher = 'due',debit,0)) as due,sum(if(voucher = 'receive',credit,0)) as rece from feesdue where sid='$sid' and cid='$branch' and cancel='No'");
             $d3=mysqli_fetch_array($s3);
            $row['id']=$d1['id'];
         $row['sr']=$d1['stu_id'];
         $row['rece']=$d3[1];
         $row['due']=$d3[0];
        $row['name']=$d1['name'];
         $row['doa']=$d1['doa'];
         $row['fname']=$d1['fname'];
         $row['mname']=$d1['mname'];
         $row['dob']=$d1['dob'];
         $row['caste']=$d1['caste'];
         $row['rel']=$d1['reli'];
         $row['gender']=$d1['gender'];
         $row['smobile']=$d1['smobile'];
         $row['gmobile']=$d1['gmobile'];
         $row['standard']=$d1['standard'];
         $row['section']=$d1['batch'];
          $row['adhar']=$d1['adhar'];
           $row['hno']=$d1['hno'];
            $row['streat']=$d1['add1'];
             $row['land']=$d1['add2'];
              $row['area']=$d1['area'];
                $row['city']=$d1['city'];
                  $row['dict']=$d1['dict'];
                  $row['pin']=$d1['pin'];
                  $row['photo']="";
                  $row['data']=array();
                  $s2=mysqli_query($host,"select * from docs where sid='$sid'");
                  if(mysqli_num_rows($s2)>0){
                  while($d2=mysqli_fetch_array($s2)){
                      $row2['name']=$d2['name'];
                       $row2['path']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d2['filename'];
                       if(strtolower($d2['name'])=='photo'){
                           $row['photo']="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$d2['filename'];;
                       }else{
                            $row['photo']=NULL;
                       }
                        $row['data'][]=$row2;
                  }
                  }else{
                       $row['photo']=NULL;
                  }
                  
$arr['data'][]=$row;
          
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