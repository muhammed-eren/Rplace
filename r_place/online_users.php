<?php
// Oturum kaydını başlat
session_start();

// İşlem tipini kontrol et
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Kullanıcı bilgilerini saklayacak dosya
$userFile = 'online_users.json';

// Dosya yoksa oluştur
if (!file_exists($userFile)) {
    file_put_contents($userFile, json_encode(['users' => [], 'lastCleanup' => time()]));
}

// Dosyadan verileri oku
$data = json_decode(file_get_contents($userFile), true);

// 5 dakikadan eski kullanıcıları temizle (300 saniye)
$currentTime = time();
if ($currentTime - $data['lastCleanup'] > 10) { // Her dakika temizlik yap
    foreach ($data['users'] as $sessionId => $lastActive) {
        if ($currentTime - $lastActive > 60) { // 5 dakikadan eski
            unset($data['users'][$sessionId]);
        }
    }
    $data['lastCleanup'] = $currentTime;
}

// Kullanıcı aktivitesini güncelle
if ($action === 'ping') {
    $data['users'][session_id()] = $currentTime;
}

// Değişiklikleri kaydet
file_put_contents($userFile, json_encode($data));

// Online kullanıcı sayısını döndür
echo json_encode(['count' => count($data['users'])]);
?>