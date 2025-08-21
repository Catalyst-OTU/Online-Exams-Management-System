<div class="app-main__outer">
        <div class="app-main__inner">
             


            <?php 
                @$exam_id = $_GET['exam_id'];


                if($exam_id != "")
                {
                   $selEx = $conn->query("SELECT * FROM exams WHERE id='$exam_id' ")->fetch(PDO::FETCH_ASSOC);

                   // Pull all non-admin users
                   $selUsers = $conn->query("SELECT id, username FROM users WHERE role <> 'admin' ORDER BY username");

                   ?>
                   <div class="app-page-title">
                    
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div><b class="text-primary">RANKING BY EXAM</b><br>
                                Exam Name : <?php echo $selEx['title']; ?><br><br>
                               <span class="border" style="padding:10px;color:black;background-color: yellow;">Excellence</span>
                               <span class="border" style="padding:10px;color:white;background-color: green;">Very Good</span>
                               <span class="border" style="padding:10px;color:white;background-color: blue;">Good</span>
                               <span class="border" style="padding:10px;color:white;background-color: red;">Failed</span>
                               <span class="border" style="padding:10px;color:black;background-color: #E9ECEE;">Not Answering</span>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                          <tbody>
                            <thead>
                                <tr>
                                    <th width="25%">User</th>
                                    <th>Score / Over</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <?php 
                                $over = $conn->query("SELECT COUNT(*) as cnt FROM exam_questions WHERE exam_id='$exam_id'")->fetch(PDO::FETCH_ASSOC)['cnt'];
                                while ($user = $selUsers->fetch(PDO::FETCH_ASSOC)) { 
                                    $uid = $user['id'];
                                    $scoreRow = $conn->query("SELECT COUNT(*) as score FROM exam_questions eq INNER JOIN responses r ON eq.question_id = r.question_id AND r.answer = eq.exam_answer WHERE r.user_id='$uid' AND r.exam_id='$exam_id'")->fetch(PDO::FETCH_ASSOC);
                                    $score = $scoreRow ? (int)$scoreRow['score'] : 0;
                                    $hasResponse = $conn->query("SELECT 1 FROM responses WHERE user_id='$uid' AND exam_id='$exam_id' LIMIT 1")->rowCount() > 0;
                                    $ans = $over > 0 ? ($score / $over * 100) : 0;

                                    $rowStyle = '';
                                    if (!$hasResponse) {
                                        $rowStyle = "background-color: #E9ECEE;color:black";
                                    } else if ($ans >= 90) {
                                        $rowStyle = "background-color: yellow;";
                                    } else if ($ans >= 80) {
                                        $rowStyle = "background-color: green;color:white";
                                    } else if ($ans >= 75) {
                                        $rowStyle = "background-color: blue;color:white";
                                    } else {
                                        $rowStyle = "background-color: red;color:white";
                                    }
                            ?>
                                   <tr style="<?php echo $rowStyle; ?>">
                                    <td>
                                      <?php echo htmlspecialchars($user['username']); ?></td>
                                    
                                    <td >
                                    <?php 
                                      if(!$hasResponse)
                                      {
                                        echo "Not answer yet";
                                      }
                                      else
                                      {
                                        echo $score;
                                        echo " / ";
                                        echo $over;
                                      }
                                    ?>
                                    </td>
                                    <td>
                                      <?php 
                                            if(!$hasResponse)
                                            {
                                              echo "Not answer yet";
                                            }
                                            else
                                            {
                                                echo $ans; ?>%<?php
                                            }
                                       ?>
                                    </td>
                                </tr>
                            <?php }
                             ?>                              
                          </tbody>
                        </table>
                    </div>



                   <?php
                }
                else
                { ?>
                <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div><b>RANKING BY EXAM</b></div>
                    </div>
                </div>
                </div> 

                 <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">ExAM List
                    </div>
                    <div class="table-responsive">
                        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                            <thead>
                            <tr>
                                <th class="text-left pl-4">Exam Title</th>
                                <th class="text-left ">Type</th>
                                <th class="text-center" width="8%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $selExam = $conn->query("SELECT * FROM exams ORDER BY id DESC ");
                                if($selExam->rowCount() > 0)
                                {
                                    while ($selExamRow = $selExam->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td class="pl-4"><?php echo $selExamRow['title']; ?></td>
                                            <td><?php echo $selExamRow['exam_type']; ?></td>
                                            <td class="text-center">
                                             <a href="?page=ranking-exam&exam_id=<?php echo $selExamRow['id']; ?>"  class="btn btn-success btn-sm">View</a>
                                            </td>
                                        </tr>

                                    <?php }
                                }
                                else
                                { ?>
                                    <tr>
                                      <td colspan="5">
                                        <h3 class="p-3">No Exam Found</h3>
                                      </td>
                                    </tr>
                                <?php }
                               ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   
                    
                <?php }

             ?>      
            
            
      
        
</div>
         


















<!-- /*!
* Author Name: MH RONY.
* GigHub Link: https://github.com/dev-mhrony
* Facebook Link:https://www.facebook.com/dev.mhrony
* Youtube Link: https://www.youtube.com/channel/UChYhUxkwDNialcxj-OFRcDw
for any PHP, Laravel, Python, Dart, Flutter work contact me at developer.mhrony@gmail.com
* Visit My Website : developerrony.com
*/ -->