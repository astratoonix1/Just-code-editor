<?php
$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

$unique_number = time() . "_" . rand(1000, 9999);
$new_file_name = "RajDev_" . $unique_number . "." . $imageFileType;
$target_file = $target_dir . $new_file_name;
$error_msg = "";

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check === false) { $error_msg = "File image nahi hai."; $uploadOk = 0; }
}

if ($_FILES["fileToUpload"]["size"] > 10000000) {
    $error_msg = "Aapki file bahut badi hai (Max 10MB).";
    $uploadOk = 0;
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    $error_msg = "Sirf JPG, JPEG, PNG aur GIF files allow hain.";
    $uploadOk = 0;
}

$result_html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Status - Dev Cloud</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>.glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }</style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-black min-h-screen text-white flex items-center justify-center p-4 font-sans relative">
    <div class="max-w-lg w-full glass rounded-3xl p-10 text-center shadow-2xl relative z-10">';

if ($uploadOk == 0) {
    $result_html .= '<div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6"><i class="fa-solid fa-xmark text-4xl text-red-500"></i></div>
        <h2 class="text-3xl font-bold text-white mb-3">Upload Failed</h2>
        <p class="text-red-300 mb-8 bg-red-900/30 p-4 rounded-xl border border-red-500/30">'.$error_msg.'</p>';
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $domain = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $file_url = $protocol . "://" . $domain . $path . "/" . $target_file;
        
        $result_html .= '<div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_30px_rgba(34,197,94,0.3)]"><i class="fa-solid fa-check text-4xl text-green-500"></i></div>
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600 mb-2">Upload Successful!</h2>
            <p class="text-gray-300 mb-6 font-medium">Your image is live on Raj\'s server.</p>
            <div class="bg-black/50 p-4 rounded-xl border border-purple-500/30 break-all text-sm mb-6 flex flex-col items-center">
                <span class="text-xs text-purple-400 uppercase tracking-widest font-bold mb-2 block">Direct Link</span>
                <a href="'.$file_url.'" target="_blank" class="text-blue-400 hover:text-blue-300 underline font-mono">'.$file_url.'</a>
            </div>
            <div class="bg-white/5 p-4 rounded-xl border border-white/10 mb-8 flex justify-between items-center text-left">
                <div>
                    <p class="text-xs text-gray-400 uppercase">File Name</p>
                    <p class="text-sm font-semibold text-purple-300 font-mono">'.$new_file_name.'</p>
                </div>
                <i class="fa-solid fa-image text-2xl text-purple-500/50"></i>
            </div>';
    } else {
        $result_html .= '<div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6"><i class="fa-solid fa-triangle-exclamation text-4xl text-yellow-500"></i></div>
            <h2 class="text-3xl font-bold text-white mb-2">Oops! Error</h2>
            <p class="text-yellow-300 mb-8">Server mein upload karte waqt koi error aa gaya.</p>';
    }
}

$result_html .= '<a href="index.html" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold py-3 px-8 rounded-xl transition duration-300 border border-white/20 hover:border-white/40"><i class="fa-solid fa-arrow-left"></i> Upload Another</a></div></body></html>';

echo $result_html;
?>
