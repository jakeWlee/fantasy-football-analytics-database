<?php
$host = 'localhost';
$db   = '354groupb6';
$user = 'fjh48053';
$pass = 'pooChie82#';

$msg = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $managers = $pdo->query("SELECT manager_id, first_name, last_name, username FROM MANAGER ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = "INSERT INTO LEAGUE (league_name, platform, scoring_format, manager_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['league_name'],
            $_POST['platform'],
            $_POST['scoring_format'],
            $_POST['manager_id']
        ]);
        $msg = "<div class='success'><span>✓</span> League added successfully!</div>";
    }
} catch(PDOException $e) { 
    $msg = "<div class='error'><span>✗</span> Error: " . $e->getMessage() . "</div>"; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add League</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a1628;
            color: #e0e0e0;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .container {
            width: 100%;
            max-width: 500px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #c9a227;
        }
        h2 {
            color: #c9a227;
            font-size: 1.8em;
        }
        .back-btn {
            color: #6b8cae;
            text-decoration: none;
            font-size: 0.95em;
            padding: 8px 16px;
            border: 1px solid #2a4a7a;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .back-btn:hover {
            color: #c9a227;
            border-color: #c9a227;
        }
        .success, .error {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .success { background: rgba(39, 174, 96, 0.15); border: 1px solid #27ae60; color: #2ecc71; }
        .error { background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; color: #e74c3c; }
        form {
            background: #111d35;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #1a2a4a;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            color: #8ab4f8;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        input, select {
            width: 100%;
            padding: 12px 14px;
            background: #0a1628;
            border: 1px solid #2a4a7a;
            border-radius: 8px;
            color: #e0e0e0;
            font-size: 1em;
            transition: border-color 0.2s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #c9a227;
        }
        .field { margin-bottom: 18px; }
        button {
            width: 100%;
            padding: 14px;
            background: #c9a227;
            color: #0a1628;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.2s;
            margin-top: 10px;
        }
        button:hover {
            background: #d4b43a;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(201, 162, 39, 0.3);
        }
        .req { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Add New League</h2>
            <a href="home.php" class="back-btn">← Back to Home</a>
        </div>
        <?= $msg ?>
        <form method="POST">
            <div class="field">
                <label>League Name <span class="req">*</span></label>
                <input type="text" name="league_name" required>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>Platform <span class="req">*</span></label>
                    <select name="platform" required>
                        <option value="">Select...</option>
                        <option value="ESPN">ESPN</option>
                        <option value="Yahoo">Yahoo</option>
                        <option value="Sleeper">Sleeper</option>
                        <option value="NFL">NFL</option>
                        <option value="CBS">CBS</option>
                    </select>
                </div>
                <div class="field">
                    <label>Scoring Format <span class="req">*</span></label>
                    <select name="scoring_format" required>
                        <option value="">Select...</option>
                        <option value="Standard">Standard</option>
                        <option value="PPR">PPR</option>
                        <option value="Half PPR">Half PPR</option>
                    </select>
                </div>
            </div>
            <div class="field">
                <label>Manager <span class="req">*</span></label>
                <select name="manager_id" required>
                    <option value="">Select manager...</option>
                    <?php foreach($managers as $m): ?>
                    <option value="<?= $m['manager_id'] ?>"><?= $m['first_name'] ?> <?= $m['last_name'] ?> (@<?= $m['username'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Add League</button>
        </form>
    </div>
</body>
</html>