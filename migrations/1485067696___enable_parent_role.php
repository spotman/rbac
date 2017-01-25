<?php defined('SYSPATH') or die('No direct access allowed.');

class Migration1485067696_Enable_Parent_Role extends Migration {

	/**
	 * Returns migration ID
	 *
	 * @return integer
	 */
	public function id()
	{
		return 1485067696;
	}

	/**
	 * Returns migration name
	 *
	 * @return string
	 */
	public function name()
	{
		return 'Enable_parent_role';
	}

	/**
	 * Returns migration info
	 *
	 * @return string
	 */
	public function description()
	{
		return '';
	}

	/**
	 * Takes a migration
	 *
	 * @return void
	 */
	public function up()
	{
	    $table = 'roles_inheritance';

	    if (!$this->table_exists($table)) {
	        $this->addParentsPivotTable($table);
        }
	}

	protected function addParentsPivotTable($table)
    {
        $this->run_sql("
        CREATE TABLE `$table` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `child_id` int(11) unsigned NOT NULL,
            `parent_id` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `child_id_parent_id` (`child_id`,`parent_id`),
            KEY `child_id` (`child_id`),
            KEY `parent_id` (`parent_id`),
            CONSTRAINT `roles_inheritance_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
            CONSTRAINT `roles_inheritance_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        ;");
    }

	/**
	 * Removes migration
	 *
	 * @return void
	 */
	public function down()
	{

	}

} // End Migration1485067696_Enable_Parent_Role
