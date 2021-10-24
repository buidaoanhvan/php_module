<?php
if (!defined('_BLOCK_DEFAULT')) header("Location: ./?module=errors&action=404");
$data = [
    'page_title' => 'Đăng nhập'
];

layout('header', $data);
?>
    <div class="container">
        <div class="row">
            <div class="col-md-5" style="margin:80px auto;">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center text-uppercase mb-4 font-weight-bold">Đăng Nhập</h2>
                        <form action="" method="POST">
                            <div class="form-group mb-3">
                                <label class="form-label mx-2"><i class="fas fa-envelope"></i> Địa chỉ email:</label>
                                <input type="email" class="form-control" name="email" placeholder="Nhập email đăng ký" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label mx-2"><i class="fas fa-key"></i> Mật khẩu:</label>
                                <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu" required>
                            </div>
                            <div class="d-grid gap-3 col-12 mx-auto">
                                <button type="submit" class="btn btn-outline-primary" type="button"><i
                                            class="fas fa-sign-in-alt"></i> Đăng nhập
                                </button>
                                <a class="btn btn-outline-danger" href="?module=auth&action=forgot"><i
                                            class="fas fa-eye"></i> Quên mật khẩu</a>
                                <a class="btn btn-outline-success" href="?module=auth&action=register"><i
                                            class="fas fa-compass"></i> Đăng ký tài khoản</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
layout('footer');