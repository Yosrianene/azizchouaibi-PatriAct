<?php

require_once '../../controller/reviewsController.php'; 

$reviewController = new reviewController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $reviewID = $_POST['reviewID'];
        
        if ($reviewController->deleteReview($reviewID)) {
            header("Location: ../index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error deleting the review. Please try again.</div>";
        }
    }

}
?>

