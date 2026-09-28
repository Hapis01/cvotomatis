<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Signature;
use App\Models\Letterhead;
use App\Middleware\AuthMiddleware;
use App\Helpers\AuthHelper;

class SettingsController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index()
    {
        $sigModel = new Signature();
        $headModel = new Letterhead();
        
        $signature = current($sigModel->where('user_id', AuthHelper::id())) ?: null;
        $letterhead = current($headModel->where('user_id', AuthHelper::id())) ?: null;

        ob_start();
        require_once __DIR__ . '/../../resources/views/settings/index.php';
        $content = ob_get_clean();

        $this->view('layouts/app', [
            'title' => 'Settings',
            'content' => $content
        ]);
    }

    public function uploadSignature()
    {
        if (isset($_FILES['signature']) && $_FILES['signature']['error'] == UPLOAD_ERR_OK) {
            $tmpName = $_FILES['signature']['tmp_name'];
            $name = basename($_FILES['signature']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $dir = __DIR__ . '/../../storage/uploads/signature/';
                if (!file_exists($dir)) mkdir($dir, 0777, true);
                
                $newName = 'sig_' . AuthHelper::id() . '_' . time() . '.' . $ext;
                if (move_uploaded_file($tmpName, $dir . $newName)) {
                    $sigModel = new Signature();
                    $existing = current($sigModel->where('user_id', AuthHelper::id()));
                    
                    if ($existing) {
                        if (file_exists($dir . $existing->file_path)) unlink($dir . $existing->file_path);
                        $sigModel->update($existing->id, ['file_path' => $newName]);
                    } else {
                        $sigModel->create([
                            'user_id' => AuthHelper::id(),
                            'file_path' => $newName,
                            'is_default' => 1
                        ]);
                    }
                    $_SESSION['success'] = "Signature uploaded successfully!";
                }
            } else {
                $_SESSION['error'] = "Invalid image format.";
            }
        }
        $this->redirect('/settings');
    }

    public function uploadLetterhead()
    {
        if (isset($_FILES['letterhead']) && $_FILES['letterhead']['error'] == UPLOAD_ERR_OK) {
            $tmpName = $_FILES['letterhead']['tmp_name'];
            $name = basename($_FILES['letterhead']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $dir = __DIR__ . '/../../storage/uploads/letterhead/';
                if (!file_exists($dir)) mkdir($dir, 0777, true);
                
                $newName = 'head_' . AuthHelper::id() . '_' . time() . '.' . $ext;
                if (move_uploaded_file($tmpName, $dir . $newName)) {
                    $headModel = new Letterhead();
                    $existing = current($headModel->where('user_id', AuthHelper::id()));
                    
                    if ($existing) {
                        if (file_exists($dir . $existing->file_path)) unlink($dir . $existing->file_path);
                        $headModel->update($existing->id, [
                            'file_path' => $newName,
                            'alignment' => $_POST['alignment'] ?? 'center'
                        ]);
                    } else {
                        $headModel->create([
                            'user_id' => AuthHelper::id(),
                            'file_path' => $newName,
                            'alignment' => $_POST['alignment'] ?? 'center',
                            'is_default' => 1
                        ]);
                    }
                    $_SESSION['success'] = "Letterhead uploaded successfully!";
                }
            } else {
                $_SESSION['error'] = "Invalid image format.";
            }
        }
        $this->redirect('/settings');
    }
}
