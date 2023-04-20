<?php
// check if CV was uploaded successfully

if (isset($_FILES["cv_file"]) && $_FILES["cv_file"] ["error"] == UPLOAD_ERR_OK){

    // define necessary fields to extract
    $fields = ["name", "email", "phone", "education", "work_experience", "skills"];

//path to uploaded cv
    $cv_path = $_FILES["cv_file"]["tmp_name"];

 // Use Tika library to extract text content from PDF file
 $text_content = shell_exec("java -jar tika-app-2.0.0.jar --text $cv_path");

 // Extract name
 $name_pattern = "/([A-Z][a-z]+([ ]?[A-Z][a-z]+)+)/";
 preg_match($name_pattern, $text_content, $name_matches);
 $candidate_name = $name_matches[1] ?? "";

 // Extract email address
 $email_pattern = "/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/";
 preg_match($email_pattern, $text_content, $email_matches);
 $candidate_email = $email_matches[0] ?? "";

 // Extract phone number
 $phone_pattern = "/(\d{3}[-\.\s]??\d{3}[-\.\s]??\d{4}|\+\d{1,2}[-\.\s]??\d{10})/";
 preg_match($phone_pattern, $text_content, $phone_matches);
 $candidate_phone = $phone_matches[1] ?? "";

 // Extract education history
 $education_pattern = "/(?i)education(.*?)(?=(work|experience|skill))/";
 preg_match($education_pattern, $text_content, $education_matches);
 $candidate_education = $education_matches[1] ?? "";

 // Extract work experience
 $work_pattern = "/(?i)work(.*?)(?=(education|skill))/";
 preg_match($work_pattern, $text_content, $work_matches);
 $candidate_work_experience = $work_matches[1] ?? "";

 // Extract skills
 $skills_pattern = "/(?i)skills(.*?)(?=(education|work))/";
 preg_match($skills_pattern, $text_content, $skills_matches);
 $candidate_skills = $skills_matches[1] ?? "";

 // Display the extracted information
 echo "<p><b>Candidate Name:</b> $candidate_name</p>";
 echo "<p><b>Candidate Email:</b> $candidate_email</p>";
 echo "<p><b>Candidate Phone:</b> $candidate_phone</p>";
 echo "<p><b>Candidate Education:</b> $candidate_education</p>";
 echo "<p><b>Candidate Work Experience:</b> $candidate_work_experience</p>";
 echo "<p><b>Candidate Skills:</b> $candidate_skills</p>";

 // Store the extracted information in a database or file
 $cv_data = array(
   "name" => $candidate_name,
   "email" => $candidate_email,
   "phone" => $candidate_phone,
   "education" => $candidate_education,
   "work_experience" => $candidate_work_experience,
   "skills" => $candidate_skills
 );
}


?>