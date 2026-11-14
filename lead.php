<?php error_reporting(0);
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_REQUEST['delete'];
$username=$_REQUEST['userid'];
$subdomain=$_REQUEST['sub_domain'];
$token=$_REQUEST['token'];
$camp=$_REQUEST['camp'];
$otype=$_REQUEST['type'];
$close=$_REQUEST['close'];
$typeid=$_REQUEST['typeid'];
$leadid=$_REQUEST['leadid'];
$pages=$_REQUEST['pages'];
$fsname=$_REQUEST['fsname'];
$fcate=$_REQUEST['fcate'];
$fzone=$_REQUEST['fzone'];
$fstatus=$_REQUEST['fstatus'];
$fmember=$_REQUEST['fmember'];
$ca=array();
$st=array();
if($otype==""){
$otype='Marketing';    
}
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    
     $max=100;
    $min=($pages-1)*100;
    $r=mysqli_num_rows($s);
    
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
       $user=$d3['branch'];
if($delete){
    mysqli_query($host,"update lead set archive='1' where id='$delete' and sub_domain='$subdomain'");
}else{
    
if($_REQUEST['sub_domain'] & $_REQUEST['sname']){  
$idd=$_REQUEST['id'];

$cat=$_REQUEST['cat'];
$status=$_REQUEST['status'];
$sname=$_REQUEST['sname'];
$fname=$_REQUEST['fname'];
$mname=$_REQUEST['mname'];
$source=$_REQUEST['source'];
$gender=$_REQUEST['gender'];
$mobile=$_REQUEST['mobile'];
$amobile=$_REQUEST['amobile'];
$sendtime=strtotime($_REQUEST['sendtime']);
$dob=$_REQUEST['dob'];
$email=$_REQUEST['email'];
$add=$_REQUEST['add'];
$add2=$_REQUEST['add2'];
$dict=$_REQUEST['dict'];
$city=$_REQUEST['city'];
$state=$_REQUEST['state'];
$pincode=$_REQUEST['pincode'];
$inter=$_REQUEST['inter'];
$academic=$_REQUEST['academic'];
$activity=$_REQUEST['activity'];
$finance=$_REQUEST['finance'];
$appoint=$_REQUEST['appoint'];

$pre=$_REQUEST['pre'];
$compaign=$_REQUEST['compaign']==""?'No Campaign':$_REQUEST['compaign'];
$zone=$_REQUEST['zone'];
$performance=$_REQUEST['performance'];
$insti=$_REQUEST['insti'];
$remark=$_REQUEST['remark'];
$lat=$_REQUEST['lat'];
$lon=$_REQUEST['lon'];
$date=date('Y-m-d');
$tim=date('h:i:s');
$time=time();
$unique=uniqid();
$path=$date.$time.$unique;

  
if($_FILES['uplodm0']['name']){
$file=$_FILES["uplodm0"]["tmp_name"];
$ex=$_FILES['uplodm0']['name'];
$extn=pathinfo($ex, PATHINFO_EXTENSION);
$attach=$path.".jpg";
$path2="../cpanel/upload/".$path.".jpg";

move_uploaded_file($file,$path2);	

}else
{
  $attach="";
}
if($_FILES['photo']['name']){
$file2=$_FILES["photo"]["tmp_name"];
$ex2=$_FILES['photo']['name'];
$extn2=pathinfo($ex2, PATHINFO_EXTENSION);	
$photo=$path.".jpg";
$path3="../cpanel/upload/profile/".$path.".jpg";
move_uploaded_file($file2,$path3);

}else
{
    $photo="";
}

if($idd!="0"){


  $p="update lead set sname='$sname',category='$cat',status='$status',cam='$compaign',zone='$zone',fname='$fname',mname='$mname',source='$source',mobile='$mobile',amobile='$amobile',gender='$gender',dob='$dob',mail='$email',add1='$add',add2='$add2',city='$city',dict='$dict',state='$state',pincode='$pincode',inter='$inter',academic='$academic',activity='$activity',finance='$finance',appoint='$appoint',performance='$performance',class='$pre',insti='$insti',remark='$remark',attach='$attach',leadstatus='$status',photo='$photo',date='$date',user='$username',type='$type',time='$time' where branch='$branch' and id='$idd' and sub_domain='$subdomain'";

	if(mysqli_query($host,"update lead set sname='$sname',cam='$compaign',zone='$zone',category='$cat',status='$status',source='$source',alert='0',sendtime='$sendtime',fname='$fname',mname='$mname',mobile='$mobile',amobile='$amobile',gender='$gender',dob='$dob',mail='$email',add1='$add',add2='$add2',city='$city',dict='$dict',state='$state',pincode='$pincode',inter='$inter',academic='$academic',activity='$activity',finance='$finance',appoint='$appoint',performance='$performance',class='$pre',insti='$insti',remark='$remark',attach='$attach',leadstatus='$status',photo='$photo',date='$date',user='$username',type='$type',time='$time' where branch='$branch' and id='$idd' and sub_domain='$subdomain'")){  $arr['message']=$p; }else{  $arr['message']=$p; }	
      

	
}else{
     $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
       $branch=$d3['branch'];
        $user=$d3['branch'];
      $subdomain=$d3['sub_domain'];
          $staffname=$d3['name'];
mysqli_query($host,"insert into lead set sname='$sname',category='$cat',cam='$compaign',zone='$zone',sendtime='$sendtime',source='$source',status='$status',fname='$fname',mname='$mname',mobile='$mobile',amobile='$amobile',gender='$gender',dob='$dob',mail='$email',add1='$add',add2='$add2',city='$city',dict='$dict',state='$state',pincode='$pincode',inter='$inter',academic='$academic',activity='$activity',leadstatus='$status',finance='$finance',appoint='$appoint',performance='$performance',class='$pre',insti='$insti',remark='$remark',attach='$attach',photo='$photo',date='$date',user='$username',type='$type',time='$time',branch='$branch',lat='$lat',lon='$lon',sub_domain='$subdomain'");	
$kk=mysqli_query($host,"select * from lead where branch='$branch' and sub_domain='$subdomain' order by id desc limit 1");
$kkk=mysqli_fetch_array($kk);
$kksks=$kkk['id'];
mysqli_query($host,"insert into complaint_status set comp_id='$kksks',update_date='$date',upd_sts='$source',sts_remark='$remark',user='$branch',staffid='$username',staffname='$staffname',date='$date',time='$tim' ");
mysqli_query($host,"update lead set status='$source' where id='$kksks'");
      $arr['message']='insert sucess';

}
}
}


