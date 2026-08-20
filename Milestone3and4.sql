#create manager table
CREATE TABLE MANAGER (
    manager_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE
);

#create league table
CREATE TABLE LEAGUE (
    league_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    league_name VARCHAR(100) NOT NULL,
    platform VARCHAR(50) NOT NULL,
    scoring_format VARCHAR(50) NOT NULL DEFAULT 'Standard',
    manager_id INT NOT NULL,
    CONSTRAINT league_fk FOREIGN KEY (manager_id) REFERENCES MANAGER(manager_id)
);

#create season table
CREATE TABLE SEASON (
    season_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    year INT NOT NULL,
    team_name VARCHAR(100) NOT NULL,
    wins INT NOT NULL DEFAULT 0,
    losses INT NOT NULL DEFAULT 0,
    tie INT NOT NULL DEFAULT 0,
    points_for DOUBLE NOT NULL DEFAULT 0.0,
    points_against DOUBLE NOT NULL DEFAULT 0.0,
    rank INT,
    league_id INT NOT NULL,
    CONSTRAINT season_fk FOREIGN KEY (league_id) REFERENCES LEAGUE(league_id),
    CHECK (wins >= 0),
    CHECK (losses >= 0),
    CHECK (tie >= 0)
);

#create player table
CREATE TABLE PLAYER (
    player_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    position CHAR(3) NOT NULL,
    nfl_team VARCHAR(50) NOT NULL,
    age INT,
    height INT,
    weight INT,
    exp INT NOT NULL DEFAULT 0,
    PRIMARY KEY (player_id),
    CHECK (position IN ('QB', 'RB', 'WR', 'TE', 'K', 'DEF')),
    CHECK (age > 0),
    CHECK (exp >= 0)
);

#create matchup table
CREATE TABLE MATCHUP (
    matchup_id INT NOT NULL AUTO_INCREMENT,
    week_number INT NOT NULL,
    manager_score DOUBLE NOT NULL DEFAULT 0.0,
    opponent_score DOUBLE NOT NULL DEFAULT 0.0,
    result VARCHAR(10),
    season_id INT NOT NULL,
    PRIMARY KEY (matchup_id),
    CONSTRAINT matchup_fk FOREIGN KEY (season_id) REFERENCES SEASON(season_id),
    CHECK (week_number BETWEEN 1 AND 18),
    CHECK (result IN ('WIN', 'LOSS', 'TIE', NULL))
);

#create lineup table
CREATE TABLE LINEUP (
    matchup_id INT NOT NULL,
    player_id INT NOT NULL,
    points_scored DOUBLE NOT NULL DEFAULT 0.0,
    touchdowns INT NOT NULL DEFAULT 0,
    yards INT NOT NULL DEFAULT 0,
    turnovers INT NOT NULL DEFAULT 0,
    lineup_status VARCHAR(20) NOT NULL DEFAULT 'Active',
    player_status VARCHAR(20) NOT NULL DEFAULT 'Healthy', -- Healthy, Questionable, Out, Injured Reserve, Suspended
    PRIMARY KEY (matchup_id, player_id),
    CONSTRAINT lineup_fk1 FOREIGN KEY (matchup_id) REFERENCES MATCHUP(matchup_id),
    CONSTRAINT lineup_fk2 FOREIGN KEY (player_id) REFERENCES PLAYER(player_id),
    CHECK (lineup_status IN ('Active', 'Bench')),
    CHECK (player_status IN ('Healthy', 'Questionable', 'Doubtful', 'Out', 'Injured Reserve', 'Suspended', 'Bye'))
);

#insert sample data into manager
INSERT INTO MANAGER (first_name, last_name, username, email) VALUES
('Matt', 'Lam', 'mtt22', 'mattlam@email.com');

#insert sample data into league
INSERT INTO LEAGUE (league_name, platform, scoring_format, manager_id) VALUES
('League of Doom', 'ESPN', 'PPR', 1),
('Boto Bangahz', 'Yahoo', 'Half PPR', 1);

#insert sample data into season
INSERT INTO SEASON (year, team_name, wins, losses, tie, points_for, points_against, rank, league_id) VALUES
-- League of Doom (id=1)
(2023, 'GOAT Squad',  8,  5, 0, 1389.4, 1210.8, 2, 1),
(2024, 'GOAT Squad',  9,  4, 0, 1423.5, 1198.2, 1, 1),
(2025, 'GOAT Squad',  6,  7, 0, 1301.0, 1355.5, 3, 1),
-- Boto Bangahz (id=2)
(2023, 'Bad Boys',    7,  6, 0, 1155.0, 1189.3, 3, 2),
(2024, 'Bad Boys',    10, 3, 0, 1290.7, 1140.4, 1, 2),
(2025, 'Bad Boys',    5,  8, 0, 1098.2, 1244.9, 4, 2);

