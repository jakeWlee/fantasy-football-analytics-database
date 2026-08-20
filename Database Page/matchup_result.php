<?php
$host = 'localhost';
$db   = '354groupb6';
$user = 'fjh48053';
$pass = 'pooChie82#';

$msg = '';
$selectedManager = $selectedLeague = $selectedSeason = $selectedWeek = null;
$seasons = [];
$existingWeeks = [];
$weekExists = false;
$managers = [];
$leagues = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all managers and leagues for dropdowns
    $managers = $pdo->query("SELECT manager_id, first_name, last_name, username FROM MANAGER ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
    $leagues = $pdo->query("SELECT league_id, league_name FROM LEAGUE ORDER BY league_name")->fetchAll(PDO::FETCH_ASSOC);
    
    // Get selected values from POST (if any)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedManager = isset($_POST['manager_id']) ? $_POST['manager_id'] : null;
        $selectedLeague = isset($_POST['league_id']) ? $_POST['league_id'] : null;
        $selectedSeason = isset($_POST['season_id']) ? $_POST['season_id'] : null;
        $selectedWeek = isset($_POST['week_number']) ? $_POST['week_number'] : null;
        
        // Check if week exists in database
        if ($selectedSeason && $selectedWeek) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM MATCHUP WHERE season_id = ? AND week_number = ?");
            $stmt->execute([$selectedSeason, $selectedWeek]);
            $weekExists = $stmt->fetchColumn() > 0;
        }
        
        // Save the matchup data (only if week doesn't exist)
        if (isset($_POST['save_matchup']) && !$weekExists) {
            $stmt = $pdo->prepare("INSERT INTO MATCHUP (week_number, manager_score, opponent_score, result, season_id) VALUES (?, ?, ?, ?, ?)");
            $result = $_POST['manager_score'] > $_POST['opponent_score'] ? 'WIN' : ($_POST['manager_score'] < $_POST['opponent_score'] ? 'LOSS' : 'TIE');
            $stmt->execute([$selectedWeek, $_POST['manager_score'], $_POST['opponent_score'], $result, $selectedSeason]);
            
            $msg = "<div class='success'><span>✓</span> New matchup for Week {$selectedWeek} added successfully!</div>";
            
            // Clear the form after successful save
            $selectedWeek = null;
            $weekExists = false;
        } elseif (isset($_POST['save_matchup']) && $weekExists) {
            $msg = "<div class='error'><span>✗</span> Week {$selectedWeek} already exists for this season! Cannot override.</div>";
        }
    }
    
    // Load seasons based on selected league
    if ($selectedLeague) {
        $stmt = $pdo->prepare("
            SELECT season_id, year, team_name 
            FROM SEASON 
            WHERE league_id = ? 
            ORDER BY year DESC
        ");
        $stmt->execute([$selectedLeague]);
        $seasons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Load existing weeks for the selected season
    if ($selectedSeason) {
        $stmt = $pdo->prepare("SELECT DISTINCT week_number FROM MATCHUP WHERE season_id = ? ORDER BY week_number");
        $stmt->execute([$selectedSeason]);
        $existingWeeks = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
} catch(PDOException $e) { 
    $msg = "<div class='error'><span>✗</span> Error: " . $e->getMessage() . "</div>"; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Matchup</title>
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
            max-width: 600px;
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
        .warning { background: rgba(241, 196, 15, 0.15); border: 1px solid #f1c40f; color: #f1c40f; }
        form {
            background: #111d35;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #1a2a4a;
        }
        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #1a2a4a;
        }
        .section:last-of-type { border-bottom: none; margin-bottom: 10px; }
        .section-title {
            color: #c9a227;
            font-size: 0.9em;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
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
        .field { margin-bottom: 15px; }
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
        }
        button:hover:not(:disabled) {
            background: #d4b43a;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(201, 162, 39, 0.3);
        }
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .req { color: #e74c3c; }
        .info-text {
            color: #6b8cae;
            padding: 12px;
            background: #0a1628;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .existing-weeks {
            margin-top: 10px;
            padding: 10px;
            background: #0a1628;
            border-radius: 6px;
            font-size: 0.85em;
        }
        .existing-weeks span {
            color: #c9a227;
            font-weight: bold;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 0.5;
        }
    </style>
    <script>
        function submitForm() {
            document.getElementById('mainForm').submit();
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Add New Matchup</h2>
            <a href="home.php" class="back-btn">← Back to Home</a>
        </div>
        <?= $msg ?>
        
        <form method="POST" id="mainForm">
            <div class="section">
                <div class="section-title">Select Season</div>
                
                <div class="form-row">
                    <div class="field">
                        <label>Manager <span class="req">*</span></label>
                        <select name="manager_id" onchange="submitForm()">
                            <option value="">Select manager...</option>
                            <?php foreach($managers as $m): ?>
                            <option value="<?= $m['manager_id'] ?>" <?= ($selectedManager == $m['manager_id']) ? 'selected' : '' ?>><?= $m['first_name'] ?> <?= $m['last_name'] ?> (@<?= $m['username'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="field">
                        <label>League <span class="req">*</span></label>
                        <select name="league_id" onchange="submitForm()">
                            <option value="">Select league...</option>
                            <?php foreach($leagues as $l): ?>
                            <option value="<?= $l['league_id'] ?>" <?= ($selectedLeague == $l['league_id']) ? 'selected' : '' ?>><?= $l['league_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="field">
                    <label>Season <span class="req">*</span></label>
                    <select name="season_id" onchange="submitForm()" <?= empty($seasons) ? 'disabled' : '' ?>>
                        <option value="">Select season...</option>
                        <?php foreach($seasons as $s): ?>
                        <option value="<?= $s['season_id'] ?>" <?= ($selectedSeason == $s['season_id']) ? 'selected' : '' ?>><?= $s['year'] ?> — <?= $s['team_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <?php if ($selectedSeason && !empty($existingWeeks)): ?>
                <div class="existing-weeks">
                    <span>⚠️ Existing weeks:</span> Weeks <?= implode(', ', $existingWeeks) ?> already have matchup data
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($selectedSeason): ?>
            <div class="section">
                <div class="section-title">Enter Week Number</div>
                <div class="field">
                    <label>Week Number <span class="req">*</span></label>
                    <input type="number" 
                           name="week_number" 
                           min="1" 
                           max="18" 
                           step="1" 
                           required 
                           placeholder="Enter week (1-18)"
                           onchange="submitForm()"
                           value="<?= htmlspecialchars($selectedWeek) ?>">
                    <small style="color:#6b8cae; display:block; margin-top:5px;">NFL season weeks 1-18 only</small>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($selectedSeason && $selectedWeek && !$weekExists && $selectedWeek >= 1 && $selectedWeek <= 18): ?>
            <div class="section">
                <div class="section-title">Enter Scores for Week <?= htmlspecialchars($selectedWeek) ?></div>
                <div class="form-row">
                    <div class="field">
                        <label>Manager Score <span class="req">*</span></label>
                        <input type="number" step="0.1" name="manager_score" required placeholder="e.g., 134.2">
                    </div>
                    <div class="field">
                        <label>Opponent Score <span class="req">*</span></label>
                        <input type="number" step="0.1" name="opponent_score" required placeholder="e.g., 118.7">
                    </div>
                </div>
            </div>
            
            <button type="submit" name="save_matchup">Add Matchup for Week <?= htmlspecialchars($selectedWeek) ?></button>
            
            <?php elseif ($selectedSeason && $selectedWeek && $weekExists): ?>
            <div class="warning" style="padding: 14px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                ⚠️ Week <?= htmlspecialchars($selectedWeek) ?> already exists for this season! Cannot add duplicate week.
            </div>
            <button type="button" disabled style="opacity:0.5;">Week Already Exists</button>
            
            <?php elseif ($selectedSeason && $selectedWeek && ($selectedWeek < 1 || $selectedWeek > 18)): ?>
            <div class="warning" style="padding: 14px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                ⚠️ Please enter a valid week number between 1 and 18.
            </div>
            <button type="button" disabled style="opacity:0.5;">Invalid Week</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>