if($leadid){
if($close==1){
 mysqli_query($host,"update lead set lead_close='yes',lead_closedate='$date' where id='$leadid'");  
    
}else{
    
if($close==3){
 mysqli_query($host,"update lead set lead_close='no',lead_closedate=NULL where id='$leadid'");  
    
}else{    
 $x='lead_convert';     
 mysqli_query($host,"update lead set lead_close='yes',lead_convert='yes',lead_closedate='$date' where id='$leadid'");  
}    
}
}

$arr['status']=true;
$arr['cate']=array();
$arr['sts']=array();
$arr['zone']=array();
$arr['member']=array();

   

    if($fcate){
             $fstatuss=explode(',',$fcate);
             $rk="";
             for($i=0;$i<count($fstatuss);$i++)
             {
                 $rkk=$fstatuss[$i];
                 if($i==0){ $rk.=" and ( lead.category='$rkk' "; if($i==count($fstatuss)-1){ $rk=" and lead.category='$rkk' "; } }else{ if($i==count($fstatuss)-1){ $rk.=" or lead.category='$rkk' ) "; }else{ $rk.=" or lead.category='$rkk' ";}}
                 
             }
            $filter=$rk;   
                } 
                
                  if($fzone){
             $fstatuss=explode(',',$fzone);
             $rk="";
             for($i=0;$i<count($fstatuss);$i++)
             {
                 $rkk=$fstatuss[$i];
                 if($i==0){ $rk.=" and ( lead.zone='$rkk' "; if($i==count($fstatuss)-1){ $rk=" and lead.zone='$rkk' "; } }else{ if($i==count($fstatuss)-1){ $rk.=" or lead.zone='$rkk' ) "; }else{ $rk.=" or lead.zone='$rkk' ";}}
                 
             }
            $filter=$rk;   
                } 
       
         if($fstatus){
             $fstatuss=explode(',',$fstatus);
             $rk="";
             for($i=0;$i<count($fstatuss);$i++)
             {
                 $rkk=$fstatuss[$i];
                 if($i==0){ $rk.=" and ( lead.status='$rkk' "; if($i==count($fstatuss)-1){ $rk=" and lead.status='$rkk' "; } }else{ if($i==count($fstatuss)-1){ $rk.=" or lead.status='$rkk' ) "; }else{ $rk.=" or lead.status='$rkk' ";}}
                 
             }
            $filter=$rk;   
                }  
       
       if($fsname){
          $filter=" and (lead.sname like '%$fsname%' or lead.fname like '%$fsname%' )";  
       }
       
