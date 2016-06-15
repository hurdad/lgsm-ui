
INSERT INTO github(url, branch) VALUES('https://github.com/dgibbs64/linuxgsm.git', 'master');

INSERT INTO query_engines(name, launch_uri) VALUES('None' ,'');
INSERT INTO query_engines(name, launch_uri) VALUES('SOURCE' ,'steam://connect');
INSERT INTO query_engines(name, launch_uri) VALUES('GOLDSOURCE' ,'steam://connect');
 
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (1, '7 Days To Die', '7DaysToDie', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'sdtdserver', 26900, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (1, 'ARK: Survivial Evolved', 'ARKSurvivalEvolved', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'arkserver', 7777, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (1, 'ARMA 3', 'Arma3', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'arma3server', 2302, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (2, 'Black Mesa: Deathmatch', 'BlackMesa', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'bmdmserver', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (2, 'Blade Symphony', 'BladeSymphony', , 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'bsserver', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (2, 'BrainBread 2', 'BrainBread2', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'bb2server', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (2, 'Codename CURE', 'CodenameCURE', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'ccserver', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (3, 'Counter Strike 1.6', 'CounterStrike', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'csserver', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (3, 'Counter Strike: Condition Zero', 'CounterStrikeConditionZero', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'csczserver', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (2, 'Counter Strike: Global Offensive', 'CounterStrikeGlobalOffensive', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'csgoserver', 27015, 1);
INSERT INTO games (query_engines_id, full_name, folder_name, glibc_version_min, hidden) VALUES (2, 'Counter Strike: Source', 'CounterStrikeSource', 0.0, 0);
INSERT INTO services (games_id, script_name, port, is_default) VALUES(LAST_INSERT_ID(), 'cssserver', 27015, 1);