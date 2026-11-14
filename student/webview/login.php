<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EdPlus Login</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f5f7fa;
    background-image: url('https://i.ibb.co/V9vKkKD/edu-pattern-bg.png');
    background-size: 180px;
    background-repeat: repeat;
}

.login-container {
    width: 100%;
    max-width: 400px;
    margin: auto;
    padding: 20px;
    padding-top: 40px;
    position: relative;
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(4px);
    border-radius: 12px;
    margin-top: 25px;
}

.faq-btn {
    position: absolute;
    right: 0;
    top: 10px;
    background: #005ea6;
    color: white;
    border: none;
    padding: 8px 22px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
}

.logo {
    text-align: center;
    font-size: 48px;
    font-weight: bold;
    color: #005ea6;
    margin-bottom: 25px;
}

.plus {
    font-size: 22px;
    vertical-align: top;
    color: #005ea6;
}

.role-switch {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
}

.role {
    width: 45%;
    border: 1px solid #005ea6;
    padding: 12px 0;
    border-radius: 8px;
    text-align: center;
    color: #005ea6;
    background: white;
    cursor: pointer;
    font-size: 15px;
}

.role.active {
    background: #c9def0;
    border-color: #005ea6;
    font-weight: 600;
}

.input-box {
    width: 100%;
    background: white;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 20px;
}

.input-box input {
    width: 100%;
    border: none;
    outline: none;
    font-size: 15px;
}

.password-box {
    position: relative;
}

.eye {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    cursor: pointer;
    opacity: 0.7;
}

.login-btn {
    width: 100%;
    background: #005ea6;
    color: white;
    padding: 14px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 20px;
}

.divider {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.divider span {
    width: 35%;
    height: 1px;
    background: #6c6c6c;
}

.forgot {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #005ea6;
    text-decoration: none;
    font-size: 16px;
}

.footer {
    text-align: center;
    margin-top: 40px;
}

.footer p {
    color: #555;
    font-size: 13px;
}

.footer h2 {
    margin-top: 5px;
    font-size: 24px;
}

.ed {
    color: #005ea6;
    font-weight: 700;
}

.plus2 {
    color: #006cc9;
}

@media(max-width: 480px) {
    .login-container {
        padding: 15px;
    }
    .logo {
        font-size: 40px;
    }
}
</style>

</head>
<body>

<div class="login-container">

    <!-- FAQ Button -->
    <button class="faq-btn">FAQ</button>

    <!-- Logo -->
    <div class="logo">
        <span>Ed<span class="plus">+</span></span>
    </div>

    <!-- Role Switch -->
    <div class="role-switch">
        <div class="role active">Student</div>
        <div class="role">Parents</div>
    </div>

    <!-- Input Fields -->
    <div class="input-box">
        <input type="text" placeholder="Mobile Number">
    </div>

    <div class="input-box password-box">
        <input type="password" placeholder="Password">
        <span class="eye">&#128065;</span>
    </div>

    <!-- Login Button -->
    <button class="login-btn">LOGIN</button>

    <!-- Divider -->
    <div class="divider">
        <span></span>
        <p>OR</p>
        <span></span>
    </div>

    <!-- Forgot Password -->
    <a class="forgot" href="#">Forgot Password ?</a>

    <!-- Footer -->
    <div class="footer">
        <p>Powered by</p>
        <h2><span class="ed">Ed</span><span class="plus2">Plus</span> Technology</h2>
    </div>

</div>

</body>
</html>
