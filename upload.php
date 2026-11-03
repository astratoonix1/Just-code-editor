<?php
$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// ==========================================
// CUSTOM NAME LOGIC: RajDev_Timestamp_Number
// ==========================================
$unique_number = time() . "_" . rand(1000, 9999);
$new_file_name = "RajDev_" . $unique_number . "." . $imageFileType;
$target_file = $target_dir . $new_file_name;
$error_msg = "";

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check === false) {
        $error_msg = "File image nahi hai.";
        $uploadOk = 0;
    }
}

if ($_FILES["fileToUpload"]["size"] > 10000000) { // 10MB limit
    $error_msg = "Aapki file bahut badi hai (Max 10MB).";
    $uploadOk = 0;
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    $error_msg = "Sirf JPG, JPEG, PNG aur GIF files allow hain.";
    $uploadOk = 0;
}

// Styling the Result Page with Tailwind & Animations
$result_html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Status - Dev Cloud</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        
        /* Success Checkmark Animation */
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-scale-in {
            animation: scaleIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        
        /* Slide up text */
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out 0.3s forwards;
            opacity: 0;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-black min-h-screen text-white flex items-center justify-center p-4 font-sans relative">
    
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-green-500/20 rounded-full filter blur-3xl"></div>
    </div>

    <div class="max-w-lg w-full glass rounded-3xl p-10 text-center shadow-2xl relative z-10 border border-white/10">';

if ($uploadOk == 0) {
    $result_html .= '
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6 animate-scale-in">
            <i class="fa-solid fa-xmark text-4xl text-red-500"></i>
        </div>
        <h2 class="text-3xl font-bold text-white mb-3 animate-slide-up">Upload Failed</h2>
        <p class="text-red-300 mb-8 bg-red-900/30 p-4 rounded-xl border border-red-500/30 animate-slide-up" style="animation-delay: 0.4s;">'.$error_msg.'</p>';
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $domain = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $file_url = $protocol . "://" . $domain . $path . "/" . $target_file;
        
        $result_html .= '
            <div class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_40px_rgba(34,197,94,0.4)] animate-scale-in">
                <i class="fa-solid fa-check text-5xl text-green-500 drop-shadow-[0_0_10px_rgba(34,197,94,0.8)]"></i>
            </div>
            
            <div class="animate-slide-up">
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600 mb-2">Upload Successful!</h2>
                <p class="text-gray-300 mb-6 font-medium">Your image is live on Raj\'s server.</p>
            </div>
            
            <div class="bg-black/50 p-5 rounded-xl border border-purple-500/30 break-all text-sm mb-6 flex flex-col items-center animate-slide-up relative group" style="animation-delay: 0.4s;">
                <span class="text-xs text-purple-400 uppercase tracking-widest font-bold mb-3 block"><i class="fa-solid fa-link mr-1"></i> Direct Link</span>
                <input type="text" id="linkInput" value="'.$file_url.'" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-blue-400 font-mono text-center outline-none focus:border-purple-500 transition-colors" readonly>
                <button onclick="copyLink()" class="absolute top-2 right-2 text-gray-400 hover:text-white bg-white/5 hover:bg-white/20 p-2 rounded-md transition-all">
                    <i class="fa-regular fa-copy"></i>
                </button>
                <span id="copyMsg" class="text-green-400 text-xs absolute -bottom-5 opacity-0 transition-opacity font-bold">Copied!</span>
            </div>
            
            <div class="bg-white/5 p-4 rounded-xl border border-white/10 mb-8 flex justify-between items-center text-left animate-slide-up" style="animation-delay: 0.5s;">
                <div>
                    <p class="text-xs text-gray-400 uppercase">File Name</p>
                    <p class="text-sm font-semibold text-purple-300 font-mono">'.$new_file_name.'</p>
                </div>
                <div class="w-12 h-12 rounded-lg overflow-hidden border border-white/20">
                    <img src="'.$target_file.'" class="w-full h-full object-cover">
                </div>
            </div>';
    } else {
        $result_html .= '
            <div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6 animate-scale-in">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-yellow-500"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2 animate-slide-up">Oops! Error</h2>
            <p class="text-yellow-300 mb-8 animate-slide-up" style="animation-delay: 0.4s;">Server mein upload karte waqt koi error aa gaya.</p>';
    }
}

$result_html .= '
        <a href="index_final.html" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold py-3 px-8 rounded-xl transition duration-300 border border-white/20 hover:border-white/40 animate-slide-up" style="animation-delay: 0.6s;">
            <i class="fa-solid fa-arrow-left"></i> Upload Another
        </a>
    </div>
    
    <script>
        function copyLink() {
            var copyText = document.getElementById("linkInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            var msg = document.getElementById("copyMsg");
            msg.style.opacity = "1";
            setTimeout(() => { msg.style.opacity = "0"; }, 2000);
        }
    </script>
</body>
</html>';

echo $result_html;
?>
