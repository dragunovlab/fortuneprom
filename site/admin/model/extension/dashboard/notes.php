<?php
class ModelExtensionDashboardNotes extends Model {

	public function getNote($notes_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "notes
		WHERE notes_id = " . (int)$notes_id;
		$query = $this->db->query($sql);
		return $query->row;
	}
	public function editNote($notes_id,$data) {
		if (!isset($data['user'])) {
			$users = array($this->user->getId());
		} else {
			$users = $data['user'];
		}
		if (isset($data['color'])) {
			$color = $data['color'];
		} else {
			$color = 'ffffff';
		}

		$user_id = $this->user->getId();
		foreach ($users as $user) {
			if ($user == $user_id) {
				$sql = "UPDATE " . DB_PREFIX . "notes"; 
			} else {
				$sql = "INSERT INTO  " . DB_PREFIX . "notes"; 
			}
			$sql .= " SET
			`title` = '" . $this->db->escape($data['title']) . "',
			`notes` = '" . $this->db->escape($data['notes']) . "',";

			if ($user != $user_id) {
				$sql .= "`date_added` = NOW(),";
			}
			$sql .= "`from_user_id` = " . (int)$user_id . ",
			`color` = '" . $this->db->escape($color) . "',
			`to_user_id`  = " . (int)$user;
			if ($user == $user_id) {
				$sql .= " WHERE notes_id = " . (int)$notes_id;
			}
			$this->db->query($sql);
			if ($user == $user_id) {
				$last_id = $this->db->getLastId();
			}
		}
	}

	public function addNote($data) {
		if (!isset($data['user'])) {
			$users = array($this->user->getId());
		} else {
			$users = $data['user'];
		}
		if (isset($data['color'])) {
			$color = $data['color'];
		} else {
			$color = 'ffffff';
		}
		$last_id = 0;
		$user_id = $this->user->getId();
		foreach ($users as $user) {
			$sql = "INSERT INTO  " . DB_PREFIX . "notes"; 
			$sql .= " SET
			`title` = '" . $this->db->escape($data['title']) . "',
			`notes` = '" . $this->db->escape($data['notes']) . "',";
			$sql .= "`date_added` = NOW(),";
			$sql .= "`from_user_id` = " . (int)$user_id . ",
			`color` = '" . $this->db->escape($color) . "',
			`to_user_id`  = " . (int)$user;
			$this->db->query($sql);
			if ($user_id == $user) {
				$last_id = $this->db->getLastId();
			}
		}
		return $last_id;
	}

	public function getNotes($data=array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "notes
		WHERE to_user_id = " .  (int)$this->user->getId() . "
		ORDER BY date_added DESC";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTotalNotes($data=array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "notes
		WHERE to_user_id = " .  (int)$this->user->getId() . "";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function deleteNotes($notes_id) {
		$sql = "DELETE FROM " . DB_PREFIX . "notes
		WHERE notes_id = " . (int)$notes_id;
		$query = $this->db->query($sql);
	}

	public function getUsers() {
		$sql = "SELECT user_id, username FROM " . DB_PREFIX . "user WHERE status=1";
		$re = $this->db->query($sql);
		$user_data = array();
		foreach ($re->rows as $row) {
			$user_data [$row['user_id']] = $row['username']; 
		}
		return $user_data;
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "notes");
	}

	public function install() {
		$sqls = array();
		$sqls[] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "notes` (
			`notes_id` INT(11) NOT NULL AUTO_INCREMENT,
			`user_name` VARCHAR(255) COLLATE utf8_general_ci NOT NULL,
			`title` VARCHAR(255) COLLATE utf8_general_ci NOT NULL,
			`notes` TEXT COLLATE utf8_general_ci NOT NULL,
			`date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`from_user_id` INT(11) NOT NULL DEFAULT 0,
			`color` CHAR(32) DEFAULT '000000',
			`to_user_id` INT(11) NOT NULL,
			PRIMARY KEY (`notes_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		$user_id = $this->user->getId();

		$sqls[] = "INSERT INTO `" . DB_PREFIX . "notes` SET
			`title`  = 'Добро пожаловать',
			`notes`  = 'Добро пожаловать',
			`date_added` = NOW(),
			`color` = 'ed5565',
			`from_user_id` = 0,
			`to_user_id` = " . (int)$user_id;
		$sqls[] = "INSERT INTO `" . DB_PREFIX . "notes` SET
			`title`  = 'Awesome title',
			`notes`  = 'The years, sometimes by accident, sometimes on purpose (injected humour and the like).',
			`date_added` = NOW(),
			`color` = '23c6c8',
			`from_user_id` = 0,
			`to_user_id` = " . (int)$user_id;
		$sqls[] = "INSERT INTO `" . DB_PREFIX . "notes` SET
			`title`  = 'Awesome date',
			`notes`  = 'The years, sometimes by accident, sometimes on purpose (injected humour and the like).',
			`date_added` = NOW(),
			`color` = 'ed5565',
			`from_user_id` = 0,
			`to_user_id` = " . (int)$user_id;
		$sqls[] = "INSERT INTO `" . DB_PREFIX . "notes` SET
			`title`  = 'Awesome project',
			`notes`  = 'The years, sometimes by accident, sometimes on purpose (injected humour and the like).',
			`date_added` = NOW(),
			`color` = 'f8ac59',
			`from_user_id` = 0,
			`to_user_id` = " . (int)$user_id;

		foreach ($sqls as $sql) {
			$this->db->query($sql);
		}
	}
}
