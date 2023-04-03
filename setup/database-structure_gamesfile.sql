ALTER TABLE `games`
    ADD COLUMN `games_file` VARCHAR(255) NOT NULL DEFAULT '' AFTER `games_name`,
    ADD COLUMN `games_is_default` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0 = self implemented game, 1 = default game' AFTER `games_type`;

UPDATE `games` SET `games_file` = 'lucky.php', `games_is_default` = '1' WHERE `games`.`games_name` = 'Lucky';
UPDATE `games` SET `games_file` = 'memory.php', `games_is_default` = '1' WHERE `games`.`games_name` = 'Memory';
UPDATE `games` SET `games_file` = 'right_number.php', `games_is_default` = '1' WHERE `games`.`games_name` = 'Right Number';
UPDATE `games` SET `games_file` = 'tictactoe.php', `games_is_default` = '1' WHERE `games`.`games_name` = 'Tic Tac Toe';
