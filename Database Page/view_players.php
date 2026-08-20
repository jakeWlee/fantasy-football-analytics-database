<?php
require_once 'db.php';

try {
    $stmt = $conn->query("SELECT player_id, first_name, last_name, position, nfl_team, age, height, weight, exp FROM PLAYER ORDER BY last_name");
    $players = $stmt->fetchAll();
} catch(PDOException $e) { die("Query failed: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Player Roster</title>
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
        .pos-tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 700;
            background: #2a4a7a;
            color: #8ab4f8;
        }
        .null-val { color: #4a6a8a; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="display:flex;align-items:center;">
                <h2>Player Roster</h2>
                <span class="count-badge"><?= count($players) ?> Players</span>
            </div>
            <a href="home.php" class="back-btn">← Back to Home</a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Position</th>
                <th>NFL Team</th>
                <th>Age</th>
                <th>Height</th>
                <th>Weight</th>
                <th>Exp</th>
            </tr>
            <?php foreach($players as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['player_id']) ?></td>
                <td><?= htmlspecialchars($p['first_name']) ?></td>
                <td><?= htmlspecialchars($p['last_name']) ?></td>
                <td><span class="pos-tag"><?= htmlspecialchars($p['position']) ?></span></td>
                <td><?= htmlspecialchars($p['nfl_team']) ?></td>
                <td><?= $p['age'] ? htmlspecialchars($p['age']) : '<span class="null-val">—</span>' ?></td>
                <td><?= $p['height'] ? htmlspecialchars($p['height']) : '<span class="null-val">—</span>' ?></td>
                <td><?= $p['weight'] ? htmlspecialchars($p['weight']) : '<span class="null-val">—</span>' ?></td>
                <td><?= htmlspecialchars($p['exp']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>