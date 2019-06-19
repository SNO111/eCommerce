<?php 
session_start();
$pageTitle = 'Contact Us';
include('init.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name       = $_POST['name'];
            $email      = $_POST['email'];
            $subject    = $_POST['subject'];
            $msg        =  $_POST['message'];

            $formErrors = array();
            if(empty($name)) {
                $formErrors[] = 'Please enter your name';
            }
            if(empty($email)) {
                $formErrors[] = 'Please enter your email';
            }
            if(empty($subject)) {
                $formErrors[] = 'Please enter a subject';
            }
            if(empty($msg)) {
                $formErrors[] = 'Please leave us a Message';
            }
            foreach ($formErrors as $error) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8" style="margin: 0 auto;">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><?php echo $error;?></strong> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            <?php }
            if (empty($formErrors)) {
                $stmt = $db->prepare('INSERT INTO contact (name, email, subject, message, time) 
                                        VALUES (:zname, :zemail, :zsubject, :zmsg, now())');
                $row = $stmt->execute(array(
                    'zname'     => $name,       
                    'zemail'    => $email,      
                    'zsubject'  => $subject,   
                    'zmsg'      => $msg       
                ));

                // Echo success message
                if ($row) { ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8" style="margin: 0 auto;">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Message sent successfully</strong> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
               echo 'Something went wrong, please try again later!';
            }
            

    } // endif 

?>
<main>
    <div class="container">
        <form id="contact-form" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" role="form">

        <div class="messages"></div>

        <div class="controls">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="form_name">Name *</label>
                        <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your name *" required="required" data-error="Firstname is required.">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="form_email">Email *</label>
                        <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="form_subject">Subject *</label>
                        <input id="form_subject" type="text" name="subject" class="form-control" placeholder="Please enter a subject *" required="required" data-error="Valid email is required.">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="form_message">Message *</label>
                        <textarea id="form_message" name="message" class="form-control" placeholder="Message for us *" rows="4" required="required" data-error="Please, leave us a message."></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="btn btn-success btn-send" value="Send message">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-muted">
                        <strong>*</strong> These fields are required. 
                </div>
            </div>
        </div>

        </form>
    </div>
</main>
<?php 
    include $tpl .'footer.php';
?>
