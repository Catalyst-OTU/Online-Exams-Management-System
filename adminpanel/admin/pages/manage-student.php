<link rel="stylesheet" type="text/css" href="css/mycss.css">
<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div>MANAGE STUDENT</div>
                    </div>
                </div>
            </div>        
            
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">Student List
                    </div>
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                            <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Gender</th>
                                <th>Birthdate</th>
                                <th>Course</th>
                                <th>Year level</th>
                                <th>Email</th>
                                <th>status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $selExmne = $conn->query("SELECT * FROM students ORDER BY id DESC ");
                                if($selExmne->rowCount() > 0)
                                {
                                    while ($selExmneRow = $selExmne->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                           <td><?php echo $selExmneRow['fullname']; ?></td>
                                           <td><?php echo $selExmneRow['gender']; ?></td>
                                           <td><?php echo $selExmneRow['birthdate']; ?></td>
                                           <td>
                                            <?php 
                                                 $courseId = $selExmneRow['course_id'];
                                                 $selCourse = $conn->query("SELECT * FROM course WHERE id='$courseId' ")->fetch(PDO::FETCH_ASSOC);
                                                 echo $selCourse ? $selCourse['name'] : '';
                                             ?>
                                            </td>
                                           <td><?php echo $selExmneRow['year_level']; ?></td>
                                           <td><?php echo $selExmneRow['email']; ?></td>
                                           <td><?php echo $selExmneRow['status']; ?></td>
                                           <td>
                                               <a rel="facebox" href="facebox_modal/updateStudent.php?id=<?php echo $selExmneRow['id']; ?>" class="btn btn-sm btn-primary">Update</a>

                                           </td>
                                        </tr>
                                    <?php }
                                }
                                else
                                { ?>
                                    <tr>
                                      <td colspan="2">
                                        <h3 class="p-3">No Course Found</h3>
                                      </td>
                                    </tr>
                                <?php }
                               ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
      
        
</div>
         
<!-- /*!
* Author Name: MH RONY.
* GigHub Link: https://github.com/dev-mhrony
* Facebook Link:https://www.facebook.com/dev.mhrony
* Youtube Link: https://www.youtube.com/channel/UChYhUxkwDNialcxj-OFRcDw
for any PHP, Laravel, Python, Dart, Flutter work contact me at developer.mhrony@gmail.com
* Visit My Website : developerrony.com
*/ -->