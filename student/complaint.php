<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$tim=time();
function send_notification($title,$desc,$token,$key,$m)
{

#prep the bundle
     $msg = array
          (
        'body'  => $desc,
        'title' => $title,
        'click_action' => $m,
                
          );
          
         
    $fields = array
            (
                'registration_ids'  => $token,
                'notification'  => $msg
            );
    
    
    $headers = array
            (
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            );
            
          
             
#Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
           $result = curl_exec($ch );
        curl_close( $ch );
        
           
        
       
}

$sid=$_POST['sid'];
$username=$_POST['username'];
$subject=$_POST['subject'];
$desc=$_POST['descr'];
$token=$_POST['token'];
$type=$_POST['type'];
$mode=$_POST['mode'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
         $s0=mysqli_query($host,"select * from students where id='$sid'");
    $d0=mysqli_fetch_array($s0);
    $name=$d0['name'];
    $user=$d0['cid'];
    $sss=mysqli_query($host,"select sub_domain from branch_category where id='$user'");
    $ddd=mysqli_fetch_array($sss);
    $subdomain=$ddd[0];
    $sid=$d0['id'];
    if($type=='parent'){  if($d0['gmobile']){ $mobile=$d0['gmobile'];  }else{ $mobile=$d0['smobile'];   } $name=$d0['fname']; }else{ $mobile=$d0['smobile']; $name=$d0['name'];  }
    
    
        if($subject){
              if($_FILES['file']['name']){
         $sourcePath=$_FILES['file']['tmp_name'];
$image_info=getimagesize($sourcePath);
$filename =time().".png";
move_uploaded_file($sourcePath,"../../cpanel/uploads/".$filename);   
        } 
        
        $som=mysqli_query($host,"select id from complaint where user='$user'");
        $cno=mysqli_num_rows($som)+1;
        
          $q="insert into complaint set department='$subject',cno='$cno',app_name='$name',app_mobile='$mobile',at_mobile='$mobile',com_subject='$subject',com_desc='$desc',mode='$mode',upload_pics='$filename',type='$type',user='$user',staffid='0',staffname='By $type',date='$date',time='$time'";
            mysqli_query($host,$q);
        $s1=mysqli_query($host,"select * from complaint where user='$user' order by id desc limit 1");
        $d1=mysqli_fetch_array($s1);
        $cid=$d1['id'];
        $ssid=$sid;
        mysqli_query($host,"insert into perm_comp set complain_id='$cid',sid='$sid',type='$type',date='$date',time='$time',user='$user',staffid='0',staffname='by $type'");
         $token=array();
         $s23=mysqli_query($host,"select staff.admin_device as admin_device,staff.id as id from staff INNER JOIN setting ON setting.appeal_respo=staff.id where length(staff.admin_device)>0 and setting.cid='$user'");
if(mysqli_num_rows($s23)>0){
    
$d23=mysqli_fetch_array($s23);
  $token[0]=$d23['admin_device'];
  $sid=$d23['id'];

$s=mysqli_query($host,"select * from global where name='Admin Key'");
$d=mysqli_fetch_array($s);
$tkey=$d['val'];
$title="Appeal Registered  by ".$name;
$descr="Please Give The Solution Appeal No. ".$cno;
    send_notification($title,$descr,$token,$tkey,"complaint");
    mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='staff'");
       mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");
}

         
         
        }
        
        $q="select complaint.date as date,complaint.com_subject,complaint.com_desc,complaint.id,perm_comp.type as type,complaint.* from perm_comp INNER JOIN complaint ON complaint.id=perm_comp.complain_id where perm_comp.user='$user' and perm_comp.sid='$sid' and complaint.archive IS NULL and mode='complaint' order by complaint.id desc";
    $arr['status']=true;
    $arr['message']='Success';
    
    
    $arr['complaint']=array();
    
    $arr['department']=array();
    $rom['name']='Acedemics';
$arr['department'][]=$rom;
$rom['name']='Finance';
$arr['department'][]=$rom;
$rom['name']='Examination';
$arr['department'][]=$rom;
$rom['name']='Transport';
$arr['department'][]=$rom;
$rom['name']='Other';
$arr['department'][]=$rom;
$s09=mysqli_query($host,"select * from department where cid='$user'");
while($d09=mysqli_fetch_array($s09)){
$rom['name']=$d09['name'];
$arr['department'][]=$rom;
}
    
   
            $q="select complaint.date as date,complaint.com_subject,complaint.com_desc,complaint.id,perm_comp.type as type,complaint.* from perm_comp INNER JOIN complaint ON complaint.id=perm_comp.complain_id where perm_comp.user='$user' and perm_comp.sid='$sid' and complaint.archive IS NULL order by complaint.id desc";


       $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){ 
    
       $id=$d[3];
       
        $q="select * from complaint_status2 where comp_id='$id' order by id desc limit 1";
       $s1=mysqli_query($host,$q);
       $d1=mysqli_fetch_array($s1);
       if(mysqli_num_rows($s1)==0 || $d1['upd_sts']=="" || $d1['upd_sts']==NULL || $d1['upd_sts']==" "){
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
$row16['id']=$d['cno'];
$row16['mode']=$d['mode'];
$row16['update']=$update;
$row16['ustatus']=$ustatus;
$row16['photo']=$d['upload_pics']==""?NULL:"https://edpluss.com/school/admin-panel/cpanel/uploads/".$d['upload_pics'];
$row16['type']=$d['type']=='parent'?'From You':'From You';
$row16['types']=$d['type']=='parent'?'By Parent':'By Student';
$arr['complaint'][]=$row16;
       
}

            $s09=mysqli_query($host,"select complaint.id from perm_comp INNER JOIN complaint INNER JOIN complaint_status2 ON complaint.id=perm_comp.complain_id  and complaint_status2.comp_id=complaint.id where perm_comp.user='$user' and complaint_status2.upd_sts='complete' and perm_comp.sid='$sid' and complaint.archive IS NULL order by complaint.id desc");


    $arr['text1']=mysqli_num_rows($s);
    $arr['text2']=mysqli_num_rows($s09);


 $q="select complaint.date as date,complaint.com_subject,complaint.com_desc,complaint.id,complaint.* from complaint_resposible INNER JOIN complaint ON complaint.id=complaint_resposible.complaint_id where complaint_resposible.cid='$user' and complaint_resposible.sid='$sid' and complaint.archive IS NULL and complaint.user='$user'";

       $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){ 
    
       $id=$d[3];
       
        $q="select * from complaint_status2 where comp_id='$id' order by id desc limit 1";
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
$row16['photo']=$d['upload_pics']==""?NULL:"https://edpluss.com/school/admin-panel/cpanel/uploads/".$d['upload_pics'];
$row16['type']='For You';
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