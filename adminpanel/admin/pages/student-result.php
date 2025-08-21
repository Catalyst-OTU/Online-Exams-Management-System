<!-- /*!
* Author Name: MH RONY.
* GigHub Link: https://github.com/dev-mhrony
* Facebook Link:https://www.facebook.com/dev.mhrony
* Youtube Link: https://www.youtube.com/channel/UChYhUxkwDNialcxj-OFRcDw
for any PHP, Laravel, Python, Dart, Flutter work contact me at developer.mhrony@gmail.com
* Visit My Website : developerrony.com
*/ -->

<link rel="stylesheet" type="text/css" href="css/mycss.css">
<div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div>STUDENT RESULT</div>
                    </div>
                </div>
            </div>        
            
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">Student Result
                    </div>
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                            <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Exam Name</th>
                                <th>Scores</th>
                                <th>Ratings</th>
                                <th width="10%"></th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $selExmne = $conn->query("SELECT DISTINCT s.id AS student_id, s.fullname, e.id AS exam_id, e.title FROM responses r INNER JOIN students s ON s.id = r.user_id INNER JOIN exams e ON e.id = r.exam_id ORDER BY r.id DESC");
                                if($selExmne->rowCount() > 0)
                                {
                                    while ($selExmneRow = $selExmne->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                           <td><?php echo $selExmneRow['fullname']; ?></td>
                                           <td>
                                              <?php echo $selExmneRow['title']; $exam_id = $selExmneRow['exam_id']; $eid = $selExmneRow['student_id']; ?>
                                           </td>
                                           <td>
                                                    <?php 
                                                    $selScore = $conn->query("SELECT COUNT(*) as score FROM exam_questions eq INNER JOIN questions q ON q.id = eq.question_id INNER JOIN responses r ON eq.question_id = r.question_id AND r.answer = q.exam_answer WHERE r.user_id='$eid' AND r.exam_id='$exam_id'")->fetch(PDO::FETCH_ASSOC);
                                                    $score = $selScore ? (int)$selScore['score'] : 0;
                                                    $over  = $conn->query("SELECT COUNT(*) as cnt FROM exam_questions WHERE exam_id='$exam_id'")->fetch(PDO::FETCH_ASSOC)['cnt'];
                                                      ?>
                                                <span>
                                                    <?php echo $score; ?>
                                                </span> / <?php echo $over; ?>
                                           </td>
                                           <td>
                                                                                             <?php 
                                                    $ans = $over > 0 ? ($score / $over * 100) : 0;
                                                 ?>
                                                 <span>
                                                     <?php echo $ans; ?>%
                                                 </span>  
                                           </td>
                                           <td>
                                                <a href="print_result.php?student_id=<?php echo $selExmneRow['student_id']; ?>&exam_id=<?php echo $selExmneRow['exam_id']; ?>" target="_blank" class="btn btn-sm btn-primary">Print Result</a>

                                           </td>
                                        </tr>
                                    <?php }
                                }
                                else
                                { ?>
                                    <tr>
                                      <td colspan="2">
                                        <h3 class="p-3">No Results Found</h3>
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