if($otype=='Marketing'){
        $ss1=mysqli_query($host,"select hide from compaign_allot where type='$otype' and name='$camp' and sid='$username'");
          $dd1=mysqli_fetch_array($ss1);
           $hide=$dd1['hide'];
       $q="select lead.status,lead.sname,lead.id,lead.fname,lead.category,lead.date as ddate,lead.mobile,lead.cam,lead.zone,lead.amobile,lead.sendtime,lead.appoint,lead.sendtime,lead.lead_convert,lead.lead_close,id as lid from lead where type='other' and cam='$camp' and user='$username' and sub_domain='$subdomain' and archive IS NULL $filter order by id desc limit $min,$max"; 
       $q1="select lead.status,lead.sname,lead.id,lead.fname,lead.category,lead.mobile,lead.cam,lead.zone,lead.amobile,lead.sendtime,lead.appoint,lead.sendtime,lead.lead_convert,lead.lead_close,id as lid from lead where type='other' and cam='$camp' and user='$username' and sub_domain='$subdomain' and archive IS NULL $filter "; 
       
             $q7="select category,count(lead.id) from lead where type='other' and cam='$camp' and user='$username' and sub_domain='$subdomain' and archive IS NULL $filter group by category "; 
             $q8="select status,count(lead.id) from lead where type='other' and cam='$camp' and user='$username' and sub_domain='$subdomain' and archive IS NULL $filter  group by status "; 

       
       
       

       $ssss=mysqli_query($host,$q1);
         $num1=mysqli_num_rows($ssss);
         $num2=0;

    }else{
         if($otype=='Manager' or $otype=='Viewer'){
             
             
$ss0=mysqli_query($host,"select staff.name as sname,compaign_allot.sid as sid from compaign_allot INNER JOIN staff ON compaign_allot.sid=staff.id where compaign_allot.name='$camp' and compaign_allot.cid='$branch' and compaign_allot.type='TeleCaller' order by compaign_allot.id desc"); 
$row6['name']="All"; 
$row6['id']="0009"; 
$arr['member'][]=$row6;
while($dd0=mysqli_fetch_array($ss0)){
$row6['name']=$dd0['sname']; 
$row6['id']=$dd0['sid']; 
$arr['member'][]=$row6;
} 
              
     if($typeid=="1")
     { 
    if($fmember and $fmember!="0009")
    {
      $filter=" and lead_allot.sid='$fmember' ";  
    }
    
    
     $q="select lead.status,lead.sname,lead.id,lead.fname,lead.category,lead.mobile,lead.date as ddate,lead.cam,lead.zone,lead.amobile,lead.sendtime,lead.appoint,lead.sendtime,lead.lead_convert,lead.lead_close,lead.id as lid from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid where lead.branch='$user' and  lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter group by lead.id order by  lead.id desc limit $min,$max "; 
    
     $q7="select lead.category as pcategory,count(lead.id)  from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid  where lead.branch='$user' and lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter group by lead.category ";  
    $q8="select lead.status as pstatus,count(lead.id) from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid where lead.branch='$user' and lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter  group by lead.status ";  
    $q9="select lead.zone as zone,count(lead.id) from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid where lead.branch='$user' and  lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter  group by lead.zone ";  
     }
    else
    {  
         $q7="select lead.category as pcategory,count(lead.id)  from lead  where lead.branch='$user' and lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL and lead.id NOT IN(select lid from lead_allot) $filter group by lead.category ";  
    $q8="select lead.status as pstatus,count(lead.id) from lead  where lead.branch='$user' and lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL and lead.id NOT IN(select lid from lead_allot) $filter  group by lead.status ";  
    $q9="select lead.zone as zone,count(lead.id) from lead where lead.branch='$user' and  lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL and lead.id NOT IN(select lid from lead_allot) $filter  group by lead.zone ";  

     
     $q="select lead.status,lead.sname,lead.id,lead.fname,lead.category,lead.mobile,lead.date as ddate,lead.cam,lead.zone,lead.amobile,lead.sendtime,lead.appoint,lead.sendtime,lead.lead_convert,lead.lead_close,lead.id as lid from lead   where lead.id NOT IN (select lid from lead_allot group by lid) and  lead.branch='$user' and  lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter group by lead.id order by  lead.id desc limit $min,$max"; 
    
        
    }
    $q1="select count(lead.id) as total,sum(CASE WHEN lead.lead_close='yes' then 1 else 0 end) as total1,sum(CASE WHEN lead.lead_convert='yes' then 1 else 0 end) as total2 from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid where lead.branch='$user' and lead_allot.status='yes' and lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter ";  
    
    $q2="select id from lead where  cam='$camp' and sub_domain='$subdomain' and archive IS NULL and branch='$user' order by id desc";  
    $q3="select lead.id from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid where lead.branch='$user' and lead.appoint>'$date' and  lead.cam='$camp' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL $filter group by lead.id  order by lead.id desc";  

   
    
        $ssss=mysqli_query($host,$q1);
         $ssss2=mysqli_query($host,$q2);
          $ssss3=mysqli_query($host,$q3);
         $newarray=mysqli_fetch_array($ssss);
         $num1=$newarray['total'];
         $num3=$newarray['total1'];
         $num4=$newarray['total2'];
         $num5=mysqli_num_rows($ssss3);
          $num2=mysqli_num_rows($ssss2)-$num1;
         }else{
             
             if($typeid=="1"){ $typedata=" and lead.appoint<='$date'"; }else{ if($typeid=="2"){ $typedata=" and lead.appoint>'$date'"; }else{ $typedata="";  } }
           
      $q="select lead.status,lead.sname,lead.id,lead.fname,lead.category,lead.mobile,lead.cam,lead.zone,lead.amobile,lead.sendtime,lead.appoint,lead.sendtime,lead.lead_convert,lead.lead_close,lead.date as ddate,lead.status as status,lead.time as dtime,lead.id as lid from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$camp' $typedata and lead.sub_domain='$subdomain' and lead.archive IS NULL and lead_allot.status='yes' and lead_allot.sid='$username' $filter order by lead.id desc limit $min,$max";  
    $q1="select lead.id as lid from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$camp' and lead.appoint<='$date' and lead.sub_domain='$subdomain' and lead.archive IS NULL and lead_allot.status='yes' and lead_allot.sid='$username'  $filter";  
     $q2="select lead.id as lid from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$camp' and lead.appoint>'$date' and lead.sub_domain='$subdomain' and lead.archive IS NULL and lead_allot.status='yes' and lead_allot.sid='$username'  $filter order by lead.id desc";  

    $q7="select lead.category as pcategory,count(lead.id) from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$camp' and lead.appoint<='$date' and lead.sub_domain='$subdomain' and lead.archive IS NULL and lead_allot.status='yes' and lead_allot.sid='$username'  $filter group by lead.category";  
    $q8="select lead.status as pstatus,count(lead.id)  from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$camp' and lead.appoint<='$date' and lead.sub_domain='$subdomain' and lead.archive IS NULL and lead_allot.status='yes' and lead_allot.sid='$username'  $filter group by lead.status";  
     $q9="select lead.zone as zone,count(lead.id)  from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$camp' and lead.appoint<='$date' and lead.sub_domain='$subdomain' and lead.archive IS NULL and lead_allot.status='yes' and lead_allot.sid='$username'  $filter group by lead.zone";  

        
       $ssss=mysqli_query($host,$q1);
        $ssss2=mysqli_query($host,$q2);
        $num1=mysqli_num_rows($ssss);
         $num2=mysqli_num_rows($ssss2);  
         }
    }
        
     $s=mysqli_query($host,$q); 
     $arr['size2']=$num2==null?0:$num2;
     $arr['size1']=$num1==null?0:$num1;
     
