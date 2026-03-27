<?php
session_start();
if (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] == true) {
    header("Location:admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SkillShop</title>
    <link rel="icon" type="image/x-icon" href="./assets/images/competence.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#0f172a] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-[24px] overflow-hidden shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)]">
        <!-- Step 1: Email Input -->
        <div id="step1">
            <div class="bg-slate-900 p-10 text-center text-white">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-900/40">
                    <i class="fas fa-user-shield text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold tracking-tight">Admin Login</h1>
                <p class="text-slate-400 text-sm mt-2">Sign in to access control panel</p>
            </div>

            <div class="p-10">
                <div id="msg1" class="hidden p-4 rounded-xl text-sm font-medium text-center mb-6"></div>

                <form onsubmit="adminLogin(event)" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-3 ml-1">Email Address</label>
                        <input type="email" id="email" placeholder="e.g. admin@skillshop.com"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium text-slate-700">
                    </div>

                    <button type="submit" id="btn1" class="w-full py-4 bg-[#1a2332] text-white rounded-xl font-bold text-sm uppercase tracking-widest shadow-xl transition-all duration-300 hover:bg-[#2d3748] hover:-translate-y-0.5 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.2)]">
                        Send Verification Code
                    </button>
                </form>

                <div class="mt-8 text-center border-t border-slate-100 pt-8">
                    <a href="index.php" class="text-slate-500 text-sm font-semibold no-underline transition-colors hover:text-slate-900 group">
                        <i class="fas fa-arrow-left mr-2 text-xs transition-transform group-hover:-translate-x-1"></i> Back to SkillShop
                    </a>
                </div>
            </div>
        </div>

        <!-- Step 2: Verification Code -->
        <div id="step2" class="hidden">
            <div class="bg-[#1a2332] p-10 text-center text-white">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-900/40">
                    <i class="fas fa-lock text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold tracking-tight">Verification</h1>
                <p class="text-slate-400 text-sm mt-2" id="sentEmailLabel"></p>
            </div>

            <div class="p-10">
                <div id="msg2" class="hidden p-4 rounded-xl text-sm font-medium text-center mb-6"></div>

                <form onsubmit="adminVerify(event)" class="space-y-8">
                    <div class="text-center">
                        <label class="block text-sm font-bold text-slate-800 mb-6">Enter 6-Digit Code</label>
                        <input type="text" id="vcode" maxlength="6" placeholder="0 0 0 0 0 0"
                            class="w-full px-6 py-5 bg-slate-50 border border-slate-100 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all tracking-[0.5em] text-center font-bold text-2xl text-slate-400">
                    </div>

                    <button type="submit" id="btn2" class="w-full py-4 bg-[#1a2332] text-white rounded-xl font-bold text-sm uppercase tracking-widest shadow-xl transition-all duration-300 hover:bg-[#2d3748] hover:-translate-y-0.5 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.2)]">
                        Verify & Login
                    </button>

                    <div class="text-center">
                        <button type="button" onclick="goToStep1()" class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-slate-400 hover:text-blue-500 transition-colors">
                            Change Email
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center border-t border-slate-100 pt-8">
                    <a href="index.php" class="text-slate-500 text-sm font-semibold no-underline transition-colors hover:text-slate-900">
                         Back to SkillShop
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function adminLogin(e) {
            e.preventDefault();
            const email = document.getElementById("email");
            const msg = document.getElementById("msg1");
            const btn = document.getElementById("btn1");

            if (!email.value) {
                msg.innerHTML = "Please enter your email.";
                msg.className = "p-4 rounded-xl text-sm font-medium text-center mb-6 bg-red-50 text-red-600";
                msg.classList.remove("hidden");
                return;
            }

            btn.disabled = true;
            btn.innerHTML = "<i class='fas fa-circle-notch fa-spin mr-2'></i> Sending...";

            const f = new FormData();
            f.append("email", email.value);

            const r = new XMLHttpRequest();
            r.open("POST", "process/adminLoginProcess.php", true);
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    const text = r.responseText.trim();
                    if (text == "success") {
                        document.getElementById("step1").classList.add("hidden");
                        document.getElementById("step2").classList.remove("hidden");
                        document.getElementById("sentEmailLabel").innerText = "Code sent to " + email.value;
                        
                        // Show success msg in step 2 immediately
                        const msg2 = document.getElementById("msg2");
                        msg2.innerHTML = "Verification code sent to " + email.value;
                        msg2.className = "p-4 rounded-xl text-sm font-medium text-center mb-6 bg-green-50 text-green-700";
                        msg2.classList.remove("hidden");
                    } else {
                        msg.innerHTML = text;
                        msg.className = "p-4 rounded-xl text-sm font-medium text-center mb-6 bg-red-50 text-red-600";
                        msg.classList.remove("hidden");
                    }
                    btn.disabled = false;
                    btn.innerText = "Send Verification Code";
                }
            };
            r.send(f);
        }

        async function adminVerify(e) {
            e.preventDefault();
            const vcode = document.getElementById("vcode");
            const msg = document.getElementById("msg2");
            const btn = document.getElementById("btn2");

            if (!vcode.value) {
                msg.innerHTML = "Please enter the verification code.";
                msg.className = "p-4 rounded-xl text-sm font-medium text-center mb-6 bg-red-50 text-red-600";
                msg.classList.remove("hidden");
                return;
            }

            btn.disabled = true;
            btn.innerHTML = "<i class='fas fa-circle-notch fa-spin mr-2'></i> Verifying...";

            const f = new FormData();
            f.append("vcode", vcode.value);

            const r = new XMLHttpRequest();
            r.open("POST", "process/adminVerifyProcess.php", true);
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    const text = r.responseText.trim();
                    if (text == "success") {
                        msg.innerHTML = "Verification successful! Redirecting...";
                        msg.className = "p-4 rounded-xl text-sm font-medium text-center mb-6 bg-green-50 text-green-700";
                        msg.classList.remove("hidden");
                        setTimeout(() => {
                            window.location = "admin-dashboard.php";
                        }, 1500);
                    } else {
                        msg.innerHTML = text;
                        msg.className = "p-4 rounded-xl text-sm font-medium text-center mb-6 bg-red-50 text-red-600";
                        msg.classList.remove("hidden");
                    }
                    btn.disabled = false;
                    btn.innerText = "Verify & Login";
                }
            };
            r.send(f);
        }

        function goToStep1() {
            document.getElementById("step1").classList.remove("hidden");
            document.getElementById("step2").classList.add("hidden");
            document.getElementById("msg1").classList.add("hidden");
        }
    </script>
</body>

</html>