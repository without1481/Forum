<?php if (!defined('VB_ENTRY')) die('Access denied.');
/*========================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2017 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/

/**
 * @package vBDatabase
 */

/**
 * @package vBDatabase
 */
class vB_dB_Result implements Iterator
{
	use vB_Trait_NoSerialize;

	/** This class is called by the new vB_dB_Assertor query class.. vB_dB_Query
	 * It's a wrapper for the class_core db class, but instead of calling
	 * db->fetch_array($recordset) it's implemented as an iterator.
	 * We also will allow returning the data in JSON or XML format.

	Properties====================================================================*/
	/** the shared database object **/
	protected $db = false;
	/** whether we should use the slave db **/
	protected $useSlave = false;

	/** The text of the query**/
	protected $querystring = false;

	/** The result recordset **/
	protected $recordset = false;

	protected $eof = false;
	protected $bof = false;

	/** The result recordset **/
	protected $resultrow = false;

	/** The result recordset **/
	protected $resultseq = 0;


	/**
	 * standard constructor
	 *
	 * @param 	mixed		the standard vbulletin db object
	 * @param 	mixed		the query string
	 *
	 */
	public function __construct(&$db, $querystring, $useSlave = false)
	{
		$this->querystring = $querystring;
		$this->db = $db;
		$this->useSlave = $useSlave;
		$this->rewind();
	}

	public function db()
	{
		return $this->db;
	}

	public function __destruct()
	{
		$this->free();
	}

	/* standard iterator method */
	public function current()
	{
		return $this->resultrow;
	}

	/* standard iterator method */
	public function key()
	{
		return $this->resultseq;
	}

	/* standard iterator method */
	public function next()
	{
		if ($this->eof)
		{
			return false;
		}
		if ($this->recordset AND !$this->eof)
		{
			$this->resultrow = $this->db->fetch_array($this->recordset);

			if (!$this->resultrow)
			{
				$this->eof = true;
			}

			$this->bof = false;
			$this->resultseq++;
		}
		return $this->resultrow;
	}

	/* standard iterator method */
	public function rewind()
	{
		//no need to rerun the query if we are at the beginning of the recordset.
		if ($this->bof)
		{
			return;
		}

		if ($this->recordset)
		{
			$this->db->free_result($this->recordset);
		}

		if (!$this->useSlave)
		{
			$this->recordset = $this->db->query_read($this->querystring);
		}
		else
		{
			$this->recordset = $this->db->query_read_slave($this->querystring);
		}

		if ($this->recordset === false)
		{
			$this->resultrow = false;
			$this->eof = true;
			$this->bof = true;
			return false;
		}
		$this->resultrow = $this->db->fetch_array($this->recordset);

		$this->resultseq = 0;
		if ($this->resultrow)
		{
			$this->eof = false;
		}
		else
		{
			$this->eof = true;
		}

		//bof = true and eof = true can happen if the result contains no records.
		$this->bof = true;
	}

	/* standard iterator method */
	public function valid()
	{
		return ($this->recordset AND !$this->eof AND ($this->resultrow !== false));
	}

	public function free()
	{
		if (isset($this->db) AND !empty($this->recordset) AND is_resource($this->recordset))
		{
			$this->db->free_result($this->recordset);
		}
	}
}
/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 94414 $
|| #######################################################################
\*=========================================================================*/