#insert sample data into matchup
INSERT INTO MATCHUP (week_number, manager_score, opponent_score, result, season_id) VALUES
-- Season 1: League of Doom 2023 (season_id=1)
(1,  134.2, 118.7, 'WIN',  1),
(2,  102.5, 141.0, 'LOSS', 1),
(3,  151.8, 129.3, 'WIN',  1),
(4,  88.4,  112.6, 'LOSS', 1),
-- Season 2: League of Doom 2024 (season_id=2)
(1,  142.5, 118.3, 'WIN',  2),
(2,  98.7,  134.2, 'LOSS', 2),
(3,  167.1, 145.6, 'WIN',  2),
(4,  155.2, 100.4, 'WIN',  2),
-- Season 3: League of Doom 2025 (season_id=3)
(1,  109.0, 138.5, 'LOSS', 3),
(2,  122.3, 115.1, 'WIN',  3),
(3,  88.6,  144.2, 'LOSS', 3),
(4,  140.1, 103.9, 'WIN',  3),
-- Season 4: Boto Bangahz 2023 (season_id=4)
(1,  98.4,  110.2, 'LOSS', 4),
(2,  121.7, 108.5, 'WIN',  4),
(3,  103.2, 97.8,  'WIN',  4),
(4,  88.9,  119.4, 'LOSS', 4),
-- Season 5: Boto Bangahz 2024 (season_id=5)
(1,  130.8, 112.4, 'WIN',  5),
(2,  145.1, 99.7,  'WIN',  5),
(3,  108.3, 121.6, 'LOSS', 5),
(4,  119.5, 90.3,  'WIN',  5),
-- Season 6: Boto Bangahz 2025 (season_id=6)
(1,  95.2,  128.8, 'LOSS', 6),
(2,  107.4, 131.3, 'LOSS', 6),
(3,  114.6, 104.2, 'WIN',  6),
(4,  88.0,  120.6, 'LOSS', 6);


#insert sample data into player
INSERT INTO PLAYER (first_name, last_name, position, nfl_team, age, height, weight, exp) VALUES
-- Stars rostered across multiple seasons (overlap players)
('Patrick',   'Mahomes',      'QB', 'Kansas City Chiefs',    28, 74, 227, 7),
('Justin',    'Jefferson',    'WR', 'Minnesota Vikings',     25, 73, 195, 4),
('Christian', 'McCaffrey',    'RB', 'San Francisco 49ers',  27, 71, 205, 7),
('Travis',    'Kelce',        'TE', 'Kansas City Chiefs',    34, 77, 245, 11),
('Lamar',     'Jackson',      'QB', 'Baltimore Ravens',      27, 74, 212, 6),
('Tyreek',    'Hill',         'WR', 'Miami Dolphins',         30, 70, 185, 9),
('Saquon',    'Barkley',      'RB', 'Philadelphia Eagles',   27, 72, 233, 6),
-- LOD 2023 only (season_id=1)
('Josh',      'Allen',        'QB', 'Buffalo Bills',          27, 77, 237, 5),
('Davante',   'Adams',        'WR', 'Las Vegas Raiders',     30, 73, 215, 9),
('Austin',    'Ekeler',       'RB', 'Los Angeles Chargers',  28, 68, 200, 5),
('Mark',      'Andrews',      'TE', 'Baltimore Ravens',      28, 78, 256, 5),
('Tony',      'Pollard',      'RB', 'Dallas Cowboys',        26, 72, 209, 4),
('Harrison',  'Butker',       'K',  'Kansas City Chiefs',    27, 76, 195, 6),
-- LOD 2024 only (season_id=2)
('Amon-Ra',   'St. Brown',    'WR', 'Detroit Lions',          24, 71, 196, 3),
('Sam',       'LaPorta',      'TE', 'Detroit Lions',          23, 76, 245, 1),
('De\'Von',   'Achane',       'RB', 'Miami Dolphins',         23, 68, 188, 1),
('Puka',      'Nacua',        'WR', 'Los Angeles Rams',      23, 73, 201, 1),
('Jake',      'Moody',        'K',  'San Francisco 49ers',  24, 72, 185, 1),
-- LOD 2025 only (season_id=3)
('Jayden',    'Daniels',      'QB', 'Washington Commanders', 24, 74, 210, 1),
('Rome',      'Odunze',       'WR', 'Chicago Bears',         22, 75, 215, 1),
('Bijan',     'Robinson',     'RB', 'Atlanta Falcons',       22, 72, 215, 2),
('Trey',      'McBride',      'TE', 'Arizona Cardinals',    25, 76, 246, 3),
('Evan',      'McPherson',    'K',  'Cincinnati Bengals',   24, 73, 190, 3),
-- BBZ 2023 only (season_id=4)
('Stefon',    'Diggs',        'WR', 'Buffalo Bills',          29, 72, 191, 8),
('Rachaad',   'White',        'RB', 'Tampa Bay Buccaneers',  24, 73, 214, 1),
('Darren',    'Waller',       'TE', 'New York Giants',       31, 78, 255, 7),
('Evan',      'McPherson',    'K',  'Cincinnati Bengals',   23, 73, 190, 2),
-- BBZ 2024 only (season_id=5)
('CeeDee',    'Lamb',         'WR', 'Dallas Cowboys',        25, 73, 198, 4),
('Kyren',     'Williams',     'RB', 'Los Angeles Rams',      24, 70, 198, 2),
('Tucker',    'Kraft',        'TE', 'New England Patriots',  23, 76, 253, 1),
('Brandon',   'Aubrey',       'K',  'Dallas Cowboys',        29, 70, 183, 1),
-- BBZ 2025 only (season_id=6)
('Brian',     'Thomas Jr.',   'WR', 'Jacksonville Jaguars',  22, 75, 209, 1),
('Jonathon',  'Brooks',       'RB', 'Carolina Panthers',    21, 71, 195, 1),
('Cade',      'Stover',       'TE', 'Baltimore Ravens',      24, 77, 248, 1),
('Chris',     'Boswell',      'K',  'Pittsburgh Steelers',  33, 72, 183, 9);

