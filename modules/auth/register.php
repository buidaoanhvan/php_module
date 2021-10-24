<?php
if (!defined('_BLOCK_DEFAULT')) header("Location: ./?module=errors&action=404");
$data = [
    'page_title' => 'Đăng ký tài khoản'
];
layout('header', $data);

if (isPost()) {
    $body = getBody();
    $errors = [];
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Vui lòng nhập Họ và Tên';
    } else {
        if (strlen($body['fullname']) <= 5 || !isName($body['fullname'])) {
            $errors['fullname']['errors'] = 'Họ tên của bạn chưa đúng';
        }
    }

    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Vui lòng nhập Email';
    } else {
        if (!isEmail($body['email'])) {
            $errors['email']['errors'] = 'Email của bạn chưa đúng';
        } else if (!checkOnlyField('users', 'email', $body['email'])) {
            $errors['email']['errors'] = 'Email của bạn đã tồn tại';
        }
    }

    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'Vui lòng nhập số điện thoại';
    } else {
        if (!isPhone($body['phone'])) {
            $errors['phone']['errors'] = 'Số điện thoại của bạn chưa đúng';
        }
    }

    if (empty(trim($body['password1'])) && empty(trim($body['password2']))) {
        $errors['password']['required'] = 'Vui lòng nhập mật khẩu';
    } else {
        if (!isPassword($body['password1'])) {
            $errors['password']['errors'] = 'Mật khẩu không hợp lệ';
        } else if ($body['password1'] != $body['password2']) {
            $errors['password']['errors'] = 'Mật khẩu không khớp';
        }
    }

    if (empty($errors)) {
        $activeToken = sha1(uniqid() . time());
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'password' => password_hash($body['password2'], PASSWORD_DEFAULT),
            'phone' => $body['phone'],
            'active_token' => $activeToken,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thành công vui lòng kiểm tra mail để kích hoạt');
            //tạo link active
            $linkActive = _URL_ROOT . '?module=auth&action=active&token=' . $activeToken;
            $email = $body['email'];
            $message = 'Bấm vào link sau để kích hoạy tài khoản: ' . $linkActive;
            //gửi mail
            sendMail($email,'Kích Hoạt Tài Khoản', $message);
            redirect('?module=auth&action=register');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập đầy đủ thông tin');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('?module=auth&action=register');
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>
<div class="container">
    <div class="row">
        <div class="col-md-6" style="margin:80px auto;">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center text-uppercase mb-3 font-weight-bold">Đăng ký</h2>
                    <?php
                    echo getMsg($msg, $msg_type);
                    ?>
                    <form action="" method="POST">
                        <div class="form-group mb-3">
                            <label class="form-label mx-2"><i class="fas fa-user"></i> Họ và tên:</label>
                            <input type="text" class="form-control" name="fullname"
                                   value="<?php echo formValue($old, 'fullname') ?>"
                                   placeholder="Nhập họ và tên">
                            <?php echo formError($errors, 'fullname') ?>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label mx-2"><i class="fas fa-mobile"></i> Số điện thoại:</label>
                            <input type="text" class="form-control" name="phone"
                                   value="<?php echo formValue($old, 'phone') ?>"
                                   placeholder="Nhập số điện thoại">
                            <?php echo formError($errors, 'phone') ?>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label mx-2"><i class="fas fa-envelope"></i> Địa chỉ email:</label>
                            <input type="text" class="form-control" name="email"
                                   value="<?php echo formValue($old, 'email') ?>"
                                   placeholder="Nhập email đăng ký">
                            <?php echo formError($errors, 'email') ?>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label mx-2"><i class="fas fa-key"></i> Mật khẩu:</label>
                            <input type="password" class="form-control" name="password1" placeholder="Nhập mật khẩu">
                            <?php echo formError($errors, 'password') ?>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label mx-2"><i class="fas fa-key"></i> Nhập lại mật khẩu:</label>
                            <input type="password" class="form-control" name="password2"
                                   placeholder="Nhập lại mật khẩu">
                            <?php echo formError($errors, 'password') ?>
                        </div>
                        <div class="d-grid gap-3 col-12 mx-auto">
                            <button type="submit" class="btn btn-outline-primary" type="button"><i
                                        class="fas fa-compass"></i> Đăng ký
                            </button>
                            <a class="btn btn-outline-success" href="?module=auth&action=login"><i
                                        class="fas fa-sign-in-alt"></i> Trang đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
layout('footer');
?>
