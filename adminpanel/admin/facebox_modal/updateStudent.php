
<?php 
  include("../../../conn.php");
  $id = $_GET['id'];
 
  $selExmne = $conn->query("SELECT * FROM students WHERE id='$id' ")->fetch(PDO::FETCH_ASSOC);

 ?>

<fieldset style="width:543px;" >
	<legend><i class="facebox-header"><i class="edit large icon"></i>&nbsp;Update <b>( <?php echo strtoupper($selExmne['fullname']); ?> )</b></i></legend>
  <div class="col-md-12 mt-4">
<form method="post" id="updateStudentFrm">
     <div class="form-group">
        <legend>Fullname</legend>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="" name="fullname" class="form-control" required="" value="<?php echo $selExmne['fullname']; ?>" >
     </div>

     <div class="form-group">
        <legend>Gender</legend>
        <select class="form-control" name="gender">
          <option value="<?php echo $selExmne['gender']; ?>"><?php echo $selExmne['gender']; ?></option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
     </div>

     <div class="form-group">
        <legend>Birthdate</legend>
        <input type="date" name="birthdate" class="form-control" required="" value="<?php echo date('Y-m-d',strtotime($selExmne["birthdate"])) ?>"/>
     </div>

     <div class="form-group">
        <legend>Course</legend>
        <?php 
            $courseId = $selExmne['course_id'];
            $selCourse = $conn->query("SELECT * FROM course WHERE id='$courseId' ")->fetch(PDO::FETCH_ASSOC);
         ?>
         <select class="form-control" name="course_id">
           <option value="<?php echo $courseId; ?>"><?php echo $selCourse['name']; ?></option>
           <?php 
             $selCourse = $conn->query("SELECT * FROM course WHERE id!='$courseId' ");
             while ($selCourseRow = $selCourse->fetch(PDO::FETCH_ASSOC)) { ?>
              <option value="<?php echo $selCourseRow['id']; ?>"><?php echo $selCourseRow['name']; ?></option>
            <?php  }
            ?>
         </select>
     </div>


      <div class="form-group">
        <legend>Year level</legend>
        <select class="form-control" name="year_level">
          <option value="<?php echo $selExmne['year_level']; ?>"><?php echo $selExmne['year_level']; ?></option>
            <option value="first year">First Year</option>
            <option value="second year">Second Year</option>
            <option value="third year">Third Year</option>
            <option value="fourth year">Fourth Year</option>
        </select>
     </div>

     <div class="form-group">
        <legend>Email</legend>
        <input type="" name="email" class="form-control" required="" value="<?php echo $selExmne['email']; ?>" >
     </div>

     <div class="form-group">
        <legend>Password</legend>
        <input type="" name="password" class="form-control" placeholder="Leave blank to keep current" >
     </div>

      <div class="form-group">
        <legend>Status</legend>
        <select class="form-control" name="status">
          <option value="<?php echo $selExmne['status']; ?>"><?php echo $selExmne['status']; ?></option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
     </div>

  <div class="form-group" align="right">
    <button type="submit" class="btn btn-sm btn-primary">Update Now</button>
  </div>
</form>
  </div>
</fieldset>







