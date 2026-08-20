<!DOCTYPE html>
<html>
<head>
    <title>Fantasy Football Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a1628;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            padding: 40px;
            width: 100%;
            max-width: 900px;
        }
        h1 {
            color: #c9a227;
            font-size: 2.8em;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(201, 162, 39, 0.3);
        }
        .subtitle {
            color: #6b8cae;
            font-size: 1.1em;
            margin-bottom: 50px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .nav {
            display: flex;
            gap: 25px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        .nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 30px 40px;
            background: #1a2a4a;
            color: #e0e0e0;
            text-decoration: none;
            border-radius: 12px;
            border: 1px solid #2a4a7a;
            transition: all 0.3s ease;
            min-width: 180px;
        }
        .nav a:hover {
            background: #c9a227;
            color: #0a1628;
            border-color: #c9a227;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(201, 162, 39, 0.3);
        }
        .icon { font-size: 2.5em; }
        .label { font-weight: 600; font-size: 1.1em; }
        .wide-btn {
            display: block;
            width: 100%;
            padding: 20px;
            background: #1a2a4a;
            color: #e0e0e0;
            text-decoration: none;
            border-radius: 12px;
            border: 1px solid #2a4a7a;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1.1em;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .wide-btn:hover {
            background: #c9a227;
            color: #0a1628;
            border-color: #c9a227;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(201, 162, 39, 0.3);
        }
        .footer {
            position: fixed;
            bottom: 20px;
            color: #4a6a8a;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fantasy Football Manager</h1>
       
        <div class="nav">
            <a href="view_players.php">
                <span class="icon">🏈</span>
                <span class="label">View Players</span>
            </a>
            <a href="add_manager.php">
                <span class="icon">👤</span>
                <span class="label">Add Manager</span>
            </a>
            <a href="add_league.php">
                <span class="icon">🏆</span>
                <span class="label">Add League</span>
            </a>
            <a href="matchup_result.php">
                <span class="icon">📊</span>
                <span class="label">Log Matchup</span>
            </a>
        </div>
        <a href="view_matchups.php" class="wide-btn">View Matchup History</a>
    </div>
  
</body>
</html>