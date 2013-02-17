<?phprequire_once ( 'config.php' );require_once ( WWW_DIR . '/lib/sql/db_newzdash.php' );	Class SQLSchema	{		private $currentSchema = -1;				public function getSchema() { 			$this->currentSchema = $this->retSchema();			return $this->currentSchema;		}		public function setSchema($schema) { 			$schema = number_format($schema,1);			$nddb = new NDDB;			if ( $nddb->isOkay() )			{				if ( $this->isSchemaLoadedIntoTable() )				{					$query = "INSERT INTO `newzdash_version` (`SCHEMA_VERSION`) VALUES (\'" . $schema . "\');";				}else{					$query = "UPDATE `newzdash`.`newzdash_version` SET `SCHEMA_VERSION` = \'" . $schema . "\';";				}				$result = $nddb->queryInsert($query);				if ( $result === FALSE ) { return false; }else{ return true; }			}else{				die ( "SQL Error [schema.php]" );			}		}				public function checkForNewSchema()		{			//attempt a schema update if one has not been done yet.			if ( $this->currentSchema == -1 )				$this->getSchema();							$nextSchemaVersion = $this->getNextVersionNumber();			if ( $nextSchemaVersion > $this->currentSchema )			{				return true;			}else{				return false;			}		}				public function getNextSchemaVersion()		{			//attempt a schema update if one has not been done yet.			if ( $this->currentSchema == -1 )				$this->getSchema();							$nextSchemaVersion = $this->getNextVersionNumber();			if ( $nextSchemaVersion > $this->currentSchema )			{				return $nextSchemaVersion;			}else{				return $this->currentSchema;			}		}				/*			PRIVATE FUNCTIONS		*/				private function isSchemaLoadedIntoTable()		{			$nddb = new NDDB;			if ( $nddb->isOkay() )			{				$result = $nddb->queryOneRow("SELECT SCHEMA_VERSION FROM newzdash_version;");				//$result = ($nddb->queryOneRow("SELECT SCHEMA_VERSION FROM newzdash_version;") !== FALSE) ? true : false;				if ( $result === FALSE )				{					return false;				}else{					return true;				}			}else{				die ( "SQL Error [schema.php]" );			}		}				private function retSchema()		{			if ( $this->isSchemaLoadedIntoTable() )			{				$nddb = new NDDB;				if ( $nddb->isOkay() )				{					$result = $nddb->queryOneRow("SELECT SCHEMA_VERSION FROM newzdash_version;");					if ( !$result === FALSE )					{						return $result['SCHEMA_VERSION'];					}else{						return null;					}				}else{					die ( "SQL Error [schema.php]" );				}			}else{				return 0;			}		}				private function getNextVesionNumber()		{			$currVer = number_format($this->currentSchema + 0.1,1);						if ( file_exists(WWW_DIR . '/install/sql/schema/' . $currVer ) )			{				return $currVer;			}else{				return $this->currentSchema;			}		}	}?>