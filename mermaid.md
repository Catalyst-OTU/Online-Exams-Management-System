%% Online Exams Management System - Use Case Diagram
%% Actors
%% - Admin
%% - Student
%% System boundary contains all OEMS use cases
usecaseDiagram
  title Online Exams Management System - Use Case Diagram

  actor Admin as A
  actor Student as S

  rectangle OEMS {
    (Manage Courses) as UC1
    (Manage Exams) as UC2
    (Manage Exam Questions) as UC3
    (Manage Students) as UC4
    (View/Print Results) as UC5
    (View Feedback) as UC6

    (Take Exam) as UC7
    (View Results) as UC8
    (Submit Feedback) as UC9

    (Authenticate User) as UAuth
    (Auto-Grade MCQs) as UGrade
    (Record Attempt) as UAttempt
  }

  %% Admin relationships
  A --> UAuth
  A --> UC1
  A --> UC2
  A --> UC3
  A --> UC4
  A --> UC5
  A --> UC6

  %% Student relationships
  S --> UAuth
  S --> UC7
  S --> UC8
  S --> UC9

  %% Includes/extends inside the system
  UC7 ..> UAttempt : «include»
  UC7 ..> UGrade  : «include»
  UC5 ..> UGrade  : «include»
