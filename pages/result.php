 <?php 
    $examId = $_GET['id'];
    $selExam = $conn->query("SELECT * FROM exams WHERE id='$examId' ")->fetch(PDO::FETCH_ASSOC);

 ?>

<div class="app-main__outer">
<div class="app-main__inner">
    <div id="refreshData">
      <!-- /*!
* Author Name: MH RONY.
* GigHub Link: https://github.com/dev-mhrony
* Facebook Link:https://www.facebook.com/dev.mhrony
* Youtube Link: https://www.youtube.com/channel/UChYhUxkwDNialcxj-OFRcDw
for any PHP, Laravel, Python, Dart, Flutter work contact me at developer.mhrony@gmail.com
* Visit My Website : developerrony.com
*/ -->      
    <div class="col-md-12">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <?php echo $selExam['title']; ?>
                          <div class="page-title-subheading">
                            <?php echo $selExam['exam_type']; ?>
                          </div>

                    </div>
                </div>
            </div>
        </div>  
        <div class="row col-md-12">
        	<h1 class="text-primary">RESULT'S</h1>
        </div>

        <div class="row col-md-6 float-left">
        	<div class="main-card mb-3 card">
                <div class="card-body">
                	<h5 class="card-title">Your Answer's</h5>
        			<table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
                                        <?php 
                        $selQuest = $conn->query("SELECT q.exam_question, q.exam_ch1, q.exam_ch2, q.exam_ch3, q.exam_ch4, q.exam_answer, r.answer FROM responses r INNER JOIN questions q ON q.id = r.question_id WHERE r.exam_id='$examId' AND r.user_id='$exmneId' ");
                        $i = 1;
                        while ($row = $selQuest->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td>
                                <b><p><?php echo $i++; ?> .) <?php echo $row['exam_question']; ?></p></b>
                                <div class="pl-4">
                                    <?php 
                                        $choices = [
                                            'A' => $row['exam_ch1'],
                                            'B' => $row['exam_ch2'],
                                            'C' => $row['exam_ch3'],
                                            'D' => $row['exam_ch4'],
                                        ];
                                        foreach ($choices as $label => $text):
                                            if (empty($text)) continue;
                                            $isUser = ($row['answer'] === $text);
                                            $isCorrect = ($row['exam_answer'] === $text);
                                    ?>
                                        <div>
                                            <span style="font-weight: <?php echo $isUser ? 'bold' : 'normal'; ?>; color: <?php echo $isCorrect ? '#28a745' : ($isUser ? '#dc3545' : 'inherit'); ?>;">
                                                <?php echo $label; ?> - <?php echo htmlspecialchars($text, ENT_QUOTES); ?>
                                                <?php if ($isCorrect): ?>
                                                    <small>(correct)</small>
                                                <?php endif; ?>
                                                <?php if ($isUser && !$isCorrect): ?>
                                                    <small>(your answer)</small>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <?php }
                         ?>
	                 </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 float-left">
        	<div class="col-md-6 float-left">
        	<div class="card mb-3 widget-content bg-night-fade">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading"><h5>Score</h5></div>
                        <div class="widget-subheading" style="color: transparent;">/</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <?php 
                                // If you maintain per-exam correct answers in exam_questions.exam_answer
                                $selScore = $conn->query("SELECT COUNT(*) as score FROM exam_questions eq INNER JOIN questions q ON q.id = eq.question_id INNER JOIN responses r ON eq.question_id = r.question_id AND r.answer = q.exam_answer WHERE r.user_id='$exmneId' AND r.exam_id='$examId'")->fetch(PDO::FETCH_ASSOC);
                                $score = $selScore ? (int)$selScore['score'] : 0;
                            ?>
                            <span>
                                <?php echo $score; ?>
                                <?php 
                                    $over  = $conn->query("SELECT COUNT(*) as cnt FROM exam_questions WHERE exam_id='$examId'")->fetch(PDO::FETCH_ASSOC)['cnt'];
                                 ?>
                            </span> / <?php echo $over; ?>
                        </div>
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
            <div class="col-md-6 float-left">
            <div class="card mb-3 widget-content bg-happy-green">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading"><h5>Percentage</h5></div>
                        <div class="widget-subheading" style="color: transparent;">/</div>
                        </div>
                        <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <?php 
                                $ans = $over > 0 ? ($score / $over * 100) : 0;
                                echo $ans . "%";
                             ?>
                            
                        </div>
                    </div>
                </div>
            </div>
            </div>
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