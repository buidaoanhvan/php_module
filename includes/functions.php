<?php
if (!defined('_BLOCK_DEFAULT')) header("Location: ./?module=errors&action=404");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function layout($layoutName, $data = [])
{
    if (file_exists('templates/layouts/' . $layoutName . '.php')) {
        require_once 'templates/layouts/' . $layoutName . '.php';
    }
}

//Gửi Mail
function sendMail($to, $subject, $message)
{
//Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = _SMTP_HOST;                       //Enable verbose debug output                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = _EMAIL_USER;                     //SMTP username
        $mail->Password = _EMAIL_KEY;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = _EMAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(_EMAIL_USER, 'Hệ Thống Quản Lý User');
        $mail->addAddress($to);     //Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mail->send();
    } catch (Exception $e) {
        require_once 'modules/errors/404.php';
        die();
    }
}

//Kiểm tra phương thức POST
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

//Kiểm tra phương thức GET
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}

function getBody()
{
    $bodyArray = [];
    if (isGet()) {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                //Xử lý an toàn thông tin bằng filter_input GET
                $key = strip_tags($key);
                if (is_array($value)) {
                    $bodyArray[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArray[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                //Xử lý an toàn thông tin bằng filter_input POST
                $key = strip_tags($key);
                if (is_array($value)) {
                    $bodyArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $bodyArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $bodyArray;
}

function isEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isName($name)
{
    if (preg_match("/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s\W|_]+$/", $name)) {
        return $name;
    }
    return false;
}

function isPhone($phone)
{
    if (preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/", $phone)) {
        return $phone;
    }
    return false;
}

function isPassword($password)
{
    if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $password)) {
        return $password;
    }
    return false;
}

function getMsg($msg, $msg_type)
{
    if (empty($msg_type)) {
        $msg_type = 'success';
    }
    if (!empty($msg)) {
        echo '<div class="alert alert-' . $msg_type . '" style="border-radius:16px">';
        echo $msg;
        echo '</div>';
    }
}

//hàm chuyển hướng
function redirect($part = "index.php")
{
    header("location: $part");
    exit;
}

//hàm thông báo lỗi form
function formError($errors, $fielname)
{
    if (!empty($errors[$fielname])) {
        return '<p class="text-danger mt-2 mx-2"><em>' . reset($errors[$fielname]) . '</em></p>';
    }
}

//hàm hiển thị dữ liệu cũ form
function formValue($oldValue, $fielname)
{
    if (!empty($oldValue[$fielname])) {
        return $oldValue[$fielname];
    }
}