#insert sample data into lineup
INSERT INTO LINEUP (matchup_id, player_id, points_scored, touchdowns, yards, turnovers, lineup_status, player_status) VALUES
-- Season 1: LOD 2023 (matchup_id=1, week 1)
(1, 8,  34.6, 3, 298, 0, 'Active', 'Healthy'),  -- Josh Allen QB
(1, 2,  26.4, 1, 124, 0, 'Active', 'Healthy'),  -- Justin Jefferson WR
(1, 9,  18.8, 1, 88,  0, 'Active', 'Healthy'),  -- Davante Adams WR
(1, 3,  31.2, 1, 148, 0, 'Active', 'Healthy'),  -- Christian McCaffrey RB
(1, 10, 14.6, 0, 96,  0, 'Active', 'Healthy'),  -- Austin Ekeler RB
(1, 11, 17.4, 1, 74,  0, 'Active', 'Healthy'),  -- Mark Andrews TE
(1, 13, 9.2,  0, 0,   0, 'Active', 'Healthy'),  -- Harrison Butker K
(1, 7,  22.0, 1, 104, 0, 'Active', 'Healthy'),  -- Saquon Barkley FLEX

-- Season 2: LOD 2024 (matchup_id=5, week 1)
(5, 1,  38.5, 3, 315, 0, 'Active', 'Healthy'),  -- Patrick Mahomes QB
(5, 2,  29.2, 1, 142, 0, 'Active', 'Healthy'),  -- Justin Jefferson WR
(5, 14, 24.6, 1, 116, 0, 'Active', 'Healthy'),  -- Amon-Ra St. Brown WR
(5, 3,  34.8, 2, 168, 0, 'Active', 'Healthy'),  -- Christian McCaffrey RB
(5, 16, 18.4, 1, 88,  0, 'Active', 'Healthy'),  -- De'Von Achane RB
(5, 4,  22.8, 1, 98,  0, 'Active', 'Healthy'),  -- Travis Kelce TE
(5, 18, 11.2, 0, 0,   0, 'Active', 'Healthy'),  -- Jake Moody K
(5, 7,  23.0, 1, 118, 0, 'Active', 'Healthy'),  -- Saquon Barkley FLEX

