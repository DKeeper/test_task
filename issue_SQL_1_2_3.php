<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 12.08.14
 * @time 8:59
 * Created by JetBrains PhpStorm.
 */

date_default_timezone_set('Europe/Moscow');

$connect = mysql_connect("[host]", "[username]", "[password]")
    or die("Could not connect : " . mysql_error());

mysql_select_db("[dbname]") or die("Could not select database");

$sql = "
CREATE TABLE IF NOT EXISTS events (
  id int(11) NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  begin_date datetime NOT NULL,
  end_date datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `events` (`id`, `name`, `begin_date`, `end_date`) VALUES
(1, 'Event 1', '2014-08-08 00:00:00', '2014-08-09 23:59:59'),
(2, 'Event 2', '2014-08-07 00:00:00', '2014-08-11 23:59:59'),
(3, 'Event 3', '2014-08-10 00:00:00', '2014-08-15 23:59:59'),
(4, 'Event 4', '2014-08-14 00:00:00', '2014-08-19 23:59:59'),
(5, 'Event 5', '2014-08-17 00:00:00', '2014-08-21 23:59:59'),
(6, 'Event 6', '2014-08-18 00:00:00', '2014-08-22 23:59:59');

CREATE TABLE IF NOT EXISTS seminars (
  id int(11) NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  begin_date datetime NOT NULL,
  end_date datetime NOT NULL,
  city_id int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY city_id (city_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Записи о семинарах';

CREATE TABLE IF NOT EXISTS dict_city (
  id int(11) NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Словарь городов';

CREATE TABLE IF NOT EXISTS members (
  id int(11) NOT NULL AUTO_INCREMENT,
  FIO text NOT NULL,
  seminar_id int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS comments (
  id int(11) NOT NULL AUTO_INCREMENT,
  text text NOT NULL,
  seminar_id int(11) NOT NULL,
  author_id int(11) NOT NULL,
  parent_comment_id int(11) DEFAULT NULL,
  created_at datetime NOT NULL,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  KEY author_id (author_id),
  KEY parent_comment_id (parent_comment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Комментарии пользователей к семинарам';

ALTER TABLE seminars
  ADD CONSTRAINT seminars_ibfk_1 FOREIGN KEY (city_id) REFERENCES dict_city (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE members
  ADD CONSTRAINT members_ibfk_1 FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE comments
  ADD CONSTRAINT comments_ibfk_3 FOREIGN KEY (parent_comment_id) REFERENCES comments (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT comments_ibfk_1 FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT comments_ibfk_2 FOREIGN KEY (author_id) REFERENCES members (id) ON DELETE CASCADE ON UPDATE CASCADE;
";

# Uncomment for data import
//mysql_query($sql) or die("Import failed : " . mysql_error());

$currentDate = date('Y-m-d');

$_timestamp = strtotime($currentDate);

$_dayNumber = date('w',$_timestamp); // 0 - Sunday

$_dayStart = date('Y-m-d H:i:s',mktime(0,0,0,date('m',$_timestamp),date('d',$_timestamp)+(1-$_dayNumber),date('Y',$_timestamp)));
$_dayFinish = date('Y-m-d H:i:s',mktime(23,59,59,date('m',$_timestamp),date('d',$_timestamp)+(7-$_dayNumber),date('Y',$_timestamp)));

$sql = "
  SELECT *
  FROM events
  WHERE
  begin_date BETWEEN STR_TO_DATE( '{$_dayStart}', '%Y-%m-%d %H:%i:%s' ) AND STR_TO_DATE( '{$_dayFinish}', '%Y-%m-%d %H:%i:%s' ) OR
  end_date BETWEEN STR_TO_DATE( '{$_dayStart}', '%Y-%m-%d %H:%i:%s' ) AND STR_TO_DATE( '{$_dayFinish}', '%Y-%m-%d %H:%i:%s' )
";

$query = mysql_query($sql) or die("Query failed : " . mysql_error());

if (mysql_num_rows($query) == 0) {
    echo "No rows found";
} else {
    echo "From {$_dayStart} to {$_dayFinish} found:";
    while ($row = mysql_fetch_assoc($query)) {
        var_dump($row);
    }
}

mysql_free_result($query);

mysql_close($connect);