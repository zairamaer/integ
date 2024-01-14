<?php

require 'vendor/autoload.php';
require 'config.php';


session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

                  $mail = new PHPMailer(true); 
                  try {
                      // Server settings
                      $mail->isSMTP();
                          $mail->Host       = 'smtp.gmail.com'; // Specify your SMTP server
                          $mail->SMTPAuth   = true;
                          $mail->Username   = 'dharklike@gmail.com'; // SMTP username
                          $mail->Password   = 'yweq hmqy knat zrdr'; // SMTP password
                          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                          $mail->Port       = 587; // TCP port to connect to
              
                          // Recipients
                          $mail->setFrom('zairamaer@gmail.com', 'ExploreEra Support'); // Replace with your information
                          $mail->addAddress($_SESSION['login']); // Add recipient
              
                          // Content
                          $mail->isHTML(true); // Set email format to HTML
                          $mail->Subject = 'Payment Confirmation';
                        $bkid = $_GET['bkid'];
                        $mail->Body = "Thank you for your booking! Your payment has been processed successfully." . '</br>' . "Your booking ID is: " . $bkid;
              
                          // Send the verification email
                          $mail->send();
                          echo '<script>alert("Email Receipt has been sent successfully");</script>';
                      
              
                  }
              
                  catch (Exception $e){ 
                      echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
                  }
                            ?>