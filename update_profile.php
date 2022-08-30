<?php

include 'db/config.php';
session_start();
$user_id = $_SESSION['id'];

if(isset($_POST['update_profile'])){

   $update_name = mysqli_real_escape_string($link, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($link, $_POST['update_email']);
   $update_education = mysqli_real_escape_string($link, $_POST['update_education']);
   $update_address = mysqli_real_escape_string($link, $_POST['update_address']);
   $update_date_of_birth = mysqli_real_escape_string($link, $_POST['update_date_of_birth']);
   
   if(!empty($update_address) || !empty($update_education) || !empty(($update_email) || !empty($update_name))){
      $message[] = 'Updated successfully!';
      mysqli_query($link, "UPDATE `users` SET username = '$update_name', email = '$update_email' , address = '$update_address' , education = '$update_education' WHERE id = '$user_id'") or die('query failed');
   }

   $old_pass = $_POST['old_pass'];
   $new_pass = $_POST['new_pass'];

      // $new_pass = mysqli_real_escape_string($link, password_hash($_POST['new_pass'],PASSWORD_DEFAULT));
      // $message[] = "";
      if(!empty($new_pass)){
         $new_password = password_hash($new_pass,PASSWORD_DEFAULT);
         print_r($new_pass);
            mysqli_query($link, "UPDATE `users` SET password = '$new_password' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'password updated successfully!';
         }

      if(!empty($update_date_of_birth)){
            mysqli_query($link, "UPDATE `users` SET date_of_birth = '$update_date_of_birth' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'Date updated successfully!';
         } else {
            // problem with input ...
         }
   

   $update_image = $_FILES['update_image']['name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_folder = 'images/'.$update_image;

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image is too large';
      }else{
         $image_update_query = mysqli_query($link, "UPDATE `users` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
         if($image_update_query){
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
         }
         $message[] = 'image updated succssfully!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="update-profile">

   <?php
      $sql = mysqli_query($link, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($sql) > 0){
         $fetch = mysqli_fetch_assoc($sql);
      }
   ?>

   <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
      <?php
         if($fetch['image'] == ''){
            echo '<img src="images/default-avatar.png">';
         }else{
            echo '<img src="images/'.$fetch['image'].'">';
         }
         if(!empty($message)){
            foreach($message as $message){
               echo '<div class="message">'.$message.'</div>';
            }
         }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>username :</span>
            <input type="text" name="update_name" value="<?php echo $fetch['username']; ?>" class="box">
            <span>your email :</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
            <span>Address :</span>
            <input type="text" name="update_address" value="<?php echo $fetch['address']; ?>" class="box">
            <span>update your pic :</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <span>Date of Birth :</span>
            <input type="date" name="update_date_of_birth" value="<?php echo $fetch['date_of_birth']; ?>" class="box">
            <span>Education :</span>
            <input type="text" name="update_education" value="<?php echo $fetch['education']; ?>" class="box">

            <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
            <span>new password :</span>
            <input type="password" name="new_pass" placeholder="enter new password" class="box">
         </div>
      </div>
      <input type="submit" value="update profile" name="update_profile" class="btn">
      <a href="home.php" class="delete-btn">go back</a>
   </form>

</div>

</body>
</html>