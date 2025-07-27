<?php

include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM allusers WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: chat.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnonnymChat - Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Orbitron:wght@400;500;600;700&display=swap');
        
        :root {
            --primary: #5865f2;
            --primary-dark: #4752c4;
            --secondary: #eb459e;
            --accent: #00c09a;
            --bg-dark: #141824;
            --bg-darker: #0c0e17;
            --bg-light: #1e2133;
            --text: #e6e6fa;
            --text-muted: #9ea0b2;
        }
        .error-message {
    color: #eb459e; /* pink/red error color */
    background-color: rgba(235, 69, 158, 0.1);
    border: 1px solid #eb459e;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: 600;
    text-align: center;
}

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Exo 2', sans-serif;
        }
        
        body {
            background-color: var(--bg-dark);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background effects */
        .bg-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(to right, rgba(94, 101, 242, 0.1) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(94, 101, 242, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            z-index: -1;
        }
        
        .bg-circles {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary) 0%, rgba(88, 101, 242, 0) 70%);
            opacity: 0.1;
            z-index: -1;
        }
        
        .bg-circles:nth-child(2) {
            top: auto;
            right: auto;
            bottom: -150px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--secondary) 0%, rgba(235, 69, 158, 0) 70%);
        }
        
        .container {
            width: 100%;
            max-width: 900px;
            background-color: var(--bg-light);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .mascot-side {
            width: 40%;
            background-color: #212540;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .mascot-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, #2a2e53 0%, #1a1e3d 100%);
            z-index: 0;
        }
        
        .mascot-container {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .robot-mascot {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
            box-shadow: 0 0 15px var(--accent), 0 0 30px rgba(0, 192, 154, 0.3);
        }
        
        .mascot-text {
            margin-top: 20px;
        }
        
        .mascot-text h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--accent);
            letter-spacing: 1px;
        }
        
        .mascot-text p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
        }
        
        .speech-bubble {
            position: relative;
            background: #2d3356;
            padding: 15px;
            margin-top: 30px;
            border-radius: 12px;
            max-width: 220px;
        }
        
        .speech-bubble::after {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid #2d3356;
        }
        
        .speech-bubble p {
            font-size: 14px;
            color: var(--text);
            text-align: center;
            line-height: 1.4;
        }
        
        .form-side {
            width: 60%;
            padding: 40px;
            position: relative;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            margin-bottom: 8px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }
        
        .form-header p {
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }
        
        .form-group label::before {
            content: '○';
            color: var(--accent);
            margin-right: 6px;
            font-size: 10px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text);
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.2);
        }
        
        .btn-container {
            margin-top: 30px;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }
        
        .btn::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            bottom: -50%;
            left: -50%;
            background: linear-gradient(to bottom, rgba(229, 172, 142, 0), rgba(255, 255, 255, 0.5) 50%, rgba(229, 172, 142, 0));
            transform: rotateZ(60deg) translate(-5em, 7.5em);
            opacity: 0;
            transition: transform 0.5s, opacity 0.5s;
        }
        
        .btn:hover {
            background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(88, 101, 242, 0.4);
        }
        
        .btn:hover::after {
            opacity: 0.3;
            transform: rotateZ(60deg) translate(1em, -9em);
        }
        
        .bottom-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .bottom-link a {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .bottom-link a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .decorations {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--secondary);
            opacity: 0.1;
        }
        
        .decorations:nth-child(2) {
            top: 60px;
            right: 50px;
            width: 15px;
            height: 15px;
            background-color: var(--accent);
        }
        
        .decorations:nth-child(3) {
            top: 40px;
            right: 80px;
            width: 10px;
            height: 10px;
            background-color: var(--primary);
        }
        
        /* Character elements */
        .character-element {
            position: absolute;
            z-index: 2;
        }
        
        .character-eye {
            top: 10px;
            left: 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(0, 192, 154, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: blink 4s infinite;
        }
        
        .character-eye::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--accent);
            box-shadow: 0 0 10px var(--accent);
        }
        
        .character-antenna {
            top: -15px;
            right: 80px;
            width: 3px;
            height: 25px;
            background: linear-gradient(to bottom, var(--secondary), transparent);
        }
        
        .character-antenna::after {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--secondary);
            box-shadow: 0 0 10px var(--secondary);
            animation: pulse 2s infinite;
        }
        
        @keyframes blink {
            0%, 45%, 50%, 100% {
                transform: scaleY(1);
            }
            48% {
                transform: scaleY(0.1);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: translateX(-50%) scale(1);
            }
            50% {
                opacity: 0.7;
                transform: translateX(-50%) scale(0.8);
            }
        }
        
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .mascot-side, .form-side {
                width: 100%;
            }
            
            .mascot-side {
                padding: 30px;
            }
            
            .robot-mascot {
                width: 150px;
                height: 150px;
            }
        }
        
        @media screen and (max-width: 480px) {
            .form-side {
                padding: 30px 20px;
            }
            
            .mascot-side {
                padding: 20px;
            }
            
            .robot-mascot {
                width: 120px;
                height: 120px;
            }
        }
    </style>








<body>
 
        
      <!-- Background effects -->
    <div class="bg-grid"></div>
    <div class="bg-circles"></div>
    <div class="bg-circles"></div>
    
    <div class="container">
        <!-- Left side with robot mascot -->
        <div class="mascot-side">
            <div class="character-element character-eye"></div>
            <div class="character-element character-antenna"></div>
            
            <div class="mascot-container">
                <img src="https://public.youware.com/users-website-assets/prod/3d62d3ab-28b7-4564-b821-4ad27172d474/3c1a674b5cd74653806c1b81a50c48cc.jpg" alt="AnonnymChat Robot" class="robot-mascot">
                <div class="mascot-text">
                    <h3>ANONN-BOT</h3>
                    <p>Your privacy guardian</p>
                </div>
                <div class="speech-bubble">
                    <p>Welcome back! I've kept your identity safe. Ready to chat anonymously?</p>
                </div>
            </div>
        </div>
        
        <!-- Right side with form -->
        <div class="form-side">
            <div class="decorations"></div>
            <div class="decorations"></div>
            <div class="decorations"></div>
            
            <div class="form-header">
                <h1>AnonnymChat</h1>
                <p>Login to your anonymous account</p>
            </div>

  
            <form method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

                <div class="btn-container">
                    <button type="submit" class="btn">LOGIN</button>
                </div>
                
                <div class="bottom-link">
                    <a href="register.php">No account? Register now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