$arr['message']=$q;

     
    $arr['data']=array();
    while($d = mysqli_fetch_array($s)){
        $row['db_date_check'] = $d['ddate'];
        $astaff = "";
        $lid = $d['lid'];
        $ss = mysqli_query($host, "select id from complaint_status where comp_id='$lid'");
        
        $row['postt'] = 0;
        $row['statuscount'] = mysqli_num_rows($ss);
        $row['name'] = $d['sname'];
        $row['id'] = $d['lid'];
        $row['status'] = $d['status'];
        $row['fname'] = $d['fname'];
        $row['category'] = $d['category'];
        $row['mobile'] = $d['mobile'];
        $row['compaign'] = $d['cam'];
        $row['zone'] = $d['zone'];
        $row['amobile'] = $d['amobile'];
        $row['astaff'] = $astaff;
         

        // --- LEAD TABLE SE DATE FETCH KARNE KA PAKKA LOGIC ---
        
        $finalDate = "";

        // 1. Pehle 'ddate' check karo (jo aapne query mein alias banaya hai)
        if(!empty($d['ddate']) && $d['ddate'] != '0000-00-00'){
            $finalDate = $d['ddate'];
        } 
        // 2. Agar wo nahi hai, toh check karo 'date' (Marketing query ke liye)
        else if(!empty($d['date']) && $d['date'] != '0000-00-00'){
            $finalDate = $d['date'];
        }
        // 3. Agar wo bhi nahi hai, toh 'appoint' check karo
        else if(!empty($d['appoint']) && $d['appoint'] != '0000-00-00'){
            $finalDate = $d['appoint'];
        }

        // Final Display
        if($finalDate != ""){
            $formatted = date('d M Y', strtotime($finalDate));
            $row['appoint'] = $formatted;
            $row['sendtime'] = $formatted; 
        } else {
            // Agar database mein sach mein date khali hai, toh aaj ki date dikha do 
            // taaki user ko "No Date" na dikhe, par aapko pata rahe ki fallback chal raha hai.
            $row['appoint'] = date('d M Y'); 
            $row['sendtime'] = date('d M Y');
        }

        // --- STATUS LOGIC ---
        if($d['lead_convert'] == "yes") {
            $row['cstatus'] = "convert";
            ++$convert;
        } else {
            if($d['lead_close'] == "yes") {
                $row['cstatus'] = "close";
                ++$close;
            } else {
                $row['cstatus'] = "";
            }
        }
        
        $arr['data'][] = $row;
    }
    $arr['size3']=$num4==null?0:$num4;
    $arr['size4']=$num3==null?0:$num3;
    $arr['size5']=$num5==null?0:$num5;


$q1.=" group by lead.category ";
 
$ss0=mysqli_query($host,$q7); 
while($dd0=mysqli_fetch_array($ss0)){
$row4['name']=$dd0[0]; 
$row4['count']=$dd0[1]; 
$arr['cate'][]=$row4;
}



$ss0=mysqli_query($host,$q9); 
while($dd0=mysqli_fetch_array($ss0)){
$row4['name']=$dd0[0]; 
$row4['count']=$dd0[1]; 
$arr['zone'][]=$row4;
}



 $ss0=mysqli_query($host,$q8); 
while($dd0=mysqli_fetch_array($ss0)){
$row5['name']=$dd0[0];    
$row5['count']=$dd0[1];  
$arr['sts'][]=$row5;
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