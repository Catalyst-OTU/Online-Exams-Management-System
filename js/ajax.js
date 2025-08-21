// $(document).on("submit","#studentLoginFrm", function(e){
//   e.preventDefault(); // safer than return false
//   console.log("Form submitted");  // <-- check this first

//   $.post("query/loginExe.php", $(this).serialize(), function(data){
//      console.log("Response:", data);
//      if(data.res === "invalid") {
//        Swal.fire(
//          'Invalid',
//          'Please input valid email / password',
//          'error'
//        );
//      }
//      else if(data.res === "success") {
//        $('body').fadeOut();
//        window.location.href='home.php';
//      }
//   }, 'json').fail(function(xhr){
//      console.log("AJAX Error:", xhr.responseText);
//   });
// });





// Submit Answer
// ✅ Manual submit (with confirmation)
// $(document).on('submit', '#submitAnswerFrm', function(e){
//   e.preventDefault();
//   var examAction = $('#examAction').val();

//   // If this is an auto-submit (timeout), let the autoSubmitAjax function handle it
//   if(examAction === "timeout") {
//     return false;
//   }

//   // Manual submit with SweetAlert confirmation
//   Swal.fire({
//     title: 'Are you sure?',
//     text: "You want to submit your answer now?",
//     icon: 'warning',
//     showCancelButton: true,
//     allowOutsideClick: false,
//     confirmButtonColor: '#3085d6',
//     cancelButtonColor: '#d33',
//     confirmButtonText: 'Yes, submit now!'
//   }).then((result) => {
//     if (result.value) {
//       // Set examAction to manual for tracking
//       $('#examAction').val('manual');
      
//       $.ajax({
//         url: "query/submitAnswerExe.php",
//         type: "POST",
//         data: $('#submitAnswerFrm').serialize(),
//         dataType: 'json',
//         success: function(data) {
//           if(data.res == "alreadyTaken") {
//             Swal.fire('Already Taken', "You already took this exam", 'error');
//           }
//           else if(data.res == "success") {
//             Swal.fire({
//               title: 'Success',
//               text: "Your answer was successfully submitted!",
//               icon: 'success',
//               allowOutsideClick: false,
//               confirmButtonColor: '#3085d6',
//               confirmButtonText: 'OK!'
//             }).then((result) => {
//               if (result.value) {
//                 $('#submitAnswerFrm')[0].reset();
//                 var exam_id = $('#exam_id').val();
//                 window.location.href = 'home.php?page=result&id=' + exam_id;
//               }
//             });
//           }
//           else if(data.res == "failed") {
//             Swal.fire('Error', "Something went wrong", 'error');
//           }
//         },
//         error: function() {
//           Swal.fire('Error', "Something went wrong", 'error');
//         }
//       });
//     }
//   });
  
//   return false;
// });





// Submit Feedbacks
$(document).on("submit","#addFeebacks", function(){
   $.post("query/submitFeedbacksExe.php", $(this).serialize(), function(data){
      if(data.res == "limit")
      {
        Swal.fire(
          'Error',
          'You reached the 3 limit maximum for feedbacks',
          'error'
        )
      }
      else if(data.res == "success")
      {
        Swal.fire(
          'Success',
          'your feedbacks has been submitted successfully',
          'success'
        )
          $('#addFeebacks')[0].reset();
        
      }
   },'json');

   return false;
});

