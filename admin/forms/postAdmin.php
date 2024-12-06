<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once  '../../controller/postController.php';
    $postController = new postController();

    if (isset($_POST['delete'])) {
        $postID = $_POST['postID'];
      
        $postController->deletePost($postID);
    }

    if (isset($_POST['accept'])) {
        $postID = $_POST['postID'];
        $postController->updatePostStatus($postID,'ACCEPTED');
    }
    header("Location: ../index.php");
}
?>