-- Season 3: LOD 2025 (matchup_id=9, week 1)
(9, 19, 28.4, 2, 244, 1, 'Active', 'Healthy'),  -- Jayden Daniels QB
(9, 2,  18.6, 0, 96,  0, 'Active', 'Healthy'),  -- Justin Jefferson WR
(9, 20, 12.4, 0, 64,  0, 'Active', 'Healthy'),  -- Rome Odunze WR
(9, 21, 22.8, 1, 118, 0, 'Active', 'Healthy'),  -- Bijan Robinson RB
(9, 7,  14.2, 0, 102, 1, 'Active', 'Healthy'),  -- Saquon Barkley RB
(9, 22, 8.6,  0, 36,  0, 'Active', 'Healthy'),  -- Trey McBride TE
(9, 23, 7.0,  0, 0,   0, 'Active', 'Healthy'),  -- Evan McPherson K
(9, 3,  4.2,  0, 28,  0, 'Active', 'Out'),      -- Christian McCaffrey FLEX (hurt early, reduced stats)

-- Season 4: BBZ 2023 (matchup_id=13, week 1)
(13, 5,  32.8, 2, 268, 1, 'Active', 'Healthy'), -- Lamar Jackson QB
(13, 24, 24.6, 1, 114, 0, 'Active', 'Healthy'), -- Stefon Diggs WR
(13, 6,  18.4, 0, 86,  0, 'Active', 'Healthy'), -- Tyreek Hill WR
(13, 25, 16.8, 1, 92,  0, 'Active', 'Healthy'), -- Rachaad White RB
(13, 7,  22.4, 1, 118, 0, 'Active', 'Healthy'), -- Saquon Barkley RB
(13, 26, 12.6, 0, 58,  0, 'Active', 'Healthy'), -- Darren Waller TE
(13, 27, 8.4,  0, 0,   0, 'Active', 'Healthy'), -- Evan McPherson K
(13, 4,  16.8, 1, 72,  0, 'Active', 'Healthy'), -- Travis Kelce FLEX

-- Season 5: BBZ 2024 (matchup_id=17, week 1)
(17, 5,  40.2, 3, 312, 0, 'Active', 'Healthy'), -- Lamar Jackson QB
(17, 28, 28.4, 2, 144, 0, 'Active', 'Healthy'), -- CeeDee Lamb WR
(17, 6,  18.6, 0, 86,  0, 'Active', 'Healthy'), -- Tyreek Hill WR
(17, 7,  24.8, 1, 138, 0, 'Active', 'Healthy'), -- Saquon Barkley RB
(17, 29, 14.6, 0, 94,  0, 'Active', 'Healthy'), -- Kyren Williams RB
(17, 15, 9.8,  0, 46,  0, 'Active', 'Out'),     -- Sam LaPorta TE (left game with injury, reduced stats)
(17, 31, 13.8, 0, 0,   0, 'Active', 'Healthy'), -- Brandon Aubrey K
(17, 4,  18.2, 1, 82,  0, 'Active', 'Healthy'), -- Travis Kelce FLEX

-- Season 6: BBZ 2025 (matchup_id=21, week 1)
(21, 19, 24.6, 1, 208, 1, 'Active', 'Healthy'), -- Jayden Daniels QB
(21, 32, 16.8, 1, 88,  0, 'Active', 'Healthy'), -- Brian Thomas Jr. WR
(21, 28, 14.2, 0, 72,  0, 'Active', 'Healthy'), -- CeeDee Lamb WR
(21, 21, 18.4, 1, 98,  0, 'Active', 'Healthy'), -- Bijan Robinson RB
(21, 33, 8.2,  0, 62,  1, 'Active', 'Healthy'), -- Jonathon Brooks RB
(21, 34, 6.4,  0, 24,  0, 'Active', 'Healthy'), -- Cade Stover TE
(21, 35, 9.6,  0, 0,   0, 'Active', 'Healthy'), -- Chris Boswell K
(21, 7,  14.6, 0, 108, 0, 'Active', 'Healthy'); -- Saquon Barkley FLEX

#query 1 - manager career statistics
SELECT 
    M.username,
    COUNT(DISTINCT L.league_id) AS total_leagues,
    COUNT(DISTINCT S.season_id) AS total_seasons,
    SUM(S.wins) AS career_wins,
    SUM(S.losses) AS career_losses,
    SUM(S.tie) AS career_ties,
    ROUND(SUM(S.wins) / NULLIF(SUM(S.wins + S.losses), 0) * 100, 1) AS career_win_percentage,
    ROUND(AVG(S.points_for), 1) AS avg_points_for,
    ROUND(AVG(S.points_against), 1) AS avg_points_against
FROM MANAGER M
JOIN LEAGUE L ON M.manager_id = L.manager_id
JOIN SEASON S ON L.league_id = S.league_id
WHERE M.username = 'mtt22';

