<?php
$host = 'localhost';
$db   = '354groupb6';
$user = 'fjh48053';
$pass = 'pooChie82#';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("
        SELECT 
            m.matchup_id,
            m.week_number,
            m.manager_score,
            m.opponent_score,
            m.result,
            s.year,
            s.team_name,
            l.league_name,
            ma.first_name AS manager_first,
            ma.last_name AS manager_last,
            ma.username
        FROM MATCHUP m
        JOIN SEASON s ON m.season_id = s.season_id
        JOIN LEAGUE l ON s.league_id = l.league_id
        JOIN MANAGER ma ON l.manager_id = ma.manager_id
        ORDER BY s.year DESC, m.week_number DESC
    ");
    $matchups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { die("Query failed: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Matchup History</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a1628;
            color: #e0e0e0;
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #c9a227;
        }
        h2 {
            color: #c9a227;
            font-size: 2em;
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
        .count-badge {
            background: #1a2a4a;
            color: #c9a227;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            margin-left: 15px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #111d35;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }
        th {
            background: #c9a227;
            color: #0a1628;
            padding: 14px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px 16px;
            border-bottom: 1px solid #1a2a4a;
            font-size: 0.95em;
        }
        tr:hover td { background: #1a2a4a; }
        tr:last-child td { border-bottom: none; }
        .result-w {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 700;
            background: rgba(39, 174, 96, 0.2);
            color: #2ecc71;
            border: 1px solid #27ae60;
        }
        .result-l {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 700;
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
        .result-t {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 700;
            background: rgba(155, 155, 155, 0.2);
            color: #bbb;
            border: 1px solid #999;
        }
        .score {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #c9a227;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="display:flex;align-items:center;">
                <h2>Matchup History</h2>
                <span class="count-badge"><?= count($matchups) ?> Matchups</span>
            </div>
            <a href="home.php" class="back-btn">← Back to Home</a>
        </div>
        <table>
            <tr>
                <th>Week</th>
                <th>Manager</th>
                <th>League</th>
                <th>Season</th>
                <th>Manager Score</th>
                <th>Opponent Score</th>
                <th>Result</th>
            </tr>
            <?php foreach($matchups as $m): ?>
            <tr>
                <td>Week <?= htmlspecialchars($m['week_number']) ?></td>
                <td><?= htmlspecialchars($m['manager_first']) ?> <?= htmlspecialchars($m['manager_last']) ?> (@<?= htmlspecialchars($m['username']) ?>)</td>
                <td><?= htmlspecialchars($m['league_name']) ?></td>
                <td><?= htmlspecialchars($m['year']) ?> — <?= htmlspecialchars($m['team_name']) ?></td>
                <td class="score"><?= htmlspecialchars($m['manager_score']) ?></td>
                <td class="score"><?= htmlspecialchars($m['opponent_score']) ?></td>
                <td>
                    <?php 
                    $result = trim($m['result']); // Trim whitespace just in case
                    if($result === 'WIN'): ?>
                        <span class="result-w">WIN</span>
                    <?php elseif($result === 'LOSS'): ?>
                        <span class="result-l">LOSS</span>
                    <?php else: ?>
                        <span class="result-t">TIE</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>