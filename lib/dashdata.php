<?php

if (!class_exists ( "DB" ))
	require_once ( WWW_DIR . "/lib/sql/db.php" );

class DashData
{
	public function time_elapsed($secs)
	{
		if ( $secs == "" )
			return 0;
		
	    if ($secs<=0)
	    {
			return "now";
	    }
	    
	    $bit = array(
			'y' => $secs / 31556926 % 12,
			'w' => $secs / 604800 % 52,
			'd' => $secs / 86400 % 7,
			'h' => $secs / 3600 % 24,
			'm' => $secs / 60 % 60,
			's' => $secs % 60
		);
        
		$ret = null;
		foreach($bit as $k => $v)
		{
			if($v > 0)
			{
				$ret[] = $v . $k;
			}
		}
        
		if ( $ret == null ) {
			return $secs;
		}else{
			$strtext = join(' ', $ret) . " ago";
			return $strtext;
		}
	}
	    
	/**
	 * getLastGroupUpdate
	 */
	public function getLastGroupUpdate($apiRun=false)
	{
	    $sql=sprintf("select name,last_updated,NOW(),unix_timestamp(NOW())-unix_timestamp(last_updated) as age from groups order by last_updated desc limit 0,5");
	    $db = new DB;
	    $data = $db->queryOneRow($sql);
	    $age_of_package=DashData::time_elapsed($data['age']);
	    
		if ( $apiRun ) 
		{
			return $age_of_package;
		}else{
			printf('<span class="icon32 icon-blue icon-clock"></span>
				<div>Last Group Update</div>
				<div>%s</div>', $age_of_package);
		}
	    
	    
	}
    
	/**
	 * getLastBinaryAdded
	 */
	public function getLastBinaryAdded($apiRun=false)
	{
	    /*
	    $sql=sprintf("select relname,dateadded from binaries order by dateadded desc limit 0,5");
	    $db = new DB;
	    $data = $db->queryOneRow($sql);
	    */
	    $sql=sprintf("select relname,dateadded,NOW(),unix_timestamp(NOW())-unix_timestamp(dateadded) as age from binaries order by dateadded desc limit 0,5");
	    $db = new DB;
	    $data = $db->queryOneRow($sql);
	    $age_of_package=DashData::time_elapsed($data['age']);

		if ( $apiRun ) 
		{
			return $age_of_package;
		}else{
			printf('<span class="icon32 icon-blue icon-clock"></span>
			<div>Last Binary Added</div>
			<div>%s</div>', $age_of_package);
		}
	}	
    
	/**
	 * getLastReleaseCreated
	 */
	public function getLastReleaseCreated($apiRun=false)
	{
	    $sql=sprintf("select name,adddate,unix_timestamp(NOW())-unix_timestamp(adddate) as age from releases order by adddate desc limit 0,5");
	    $db = new DB;
	    $data = $db->queryOneRow($sql);
	    $age_of_package=DashData::time_elapsed($data['age']);
	    
		if ( $apiRun )
		{
			return $age_of_package;
		}else{
			printf('<span class="icon32 icon-blue icon-clock"></span>
			<div>Last Release Created</div>
			<div>%s</div>', $age_of_package);
		}
		
	}    
	
	public function getPartsTableSize($apiRun=false)
	{
		$sql = "SELECT table_rows as cnt FROM information_schema.TABLES where table_name = 'parts' AND table_schema='" . DB_NAME . "';";
		$db = new DB;
		$data = $db->queryOneRow($sql);
		return number_format($data['cnt']);
	}
	
	public function getPartsTableDBSize($apiRun=false)
	{
		$sql = "SELECT concat(round((data_length+index_length)/(1024*1024*1024),2),'GB') AS cnt FROM information_schema.tables where table_name = 'parts' AND table_schema='" . DB_NAME . "';";
		$db = new DB;
		$data = $db->queryOneRow($sql);
		return $data['cnt'];
	}
	
	
	/**
	 * getNewzNabTmuxInfo
	 */
	public function getNewzNabTmuxInfo()
	{
		$tmuxpath = realpath(NEWZNAB_HOME).'/misc/update_scripts/nix_scripts/tmux';
	    if (file_exists($tmuxpath.'/.git/HEAD'))
	    {
		$stringfromfile = file($tmuxpath.'/.git/HEAD', FILE_USE_INCLUDE_PATH);

		$stringfromfile = $stringfromfile[0]; //get the string from the array
    
		$explodedstring = explode("/", $stringfromfile); //seperate out by the "/" in the string
    
		$branchname = $explodedstring[2]; //get the one that is always the branch name
		$branchname = trim($branchname);
		   
		if (file_exists($tmuxpath."/.git/refs/heads/".$branchname))
		{
		    $gitversion=file_get_contents($tmuxpath."/.git/refs/heads/".$branchname);
		}
		else
		{
		    $gitversion="unknown";
		}
	    
		printf('<span class="icon32 icon-blue icon-gear"></span>
			    <div>NewzNab-tmux Branch: %s</div>
			    <div>Revision: %s</div>', $branchname, substr($gitversion, 0, 10)."...");
	    }
	    else
	    {
		printf('<span class="icon32 icon-blue icon-gear"></span>
			    <div>NewzNab-tmux Branch: %s</div>
			    <div>Revision: %s</div>', "unknown", "unknown");	    
			    
	    }
	}
    				
   
    
	/**
	 * getRegexInfo
	 */
	public function getNewzDashInfo()
	{
	
	    if (file_exists('.git/HEAD'))
	    {
		$stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);

		$stringfromfile = $stringfromfile[0]; //get the string from the array
    
		$explodedstring = explode("/", $stringfromfile); //seperate out by the "/" in the string
    
		$branchname = $explodedstring[2]; //get the one that is always the branch name
		$branchname = trim($branchname);
		   
		if (file_exists(".git/refs/heads/".$branchname))
		{
		    $gitversion=file_get_contents(".git/refs/heads/".$branchname);
		}
		else
		{
		    $gitversion="unknown";
		}
	    
		printf('<span class="icon32 icon-blue icon-gear"></span>
			    <div>NewzDash Branch: %s</div>
			    <div>Revision: %s</div>', $branchname, substr($gitversion, 0, 10)."...");
	    }
	    else
	    {
		printf('<span class="icon32 icon-blue icon-gear"></span>
			    <div>NewzDash Branch: %s</div>
			    <div>Revision: %s</div>', "unknown", "unknown");	    
			    
	    }
	}
	
    	/**
	 * getDatabaseInfo
	 */
	public function getDatabaseAndRegexInfo()
	{
	    $sql=sprintf("select * from site where `setting`='dbversion'");
	    $db = new DB;
	    $data = $db->queryOneRow($sql);
	    # $version = $data['value'];
	    # now, we want just the numbers as the version is stored as '#Rev: number $'
	    preg_match('/[0-9]+/', $data['value'], $version);
	    
	    $sql=sprintf("select * from site where `setting`='latestregexrevision'");
	    $db = new DB;
	    $data = $db->queryOneRow($sql);

	    printf('<span class="icon32 icon-blue icon-gear"></span>
			<div>Database Version: %s</div>
			<div>Regex Version: %s</div>', $version[0], $data['value']);
	    
	    
	}
    
	/**
	 * getSubversionInfo
	 */
	public function getSubversionLatestFromRss()
	{

	    # cache the rss feed so we don't hammer the server
	    # and a slight delay in knowing a new release is available is not that bad
	    if (xcache_isset("newznabrss"))
	    {
			$xml_source = xcache_get("newznabrss");
	    }
	    else
	    {
			$xml_source = file_get_contents('http://newznab.com/plussvnrss.xml');
			# store it for 15 minutes
			xcache_set("newznabrss", $xml_source, 60*15);
	    }
	    
	    $x = simplexml_load_string($xml_source);
	    
	    if(count($x) == 0)
		return "";

	    $rev=$x->channel->item[0]->title;
	    preg_match('/[0-9]+/', $rev, $latest);

	    return $latest[0];

	}
	
	/**
	 * getSubversionInfo
	 */
	public function getSubversionInfo()
	{

	    if (extension_loaded('svn')) {
		#svn_auth_set_parameter( SVN_AUTH_PARAM_DEFAULT_USERNAME, SVN_USERNAME );
		#svn_auth_set_parameter( SVN_AUTH_PARAM_DEFAULT_PASSWORD, SVN_PASSWORD );
		$svn_stat=svn_status(realpath(NEWZNAB_HOME), SVN_NON_RECURSIVE|SVN_ALL);
		$current_version=sprintf("%s", $svn_stat[0]["cmt_rev"]);
		
	
		#$svn_info=svn_info(realpath(NEWZNAB_HOME), SVN_SHOW_UPDATES);
		#$latest_version=sprintf("%s", $svn_info[0]["last_changed_rev"]);
		$latest_version=DashData::getSubversionLatestFromRss();
    
		    
		if ($current_version === $latest_version)
		{
		    $version_string=sprintf("Running latest version (%s)", $current_version);
		    $notification_string="";
		}
		else
		{
		    $version_string=sprintf("Running %s, Latest available is %s", $current_version, $latest_version);
		    $updates_available=intval($latest_version)-intval($current_version);
		    # $notification_string=sprintf('<span class="notification red">%d</span>', $updates_available);
		    $notification_string=sprintf('<span class="notification red">!</span>');
		}
		
		printf('<span class="icon32 icon-blue icon-gear"></span>
			    <div>NewzNab SVN Revision</div>
			    <div>%s</div>
			    %s', $version_string, $notification_string);
	    }
	    else
	    {
		printf('<span class="icon32 icon-blue icon-gear"></span>
			    <div>NewzNab SVN Revision</div>
			    <div>%s</div>', "php subversion module is not installed");
	    }
	}
	
        /**
         * count of releases
         */
        public function getReleaseCount($retOnlyValue=false)
        {
            $sql = "SELECT table_rows as cnt FROM information_schema.TABLES where table_name = 'releases' AND table_schema='" . DB_NAME . "';";
			$db = new DB;
			$data = $db->queryOneRow($sql);
			return number_format($data['cnt']); 
        }
        
        public function getActiveGroupCount($apiRun=false)
        {
            $sql = "SELECT count(active) as cnt from `groups` WHERE active=1;";
			$db = new DB;
			$data = $db->queryOneRow($sql);
			return number_format($data['cnt']);
        }
        
        public function getPendingProcessingCount($apiRun=false)
        {
            $db=new DB;
            
            # $sql_query=sprintf("select COUNT(*) AS ToDo from releases r left join category c on c.ID = r.categoryID where (r.passwordstatus between -6 and -1) or (r.haspreview = -1 and c.disablepreview = 0)");

             /////////////amount of books left to do//////
            $sql_query = "select count(*) as ToDo from releases use index (ix_releases_categoryID) where (bookinfoID IS NULL and categoryID = 7020) or ";
            /////////////amount of games left to do//////
            $sql_query= $sql_query . " (consoleinfoID IS NULL and categoryID in ( select ID from category where parentID = 1000 )) or ";
            /////////////amount of movies left to do//////
            $sql_query = $sql_query . "(imdbID IS NULL and categoryID in ( select ID from category where parentID = 2000 )) or ";
            /////////////amount of music left to do//////
            $sql_query = $sql_query . "(musicinfoID IS NULL and categoryID in ( select ID from category where parentID = 3000 )) or ";
            /////////////amount of tv left to do/////////
            $sql_query = $sql_query . "(rageID = -1 and categoryID in ( select ID from category where parentID = 5000 ));";

            
            $data = $db->query($sql_query);
			$total = $data[0]['ToDo'];

			if ( $apiRun ) {
				return number_format($total);
			}else{
				printf('<span class="icon32 icon-blue icon-star-off"></span>
				<div>Pending Processing</div>
				<div>%s</div>', $total);   	
			}
        }
        

}
        
                        




?>
