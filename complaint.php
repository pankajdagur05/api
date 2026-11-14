<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_REQUEST['delete'];
$username=$_REQUEST['userid'];
$subdomain=$_REQUEST['subdomain'];
$token=$_REQUEST['token'];
$type='other';
$cno=time();
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
if($delete){
    mysqli_query($host,"delete from complaint where id='$delete' and sub_domain='$subdomain'");
}else{
    
if($_REQUEST['status'] & $_REQUEST['sname'])  {  
    
$idd=$_REQUEST['id'];

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffname=$d3['name'];
      $user=$branch;
      

$status=$_REQUEST['status'];
$sname=$_REQUEST['sname'];
$fname=$_REQUEST['fname'];
$mname=$_REQUEST['mname'];
$gender=$_REQUEST['gender'];
$mobile=$_REQUEST['mobile'];
$amobile=$_REQUEST['amobile'];
$email=$_REQUEST['mail'];
$add=$_REQUEST['add1'];
$add2=$_REQUEST['add2'];
$clas=$_REQUEST['clas'];
$complaint=$_REQUEST['complaint'];
$resolve=$_REQUEST['resolve'];
$remark=$_REQUEST['remark'];
$title=$_REQUEST['title'];
$depart=$_REQUEST['depart'];
$doc=$_REQUEST['doc'];
$soc=$_REQUEST['soc'];
$sendby=$_REQUEST['by'];
$sid=$_REQUEST['sid'];
$date=date('Y-m-d');
$tim=date('h:i:s');
$time=time();
$unique=uniqid();
$path=$date.$time.$unique;
if($_FILES['uplm']['name']){
$file2=$_FILES["uplm"]["tmp_name"];
$ex2=$_FILES['uplm']['name'];
$extn2=pathinfo($ex2, PATHINFO_EXTENSION);	
$photo=$path.".jpg";
$path3="../cpanel/upload/profile/".$path.".jpg";
move_uploaded_file($file2,$path3);
}else
{
  $photo="";
}

if($sid==""){$sid=NULL;}

if($idd){
	mysqli_query($host,"update complaint set name='$sname',status='$status',cno='$cno',soc='$soc',doc='$doc',fname='$fname',mname='$mname',mobile='$mobile',amobile='$amobile',gender='$gender',mail='$email',add1='$add',add2='$add2',title='$title',depart='$depart',remark='$remark',complaint='$complaint',resolve='$resolve',clas='$clas',photo='$photo',date='$date',user='$username',type='$type',time='$time',sendby='$sendby' where branch='$branch' and id='$idd' and sub_domain='$subdomain'");	
	
}else{
$q="insert into complaint set name='$sname',status='$status',soc='$soc',doc='$doc',sid='$sid',cno='$cno',fname='$fname',mname='$mname',mobile='$mobile',amobile='$amobile',gender='$gender',mail='$email',add1='$add',add2='$add2',title='$title',depart='$depart',remark='$remark',complaint='$complaint',resolve='$resolve',clas='$clas',sendby='$sendby',photo='$photo',date='$date',user='$username',complaintstatus='$status',type='$type',time='$time',branch='$branch',sub_domain='$subdomain'";
mysqli_query($host,$q);	

$sms='Dear '.$sname.' Your Complaint Has been registered . Complaint No. Is '.$cno;
include("sms.php");
	
}
}
}
$arr['status']=true;
    $arr['message']='success'.$p;
    $arr['data']=array();
    $s=mysqli_query($host,"select * from complaint where type='other' and user='$username' and sub_domain='$subdomain'");
    while($d=mysqli_fetch_array($s)){
        $tid=$d['id'];
        $row['name']=$d['name'];
        $row['id']=$d['id'];
         $row['cno']=$d['cno'];
        $row['status']=$d['status'];
        $row['name']=$d['name'];
        $row['gender']=$d['gender'];
        $row['fname']=$d['fname'];
         $row['mname']=$d['mname'];
          $row['mobile']=$d['mobile'];
           $row['amobile']=$d['amobile'];
            $row['mail']=$d['mail'];
             $row['sendby']=$d['sendby'];
             $row['add1']=$d['add1'];
              $row['add2']=$d['add2'];
               $row['clas']=$d['clas'];
                $row['title']=$d['title'];
                 $row['complaint']=$d['complaint'];
                  $row['resolve']=$d['resolve'];
                   $row['depart']=$d['depart'];
                   $row['remark']=$d['remark'];
                   $row['doc']=$d['doc'];
                   $row['soc']=$d['soc'];
                   $row['complaintstatus']=$d['complaintstatus'];
                    $row['data']=array();
                   if($d['photo']){ 
                    $row['photo']="https://www.smartechindia.in/leadpanel/cpanel/upload/".$d['photo']; 
                    }else{
                    $row['photo']="";
                   } 
                   
$s2=mysqli_query($host,"select * from update_status where mode='complaint' and typeid='$tid' and sub_domain='$subdomain' order by id desc");
while($d2=mysqli_fetch_array($s2))
{
    $row2['remark']=$d2['sts_remark'];
    $row2['status']=$d2['upd_sts'];
    $row2['date']=$d2['date'];
    $row2['time']=date('h:i:s',$d2['time']);
    $row['data'][]=$row2;
}
                  
        $arr['data'][]=$row;
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