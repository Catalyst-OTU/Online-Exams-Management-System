<script type="text/javascript">
   function preventBack(){window.history.forward();}
   setTimeout("preventBack()", 0);
   window.onunload = function(){null};
</script>
<?php 
    $examId = $_GET['id'];
    $selExam = $conn->query("SELECT * FROM exams WHERE id='$examId' ")->fetch(PDO::FETCH_ASSOC);
    $selExamTimeLimit = $selExam['duration'];
    $exDisplayLimit = $conn->query("SELECT COUNT(*) as cnt FROM exam_questions WHERE exam_id='$examId'")->fetch(PDO::FETCH_ASSOC)['cnt'];
?>
<div class="app-main__outer">
<div class="app-main__inner">
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
                    <div class="page-title-actions mr-5" style="font-size: 20px;">
                        <form name="cd">
                          <input type="hidden" id="timeExamLimit" value="<?php echo $selExamTimeLimit; ?>">
                          <label>Remaining Time : </label>
                          <input style="border:none;background-color: transparent;color:blue;font-size: 25px;" 
                                 name="disp" type="text" class="clock" id="txt" value="00:00" size="5" readonly="true" />
                      </form> 
                    </div>   
                 </div>
            </div>  
    </div>

    <div class="col-md-12 p-0 mb-4">
        <form method="post" id="submitAnswerFrm">
            <input type="hidden" name="exam_id" id="exam_id" value="<?php echo $examId; ?>">
            <input type="hidden" name="examAction" id="examAction">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="tableList">
        <?php 
            $selQuest = $conn->query("SELECT q.id, q.exam_question, q.exam_ch1, q.exam_ch2, q.exam_ch3, q.exam_ch4 
                                      FROM exam_questions eq 
                                      INNER JOIN questions q ON q.id = eq.question_id 
                                      WHERE eq.exam_id='$examId' ORDER BY q.id");
            if($selQuest->rowCount() > 0)
            {
                $i = 1;
                while ($selQuestRow = $selQuest->fetch(PDO::FETCH_ASSOC)) { 
                    $questId = $selQuestRow['id']; ?>
                    <tr>
                        <td>
                            <p><b><?php echo $i++ ; ?> .) <?php echo $selQuestRow['exam_question']; ?></b></p>
                            <div class="form-group pl-4">
                                <?php $name = "answer[{$questId}][correct]"; ?>
                                <div class="custom-control custom-radio mb-1">
                                    <input class="custom-control-input" type="radio" id="q<?php echo $questId; ?>_a" 
                                           name="<?php echo $name; ?>" 
                                           value="<?php echo htmlspecialchars($selQuestRow['exam_ch1'], ENT_QUOTES); ?>">
                                    <label class="custom-control-label" for="q<?php echo $questId; ?>_a">
                                        A - <?php echo $selQuestRow['exam_ch1']; ?>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input class="custom-control-input" type="radio" id="q<?php echo $questId; ?>_b" 
                                           name="<?php echo $name; ?>" 
                                           value="<?php echo htmlspecialchars($selQuestRow['exam_ch2'], ENT_QUOTES); ?>">
                                    <label class="custom-control-label" for="q<?php echo $questId; ?>_b">
                                        B - <?php echo $selQuestRow['exam_ch2']; ?>
                                    </label>
                                </div>
                                <?php if (!empty($selQuestRow['exam_ch3'])): ?>
                                <div class="custom-control custom-radio mb-1">
                                    <input class="custom-control-input" type="radio" id="q<?php echo $questId; ?>_c" 
                                           name="<?php echo $name; ?>" 
                                           value="<?php echo htmlspecialchars($selQuestRow['exam_ch3'], ENT_QUOTES); ?>">
                                    <label class="custom-control-label" for="q<?php echo $questId; ?>_c">
                                        C - <?php echo $selQuestRow['exam_ch3']; ?>
                                    </label>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($selQuestRow['exam_ch4'])): ?>
                                <div class="custom-control custom-radio mb-1">
                                    <input class="custom-control-input" type="radio" id="q<?php echo $questId; ?>_d" 
                                           name="<?php echo $name; ?>" 
                                           value="<?php echo htmlspecialchars($selQuestRow['exam_ch4'], ENT_QUOTES); ?>">
                                    <label class="custom-control-label" for="q<?php echo $questId; ?>_d">
                                        D - <?php echo $selQuestRow['exam_ch4']; ?>
                                    </label>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="padding: 20px;">
                        <button type="button" class="btn btn-xlg btn-warning p-3 pl-4 pr-4" id="resetExamFrm">Reset</button>
                        <input name="submit" type="submit" value="Submit" 
                               class="btn btn-xlg btn-primary p-3 pl-4 pr-4 float-right" id="submitAnswerFrmBtn">
                    </td>
                </tr>
            <?php } else { ?>
                <b>No question at this moment</b>
            <?php } ?>   
        </table>
        </form>
    </div>
