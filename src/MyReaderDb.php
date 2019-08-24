<?php
/**
 * This file is part of josecarlosphp/reader - PHP classes to read from different sources.
 *
 * josecarlosphp/reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @see         https://github.com/josecarlosphp/reader
 * @copyright   2013-2019 José Carlos Cruz Parra
 * @license     https://www.gnu.org/licenses/gpl.txt GPL version 3
 * @desc        Class to read from a database's table.
 */

namespace josecarlosphp\reader;

class MyReaderDb extends MyReader
{
	private $_table;
	/**
	 * @var DbConnection
	 */
    private $_db;
	/**
	 * @var DB_ResultSet
	 */
    private $_rs;
	private $_assoc;
	/**
	 * Constructor
	 *
	 * @param string $table
	 * @param DB_Connection $db
	 * @param bool $assoc
	 */
	function __construct($table, $db, $assoc=true)
    {
        parent::__construct();
		$this->_table = $table;
        $this->_db = $db;
		$this->_assoc = $assoc ? true : false;
    }

    public function Open($filepath, $autoclose=false)
    {
        parent::Open($filepath, $autoclose);
        if($this->_db->Ping(true))
		{
			$this->_rs->Set = $this->_db->Execute("SELECT * FROM ´".$this->_table."´");
			return $this->_rs->Set !== false;
		}
		return false;
    }

    public function ReadRow()
    {
		$r = $this->_assoc ? $this->_rs->FetchAssoc() : $this->_rs->FetchRow();
        if($r)
        {
            $this->_i++;
            $this->_contador++;
        }
        return $r;
    }

    public function Close()
    {
		$this->_rs->FreeResult();
        return true;
    }

    public function Seek($offset, $whence = SEEK_SET)
    {
        switch($whence)
        {
            case SEEK_SET:
				$this->_i = 0;
				return $this->_rs->DataSeek($this->_i);
            case SEEK_CUR:
				$this->_i += $offset;
				return $this->_rs->DataSeek($this->_i);
        }
        return false;
    }
}
