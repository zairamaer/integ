<?php
include('includes/config.php');
error_reporting(0);
session_start();

if(isset($_POST['submitted']))
{
    $issue = $_POST['issue'];
    $description = $_POST['description'];
    $email = $_SESSION['login'];
    
    try {
        $sql = "INSERT INTO tblissues(UserEmail, Issue, Description) VALUES(:email, :issue, :description)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':issue', $issue, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        
        if ($query->execute()) {
            header('location: thankyou.php');
            $lastInsertId = $dbh->lastInsertId();
            $_SESSION['msg'] = "Info successfully submitted";
            echo "<script type='text/javascript'>alert('Info successfully submitted'); window.location.href = 'thankyou.php';</script>";
        } else {
            $_SESSION['msg'] = "Something went wrong. Please try again.";
            echo "<script type='text/javascript'>alert('Something went wrong. Please try again.'); window.location.href = 'thankyou.php';</script>";
        }
        
        if($lastInsertId)
        {
            $_SESSION['msg']="Info successfully submitted";
            echo "<script type='text/javascript'> document.location = 'thankyou.php'; </script>";
        }
        else 
        {
            $_SESSION['msg']="Something went wrong. Please try again.";
            echo "<script type='text/javascript'> document.location = 'thankyou.php'; </script>";
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Database error: " . $e->getMessage();
        echo "<script type='text/javascript'>alert('Database error: " . $e->getMessage() . "'); window.location.href = 'thankyou.php';</script>";
    }
}
?>	

<html>


<?php echo "Hello" ?>
</html>