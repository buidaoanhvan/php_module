<?php
if (!defined('_BLOCK_DEFAULT')) header("Location: ./?module=errors&action=404");

function query($sql, $data = [], $status = 0)
{
    global $conn;
    $query = false;
    $statement = null;
    try {
        $statement = $conn->prepare($sql);
        if (!empty($data)) {
            $query = $statement->execute($data);
        } else {
            $query = $statement->execute();
        }
    } catch (Exception $e) {
        require_once 'modules/errors/404.php';
        die();
    }
    if ($status == 1) {
        return $statement;
    }
    return $query;
}

//Hàm thêm dữ liệu (tên bảng, dữ liệu cần thêm array)
function insert($table, $data)
{
    $value = implode(', :', array_keys($data));
    $key = str_replace(':', '', $value);
    $sql = "INSERT INTO " . $table . " (" . $key . ") VALUES (:" . $value . ")";
    return query($sql, $data);
}

//Hàm câp nhật dữ liệu theo id (tên bảng, dữ liệu cần thêm array , id)
function update($table, $data, $id)
{
    $value = "";
    foreach ($data as $key => $val) {
        $value .= $key . ' = :' . $key . ', ';
    }
    $value = rtrim($value, ', ');
    $data['id'] = $id;
    $sql = "UPDATE " . $table . " SET " . $value . " WHERE id = :id";
    return query($sql, $data);
}

//Hàm xoá dữ liệu theo id (tên bảng, dữ liệu cần thêm array , id)
function delete($table, $id)
{
    $data['id'] = $id;
    $sql = "DELETE FROM " . $table . " WHERE id = :id";
    return query($sql, $data);
}

//Hàm lấy dữ liệu theo bảng $field = "name, phone"
function show($table, $field = '*', $sort = 'asc')
{
    $sql = "SELECT " . $field . " FROM " . $table . " ORDER BY id " . $sort;
    return query($sql, null, 1)->fetchAll(PDO::FETCH_ASSOC);
}

//Hàm check duy nhất
function checkOnlyField($table, $field, $data)
{
    $sql = "SELECT $field FROM $table WHERE $field = '$data'";
    $data = query($sql, null, 1)->fetchAll(PDO::FETCH_ASSOC);
    if (empty($data)) {
        return true;
    }
    return false;
}