</div>







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Timer functionality
document.addEventListener('DOMContentLoaded', function() {
    var examTimeLimit = parseInt(document.getElementById('timeExamLimit').value) * 60;
    var timeLeft = examTimeLimit;
    var timerInterval;
    
    // Initialize timer display
    updateTimerDisplay();
    
    function updateTimerDisplay() {
        var minutes = Math.floor(timeLeft / 60);
        var seconds = timeLeft % 60;
        document.getElementById('txt').value = (minutes < 10 ? '0' + minutes : minutes) + ":" + (seconds < 10 ? '0' + seconds : seconds);
    }
    
    function startTimer() {
        timerInterval = setInterval(function() {
            timeLeft--;
            updateTimerDisplay();
            
            // Check if time is up
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                console.log("Time's up! Auto-submitting...");
                autoSubmitExam();
            }
        }, 1000);
    }
    
    function pickRandomAnswers() {
        // Loop over each question row
        document.querySelectorAll("#tableList tr").forEach(row => {
            const radios = row.querySelectorAll("input[type=radio]");
            if (radios.length > 0) {
                // Check if already answered
                const answered = Array.from(radios).some(r => r.checked);
                if (!answered) {
                    // Randomly select one option
                    const randomIndex = Math.floor(Math.random() * radios.length);
                    radios[randomIndex].checked = true;
                    console.log("Selected random answer for question");
                }
            }
        });
    }
    
    function autoSubmitExam() {
        console.log("Auto-submitting exam...");
        // Pick random answers for unanswered questions
        pickRandomAnswers();
        
        // Mark as timeout action
        document.getElementById("examAction").value = "timeout";
        
        // Submit directly without confirmation
        submitFormDirectly();
    }
    
    function submitFormDirectly() {
        console.log("Submitting form directly...");
        // Create form data
        var formData = new FormData(document.getElementById('submitAnswerFrm'));
        
        // Submit via AJAX
        fetch('query/submitAnswerExe.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Submission response:", data);
            if(data.res == "alreadyTaken") {
                Swal.fire('Already Taken', "You already took this exam", 'error');
            }
            else if(data.res == "success") {
                // Show success message for auto-submit
                Swal.fire({
                    title: 'Success',
                    text: "Your answer was automatically submitted due to time out!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        // Redirect to results page
                        var exam_id = document.getElementById('exam_id').value;
                        window.location.href = 'home.php?page=result&id=' + exam_id;
                    }
                });
            }
            else if(data.res == "failed") {
                Swal.fire('Error', "Something went wrong", 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', "Something went wrong", 'error');
        });
    }
    
    // Override the alert function to detect the time's up message
    var originalAlert = window.alert;
    window.alert = function(message) {
        if (message.includes("your time is over") || message.includes("please click ok")) {
            // Time's up alert detected - auto submit
            autoSubmitExam();
            return; // Don't show the actual alert
        }
        // For other alerts, use the original function
        originalAlert.apply(window, arguments);
    };
    
    // Form submission handler
    document.getElementById('submitAnswerFrm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var examAction = document.getElementById('examAction').value;
        
        // If auto-submit, let the autoSubmitExam function handle it
        if (examAction === "timeout") {
            return false;
        }
        
        // Check if at least one question is answered
        var answeredQuestions = document.querySelectorAll('input[type=radio]:checked').length;
        if (answeredQuestions === 0) {
            Swal.fire({
                title: 'No Answers Selected',
                text: "Please answer at least one question before submitting, or we'll automatically select random answers for you.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Select Random Answers',
                cancelButtonText: 'I\'ll Answer Questions'
            }).then((result) => {
                if (result.value) {
                    // User chose to have random answers selected
                    pickRandomAnswers();
                    // Continue with submission
                    submitManualForm();
                }
            });
            return false;
        }
        
        // If answers exist, proceed with confirmation
        submitManualForm();
    });
    
    function submitManualForm() {
        // Manual submission with confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to submit your answer now?",
            icon: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit now!'
        }).then((result) => {
            if (result.value) {
                // Submit manual form
                var formData = new FormData(document.getElementById('submitAnswerFrm'));
                formData.append('examAction', 'manual');
                
                fetch('query/submitAnswerExe.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.res == "alreadyTaken") {
                        Swal.fire('Already Taken', "You already took this exam", 'error');
                    }
                    else if(data.res == "success") {
                        Swal.fire({
                            title: 'Success',
                            text: "Your answer was successfully submitted!",
                            icon: 'success',
                            allowOutsideClick: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.value) {
                                var exam_id = document.getElementById('exam_id').value;
                                window.location.href = 'home.php?page=result&id=' + exam_id;
                            }
                        });
                    }
                    else if(data.res == "failed") {
                        Swal.fire('Error', "Something went wrong", 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', "Something went wrong", 'error');
                });
            }
        });
    }
    
    // Reset button handler
    document.getElementById('resetExamFrm').addEventListener('click', function() {
        document.querySelectorAll('input[type=radio]').forEach(radio => {
            radio.checked = false;
        });
    });
    
    // Start the timer
    startTimer();
    console.log("Timer started with", timeLeft, "seconds remaining");
});
</script>