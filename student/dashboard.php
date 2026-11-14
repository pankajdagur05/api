<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$sid=$_POST['sid'];
$username=$_POST['username'];
$token=$_POST['token'];
$type=$_POST['type'];
$sid2=$sid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $q="select * from apk_token where token='$token' and user='$username'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
   $s90=mysqli_query($host,"select branch_category.sub_domain from students INNER JOIN branch_category ON branch_category.id=students.id where students.id='$sid'");  
   //echo "select branch_category.sub_domain from students INNER JOIN branch_category ON branch_category.id=students.id where students.id='$sid'";
   $d90=mysqli_fetch_array($s90);
   $subdomain=$d90[0];
   $user=$d90['cid'];
 
    if(strtolower(trim($type))=='student' or strtolower(trim($type))=='students'){ $ty='s_notification'; $field="smobile"; $ps="s_pass"; $dev="s_device"; }else{  $ty='p_notification'; $field="smobile"; $ps="p_pass"; $dev="p_device"; }
    
$q="select students.smobile,students.mname,students.fname,students.stu_id,students.cid,students.name,students.id,students.standard,students.stream,students.dob,students.route,branch_category.iname as nn,branch_category.sub_domain as sub_domain from students INNER JOIN branch_category ON branch_category.id=students.cid where students.$field='$username' and branch_category.type='branch'";
if($subdomain){ $q.=" branch_category.sub_domain='$subdomain'"; }
$q.=" and students.status='Active'  order by students.id desc ";


$sql=mysqli_query($host,$q);
          $ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']="success";
		 $ar1['version']='4.0';
		 $ar1['selected_branch']=$sid;
		 $arr['baseurl']="https://edpluss.com";
 
	
		 $ar1['account']=array();
		 $ar1['slider']=array();
         while($like=mysqli_fetch_array($sql)){
              $sid=$like['id'];
              $ndate=$like[$ty];
              $ss=mysqli_query($host,"select * from notification where sid='$sid' and type='$type' and date>'$ndate'");
              $row['notification']=mysqli_num_rows($ss);
              $s1=mysqli_query($host,"select sum(debit),sum(credit) from feesdue where sid='$sid' and cancel='No' and rdate<='$date'");
            $d1=mysqli_fetch_array($s1);
               $row['overduefees']=$d1[0]-$d1[1];
               
                 $s1=mysqli_query($host,"select sum(debit),sum(credit) from feesdue where sid='$sid' and cancel='No'");
            $d1=mysqli_fetch_array($s1);
               $row['duefees']=$d1[0];
                $s01=mysqli_query($host,"select * from erp_setting where cid='$user'");
                $d01=mysqli_fetch_array($s01);
            if($d01['parentledger']=='due'){
                 $row['depositfees']=$d1[1];
            }else{
               
                $row['depositfees']="00000";
            }
                
                
                
		         
		        $ss=mysqli_query($host,"select * from docs where sid='$sid' and name='Photo'");
		        if(mysqli_num_rows($ss)==0){ $pic="https://edpluss.com/school/admin-panel/cpanel/access/img/student-list.png"; }else{ $dd=mysqli_fetch_array($ss); $pic="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$dd['filename'];  }
		          $cid=$like['cid'];
		           $s01=mysqli_query($host,"select * from branch_category where id='$cid'");
		           $d8=mysqli_fetch_array($s01);
		           $row['branchname']=$like['nn'];
		           $row['logo']="https://edpluss.com/school/admin-panel/cpanel/logo/".$like['cid'].".png";
		            $row['transport']="https://edpluss.com/project/";
		            $row['report']="https://edpluss.com/contact.php";
		            $row['notification']="https://edpluss.com/contact.php";
		            $row['id']=$like['id'];
		            $row['name']=$like['name'];
		            $row['standard']=$like['standard'];
		             //$row['bnumber']=$like['smobile'];
		              $row['bwnumber']=$like['smobile'];
		             $row['route']='pratap nagar';
		            $row['session']=$like['session'];
		            $row['fname']=$like['fname'];
		              $row['mname']=$like['mname'];
		                $row['dob']=date('d M Y',strtotime($like['dob']));
		              $row['sr']=$like['stu_id'];
		              $row['score']=78;
		            $row['stream']=$like['stream'];
		            $row['branchid']=$like['cid'];
		            $row['subdomain']=$like['sub_domain'];
		            $row['photo']=$pic;
		            
		            $row['facebook']=$d8['fb'];
	                 $row['instagram']=$d8['insta'];
		            $row['youtube']=$d8['yt'];
		           $row['twitter']=$d8['tw'];
		           $row['bwebsite']=$d8['website'];
		           $row['blat']=$d8['blat'];
		           $row['blon']=$d8['blon'];
		           $row['bnumber']=$d8['mobile'];
		           //$row['bwnumber']=$d8['amobile'];
		           $row['bemail']=$d8['email'];
		            $row['baddress']=$d8['address'];
		            
		            
		            $ar1['account'][]=$row;
		            
		       } 
		      $s01=mysqli_query($host,"select * from website_gallery where subdomain='MDA' and cate='slider' and cid='$user' order by id desc"); 
		      while($d01=mysqli_fetch_array($s01)){
		          $rows['title']=$d01['title'];
		          $rows['path']="https://edpluss.com/school/admin-panel/cpanel/website/upload/gallery/".$d01['photo'];
		          $ar1['slider'][]=$rows;
		      }
		      
		      
		 $ar1['features']=array();     
		      
 $row=array();
 
           $row['name']="Day Report".$sid2;
           $row['group']="Attendance";
      $row['icon']="https://edpluss.com/image/sale.png";
      $row['link']="https://edpluss.com/api.php";
      $ar1['features'][]=$row;
       
       
       
           $row['name']="Travelling Report";
           $row['group']="Attendance";
      $row['icon']="https://edpluss.com/image/car.png";
      $row['link']="https://edpluss.com/contact.php";
      $ar1['features'][]=$row;
      
      
      
        $row['name']="IVR Calling ";
        $row['group']="Other";
      $row['icon']="https://edpluss.com/image/phone.png";
      $row['link']="https://edpluss.com/pricing.php";
      $ar1['features'][]=$row;
      $fromdate=date("Y-m-01");
      $todate=date("Y-m-t");
     
     
     
          $row['name']="Attedance Records ";
             $row['group']="Other";
      $row['icon']="https://edpluss.com/image/attendance.png";
      $row['link']="https://edpluss.com/";
      $ar1['features'][]=$row;
          
      
       $row['name']="Scheduler List";
          $row['group']="Other";
      $row['icon']="https://edpluss.com/image/calenda.png";
      $row['link']="https://edpluss.com/test.php";
      $ar1['features'][]=$row;
      

		       
    }else{
    $ar1['status']=false;
    $ar1['message']='Token Not Match';
}

echo $json = json_encode($ar1);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>