#query 2 - each player avg pts & tds per season
SELECT P.first_name, P.last_name, P.position, P.exp,
    ROUND(SUM(L.points_scored) / COUNT(DISTINCT S.year), 2) AS avg_points_per_season,
    ROUND(SUM(L.touchdowns) / COUNT(DISTINCT S.year), 2) AS avg_tds_per_season
FROM LINEUP L
JOIN PLAYER P ON L.player_id = P.player_id
JOIN MATCHUP M ON L.matchup_id = M.matchup_id
JOIN SEASON S ON M.season_id = S.season_id
GROUP BY P.player_id, P.first_name, P.last_name, P.position
ORDER BY avg_points_per_season DESC;

#query 3 - how frequent a player is rostered
SELECT 
    p.first_name,
    p.last_name,
    p.position,
    COUNT(DISTINCT s.season_id) AS seasons_rostered,
    COUNT(l.matchup_id) AS weeks_rostered,
    SUM(CASE WHEN l.lineup_status = 'Active' THEN 1 ELSE 0 END) AS weeks_started
FROM MANAGER m
JOIN SEASON s ON m.manager_id = s.league_id
JOIN MATCHUP mu ON s.season_id = mu.season_id
JOIN LINEUP l ON mu.matchup_id = l.matchup_id
JOIN PLAYER p ON l.player_id = p.player_id
WHERE m.username = 'mtt22'
GROUP BY p.player_id, p.first_name, p.last_name, p.position
ORDER BY weeks_rostered DESC, p.position;

#query 4 - active lineup for specific matchup (id=1)
SELECT P.first_name, P.last_name, P.position, P.nfl_team,
    L.points_scored, L.touchdowns, L.yards, L.turnovers, L.player_status
FROM LINEUP L
JOIN PLAYER P ON L.player_id = P.player_id
WHERE L.matchup_id = 1 AND L.lineup_status = 'Active'
ORDER BY CASE P.position
    WHEN 'QB'  THEN 1
    WHEN 'RB'  THEN 2
    WHEN 'WR'  THEN 3
    WHEN 'TE'  THEN 4
    WHEN 'K'   THEN 5
    WHEN 'DEF' THEN 6
END;
-- only matchup_id 1,5,9,13,17,21 have lineup data (week 1 of each unique season)

#query 5 - manager matchup history
SELECT M.first_name, M.last_name, S.team_name, S.year,
    MU.week_number, MU.manager_score, MU.opponent_score, MU.result
FROM MANAGER M
JOIN LEAGUE L ON M.manager_id = L.manager_id
JOIN SEASON S ON L.league_id = S.league_id
JOIN MATCHUP MU ON S.season_id = MU.season_id
ORDER BY L.league_name, S.year, MU.week_number;

#view 1
CREATE VIEW career_statistics AS
SELECT 
    M.username,
    M.first_name,
    M.last_name,
    COUNT(DISTINCT L.league_id) AS total_leagues,
    COUNT(DISTINCT S.season_id) AS total_seasons,
    SUM(S.wins) AS career_wins,
    SUM(S.losses) AS career_losses,
    SUM(S.tie) AS career_ties,
    ROUND(SUM(S.wins) / NULLIF(SUM(S.wins + S.losses), 0) * 100, 1) AS career_win_pct,
    ROUND(AVG(S.points_for), 1) AS avg_points_for,
    ROUND(AVG(S.points_against), 1) AS avg_points_against,
    SUM(S.points_for) AS total_points_for,
    SUM(S.points_against) AS total_points_against
FROM MANAGER M
JOIN LEAGUE L ON M.manager_id = L.manager_id
JOIN SEASON S ON L.league_id = S.league_id
GROUP BY M.username, M.first_name, M.last_name;

SELECT * FROM career_statistics;

#view 2
CREATE VIEW active_lineup_stats AS
SELECT L.matchup_id, P.first_name, P.last_name, P.position, L.points_scored, L.touchdowns, L.yards, L.turnovers, L.player_status
FROM LINEUP L
JOIN PLAYER P ON L.player_id = P.player_id
WHERE L.lineup_status = 'Active';

SELECT * FROM active_lineup_stats;

#grant access/privileges
GRANT SELECT, INSERT, UPDATE ON fantasy_db.* TO 'app_service'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE ON fantasy_db.LEAGUE TO 'league_admin'@'localhost';

GRANT SELECT ON fantasy_db.* TO 'analyst_user'@'localhost';