console.log('ajax.js loaded');
// Admin Log in
$(document).on("submit","#adminLoginFrm", function(){
   $.post("query/loginExe.php", $(this).serialize(), function(data){
      if(data.res == "invalid")
      {
        Swal.fire(
          'Invalid',
          'Please input valid username / password',
          'error'
        )
      }
      else if(data.res == "success")
      {
        $('body').fadeOut();
        window.location.href='home.php';
      }
   },'json');

   return false;
});



// Add Course 
$(document).on("submit","#addCourseFrm" , function(e){
  e.preventDefault(); // prevent default submit

  var $btn = $(this).find('button[type="submit"],input[type="submit"]');
  $btn.prop('disabled', true);

  // --- VALIDATION ---
  var courseName = $.trim($("#course_name").val());

  // 1. Required field
  if(courseName === ""){
    Swal.fire("Validation Error", "Course name cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // 2. Prevent HTML/Script injection
  var pattern = /^[a-zA-Z0-9\s\-\_]+$/; // allow only letters, numbers, spaces, - and _
  if(!pattern.test(courseName)){
    Swal.fire("Validation Error", "Invalid course name. Only letters, numbers, spaces, - and _ are allowed.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // 3. Optional: Length check
  if(courseName.length < 3 || courseName.length > 100){
    Swal.fire("Validation Error", "Course name must be between 3 and 100 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // --- AJAX Submit ---
  $.post("query/addCourseExe.php", $(this).serialize() , function(data){
    if(data.res == "exist")
    {
      Swal.fire(
        'Already Exist',
        data.course_name.toUpperCase() + ' Already Exist',
        'error'
      )
    }
    else if(data.res == "success")
    {
      Swal.fire(
        'Success',
        data.course_name.toUpperCase() + ' Successfully Added',
        'success'
      )
      refreshDiv();
      setTimeout(function(){ 
          $('#body').load(document.URL);
       }, 2000);
    }
    $btn.prop('disabled', false);
  },'json')
  .fail(function(){ $btn.prop('disabled', false); });
});










// Update Course
$(document).on("submit","#updateCourseFrm" , function(e){
  e.preventDefault(); // stop default submit

  var $btn = $(this).find('button[type="submit"],input[type="submit"]');
  $btn.prop('disabled', true);

  // --- VALIDATION ---
  var courseName = $.trim($(this).find('[name="newCourseName"]').val());

  // Required field
  if(courseName === ""){
    Swal.fire("Validation Error", "Course name cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // Only allow safe characters (letters, numbers, space, dash, underscore)
  var pattern = /^[a-zA-Z0-9\s\-\_]+$/;
  if(!pattern.test(courseName)){
    Swal.fire("Validation Error", "Invalid course name. Only letters, numbers, spaces, - and _ are allowed.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // Length check
  if(courseName.length < 3 || courseName.length > 100){
    Swal.fire("Validation Error", "Course name must be between 3 and 100 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // --- AJAX Submit ---
  $.post("query/updateCourseExe.php", $(this).serialize() , function(data){
     if(data.res == "success")
     {
        Swal.fire(
            'Success',
            'Selected course has been successfully updated!',
            'success'
        )
        refreshDiv();
     }
     $btn.prop('disabled', false);
  },'json')
  .fail(function(){ $btn.prop('disabled', false); });

  return false;
});






$(document).on("click", "#deleteCourse", function(e){
    e.preventDefault();
    var id = $(this).data("id");
     $.ajax({
      type : "post",
      url : "query/deleteCourseExe.php",
      dataType : "json",  
      data : {id:id},
      cache : false,
      success : function(data){
        if(data.res == "success")
        {
          Swal.fire(
            'Success',
            'Selected Course successfully deleted',
            'success'
          )
          refreshDiv();
        }
      },
      error : function(xhr, ErrorStatus, error){
        console.log(status.error);
      }

    });
    
   

    return false;
  });


// Delete Course
// $(document).on("click", "#deleteCourse", function(e){
//     e.preventDefault();
//     var id = $(this).data("id");
//     var name = $(this).data("name");
//     Swal.fire({
//       title: 'Are you sure?',
//       html: 'Are you sure you want to delete this course?<br><b>' + (name || '') + '</b>',
//       icon: 'warning',
//       showCancelButton: true,
//       confirmButtonText: 'Yes, delete it!',
//       cancelButtonText: 'Cancel'
//     }).then(function(result){
//       if(result.isConfirmed){
//         $.ajax({
//           type : "post",
//           url : "query/deleteCourseExe.php",
//           dataType : "json",  
//           data : {id:id},
//           cache : false,
//           success : function(data){
//             if(data.res == "success")
//             {
//               Swal.fire(
//                 'Success',
//                 'Selected Course successfully deleted',
//                 'success'
//               )
//               refreshDiv();
//             }
//           },
//           error : function(xhr, ErrorStatus, error){
//             console.log(error);
//           }
//         });
//       }
//     });
//     return false;
//   });


// Delete Exam
$(document).on("click", "#deleteExam", function(e){
    e.preventDefault();
    var id = $(this).data("id");
     $.ajax({
      type : "post",
      url : "query/deleteExamExe.php",
      dataType : "json",  
      data : {id:id},
      cache : false,
      success : function(data){
        if(data.res == "success")
        {
          Swal.fire(
            'Success',
            'Selected Course successfully deleted',
            'success'
          )
          refreshDiv();
        }
      },
      error : function(xhr, ErrorStatus, error){
        console.log(status.error);
      }

    });
    
   

    return false;
  });



// Add Exam 
$(document).on("submit","#addExamFrm" , function(e){
  e.preventDefault();

  var $btn = $(this).find('button[type="submit"],input[type="submit"]');
  $btn.prop('disabled', true);

  // --- VALIDATION ---
  var examTitle = $.trim($(this).find('[name="examTitle"]').val());
  var examDesc  = $.trim($(this).find('[name="examDesc"]').val());

  // 1. Required fields
  if(examTitle === ""){
    Swal.fire("Validation Error", "Exam Title cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(examDesc === ""){
    Swal.fire("Validation Error", "Exam Description cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // 2. Allowed characters (letters, numbers, spaces, punctuation)
  var titlePattern =/^[a-zA-Z0-9\s\-\_\.\,]+$/;
  if(!titlePattern.test(examTitle)){
    Swal.fire("Validation Error", "Exam Title contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // 3. Length check
  if(examTitle.length < 3 || examTitle.length > 150){
    Swal.fire("Validation Error", "Exam Title must be between 3 and 150 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(examDesc.length < 5 || examDesc.length > 500){
    Swal.fire("Validation Error", "Exam Description must be between 5 and 500 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // --- AJAX Submit ---
  $.post("query/addExamExe.php", $(this).serialize() , function(data){
    if(data.res == "noSelectedCourse")
    {
      Swal.fire('No Course', 'Please select course', 'error')
    }
    else if(data.res == "noSelectedTime")
    {
      Swal.fire('No Time Limit', 'Please select time limit', 'error')
    }
    else if(data.res == "exist")
    {
      Swal.fire(
        'Already Exist',
        data.examTitle.toUpperCase() + '<br>Already Exist',
        'error'
      )
    }
    else if(data.res == "success")
    {
      Swal.fire(
        'Success',
        data.examTitle.toUpperCase() + '<br>Successfully Added',
        'success'
      )
      $('#addExamFrm')[0].reset();
      $('#course_name').val("");
      refreshDiv();
    }
    $btn.prop('disabled', false);
  },'json')
  .fail(function(){ $btn.prop('disabled', false); });

  return false;
});




// Update Exam 
$(document).on("submit","#updateExamFrm" , function(e){
  e.preventDefault();

  var $btn = $(this).find('button[type="submit"],input[type="submit"]');
  $btn.prop('disabled', true);

  // --- VALIDATION ---
  var examTitle = $.trim($(this).find('[name="examTitle"]').val());
  var examDesc  = $.trim($(this).find('[name="examDesc"]').val());

  // Required
  if(examTitle === ""){
    Swal.fire("Validation Error", "Exam Title cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(examDesc === ""){
    Swal.fire("Validation Error", "Exam Description cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // ✅ Allow letters, numbers, spaces, hyphens, underscore, period, comma
  var titlePattern = /^[a-zA-Z0-9\s\-\_\.\,]+$/;
  if(!titlePattern.test(examTitle)){
    Swal.fire("Validation Error", "Exam Title contains invalid characters. Allowed: letters, numbers, spaces, hyphen (-), underscore (_), period (.), comma (,)", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // Length check
  if(examTitle.length < 3 || examTitle.length > 150){
    Swal.fire("Validation Error", "Exam Title must be between 3 and 150 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(examDesc.length < 5 || examDesc.length > 500){
    Swal.fire("Validation Error", "Exam Description must be between 5 and 500 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // --- AJAX Submit ---
  $.post("query/updateExamExe.php", $(this).serialize() , function(data){
    if(data.res == "success")
    {
      Swal.fire(
        'Update Successfully',
        data.msg + ' <br>are now successfully updated',
        'success'
      )
      refreshDiv();
    }
    else if(data.res == "failed")
    {
      Swal.fire(
        "Something went wrong!",
        "Please try again later",
        "error"
      )
    }
    $btn.prop('disabled', false);
  },'json')
  .fail(function(){ $btn.prop('disabled', false); });

  return false;
});








// Update Question
$(document).on("submit","#updateQuestionFrm" , function(){
  console.log('Submitting updateQuestionFrm');
  $.post("query/updateQuestionExe.php", $(this).serialize() , function(data){
     console.log('Update response:', data);
     if(data && data.res == "success")
     {
        Swal.fire(
            'Success',
            'Selected question has been successfully updated!',
            'success'
          )
          refreshDiv();
     } else {
        console.warn('Update failed or unexpected response');
        Swal.fire('Update Failed', 'Please try again.', 'error');
     }
  },'json').fail(function(xhr, status, err){
    console.error('Update AJAX error:', status, err, xhr && xhr.responseText);
    Swal.fire('Error', 'Network or server error while updating.', 'error');
  });
  return false;
});





// Delete Question
$(document).on("click", "#deleteQuestion", function(e){
    e.preventDefault();
    var id = $(this).data("id");
     $.ajax({
      type : "post",
      url : "query/deleteQuestionExe.php",
      dataType : "json",  
      data : {id:id},
      cache : false,
      success : function(data){
        if(data.res == "success")
        {
          Swal.fire(
            'Deleted Success',
            'Selected question successfully deleted',
            'success'
          )
          refreshDiv();
        }
      },
      error : function(xhr, ErrorStatus, error){
        console.log(status.error);
      }

    });
    
   

    return false;
  });


// Add Question 
$(document).on("submit","#addQuestionFrm" , function(e){
  e.preventDefault();

  var $btn = $(this).find('button[type="submit"],input[type="submit"]');
  $btn.prop('disabled', true);

  // --- Grab values ---
  var question      = $.trim($(this).find('[name="question"]').val());
  var choiceA       = $.trim($(this).find('[name="choice_A"]').val());
  var choiceB       = $.trim($(this).find('[name="choice_B"]').val());
  var choiceC       = $.trim($(this).find('[name="choice_C"]').val());
  var choiceD       = $.trim($(this).find('[name="choice_D"]').val());
  var correctAnswer = $.trim($(this).find('[name="correctAnswer"]').val());

  // --- Validation Rules ---
  // Required checks
  if(question === ""){
    Swal.fire("Validation Error", "Question cannot be empty.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(choiceA === "" || choiceB === ""){
    Swal.fire("Validation Error", "Choices A and B are required.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(correctAnswer === ""){
    Swal.fire("Validation Error", "Correct Answer is required.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // Allow letters, numbers, spaces, punctuation (-_.?,!)
  var textPattern = /^[a-zA-Z0-9\s\-\_\.\,\?\!]+$/;

  if(!textPattern.test(question)){
    Swal.fire("Validation Error", "Question contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  if(choiceA && !textPattern.test(choiceA)){
    Swal.fire("Validation Error", "Choice A contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(choiceB && !textPattern.test(choiceB)){
    Swal.fire("Validation Error", "Choice B contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(choiceC && !textPattern.test(choiceC)){
    Swal.fire("Validation Error", "Choice C contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(choiceD && !textPattern.test(choiceD)){
    Swal.fire("Validation Error", "Choice D contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }
  if(!textPattern.test(correctAnswer)){
    Swal.fire("Validation Error", "Correct Answer contains invalid characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // Length check (you can tweak limits)
  if(question.length < 5 || question.length > 500){
    Swal.fire("Validation Error", "Question must be between 5 and 500 characters.", "warning");
    $btn.prop('disabled', false);
    return false;
  }

  // --- AJAX Submit ---
  $.post("query/addQuestionExe.php", $(this).serialize() , function(data){
    if(data.res == "exist")
    {
      Swal.fire(
        'Already Exist',
        data.msg + ' question <br>already exist in this exam',
        'error'
      )
    }
    else if(data.res == "success")
    {
      Swal.fire(
        'Success',
        data.msg + ' question <br>Successfully added',
        'success'
      )
      $('#addQuestionFrm')[0].reset();
      refreshDiv();
    }
    $btn.prop('disabled', false);
  },'json')
  .fail(function(){ $btn.prop('disabled', false); });

  return false;
});





// Add Student
$(document).on("submit", "#addStudentFrm", function(e) {
  e.preventDefault();

  let fullname = $("#fullname").val().trim();
  let birthdate = $("#bdate").val();
  let gender = $("#gender").val();
  let course = $("#course").val();
  let year_level = $("#year_level").val();
  let email = $("#email").val().trim();
  let password = $("#password").val();

  // Regex patterns
  let namePattern = /^[A-Za-z\s\-]+$/; // allows letters, spaces, hyphen
  let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  let passwordPattern = /^.{6,}$/; // at least 6 chars

  // Client-side validations
  if (!namePattern.test(fullname)) {
    Swal.fire("Invalid Fullname", "Only letters, spaces, and hyphens are allowed.", "error");
    return false;
  }

  if (gender == "0") {
    Swal.fire("No Gender", "Please select gender", "error");
    return false;
  }

  if (course == "0") {
    Swal.fire("No Course", "Please select course", "error");
    return false;
  }

  if (year_level == "0") {
    Swal.fire("No Year Level", "Please select year level", "error");
    return false;
  }

  if (!emailPattern.test(email)) {
    Swal.fire("Invalid Email", "Please enter a valid email address.", "error");
    return false;
  }

  if (!passwordPattern.test(password)) {
    Swal.fire("Weak Password", "Password must be at least 6 characters long.", "error");
    return false;
  }

  // Submit AJAX request if validations pass
  $.post("query/addStudentExe.php", $(this).serialize(), function(data) {
    if (data.res == "noGender") {
      Swal.fire("No Gender", "Please select gender", "error");
    }
    else if (data.res == "noCourse") {
      Swal.fire("No Course", "Please select course", "error");
    }
    else if (data.res == "noLevel") {
      Swal.fire("No Year Level", "Please select year level", "error");
    }
    else if (data.res == "fullnameExist") {
      Swal.fire("Fullname Already Exist", data.msg + " already exists", "error");
    }
    else if (data.res == "emailExist") {
      Swal.fire("Email Already Exist", data.msg + " already exists", "error");
    }
    else if (data.res == "success") {
      Swal.fire("Success", data.msg + " has been successfully added", "success");
      refreshDiv();
      $("#addStudentFrm")[0].reset();
    }
    else if (data.res == "failed") {
      Swal.fire("Something Went Wrong", "Please try again later", "error");
    }
  }, "json");

  return false;
});



// Update Student
$(document).on("submit", "#updateStudentFrm", function(e) {
  e.preventDefault();

  let fullname = $("input[name='fullname']").val().trim();
  let gender = $("select[name='gender']").val();
  let birthdate = $("input[name='birthdate']").val();
  let course = $("select[name='course_id']").val();
  let year_level = $("select[name='year_level']").val();
  let email = $("input[name='email']").val().trim();
  let password = $("input[name='password']").val().trim(); // optional
  let status = $("select[name='status']").val();

  // Regex patterns
  let namePattern = /^[A-Za-z\s\-]+$/; // letters, spaces, hyphens
  let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  let passwordPattern = /^.{6,}$/; // at least 6 chars (if filled)

  // Client-side validations
  if (!namePattern.test(fullname)) {
    Swal.fire("Invalid Fullname", "Only letters, spaces, and hyphens are allowed.", "error");
    return false;
  }

  if (!gender || gender === "0") {
    Swal.fire("No Gender", "Please select gender", "error");
    return false;
  }

  if (!birthdate) {
    Swal.fire("No Birthdate", "Please select birthdate", "error");
    return false;
  }

  if (!course || course === "0") {
    Swal.fire("No Course", "Please select course", "error");
    return false;
  }

  if (!year_level || year_level === "0") {
    Swal.fire("No Year Level", "Please select year level", "error");
    return false;
  }

  if (!emailPattern.test(email)) {
    Swal.fire("Invalid Email", "Please enter a valid email address.", "error");
    return false;
  }

  if (password && !passwordPattern.test(password)) {
    Swal.fire("Weak Password", "Password must be at least 6 characters long.", "error");
    return false;
  }

  if (!status) {
    Swal.fire("No Status", "Please select status", "error");
    return false;
  }

  // Submit via AJAX if validations pass
  $.post("query/updateStudentExe.php", $(this).serialize(), function(data) {
    if (data.res == "success") {
      Swal.fire(
        "Success",
        data.exFullname + " <br>has been successfully updated!",
        "success"
      );
      refreshDiv();
    } else if (data.res == "emailExist") {
      Swal.fire("Email Already Exist", data.msg + " already exists", "error");
    } else if (data.res == "fullnameExist") {
      Swal.fire("Fullname Already Exist", data.msg + " already exists", "error");
    } else if (data.res == "failed") {
      Swal.fire("Update Failed", "Something went wrong. Please try again.", "error");
    }
  }, "json");

  return false;
});



function refreshDiv()
{
  $('#tableList').load(document.URL +  ' #tableList');
  $('#refreshData').load(document.URL +  ' #refreshData');

}


