<?php

include( "model/user.php" );
include( "libs/php/core.php" );

/* TODO: ( added by tenach )
 * List memberships on projects
 * List user involvements (bugs, projects)
 */


// !useScript("tablehover.js"); - Will make things look funny.

requireLogin();

useScript( "edit-menu.js" );

// Need to convert this to divs so that tablehover.js can be used. -Tenach
$adminMenu = "<table>
	<tr class = 'center'>
		<td><h2><a href = '" . $SITE_PREFIX . "t/admin/user'>User Administration</a></h2></td>
		<td><h2><a href = '" . $SITE_PREFIX . "t/admin/project'>Project Administration</a></h2></td>
	</tr>
	<tr class = 'center'>
		<td><a href = '" . $SITE_PREFIX . "t/register' >Add new user</a></td>
		<td><a href = '" . $SITE_PREFIX . "t/new-project' >Add new project </a></td>
	</tr>
</table>";

if( isset( $argv[1] ) ) {
	$editButton = "<img id = 'edit-" . $argv[1] . "-control' src = '" . $SITE_PREFIX . "imgs/edit.png'   alt = 'edit'   />";
}

$users = $USER_OBJECT->getAllUsers();

$app_root = dirname(__FILE__) . "/../";
include( $app_root . "libs/php/markdown.php" );

if( sizeof($argv) > 1 ) {
	$TITLE = ucwords( $argv[1] );
	
	if( isset( $argv[2]) ) {
		if( strpos( '-', $argv[2] ) ) {
			$TITLE = str_replace( '-', ' ', $argv[2] );
		}
	}
	
	$CONTENT = $adminMenu;	
	
	if( isset( $argv[2] ) ) {
		if( $argv[1] == "user" ) {
			$USER_OBJECT->getByCol("username", $argv[2]);
			$user = $USER_OBJECT->getNext();
			$TITLE = "User " . $user['real_name'];
			$CONTENT = $adminMenu;
			$CONTENT .= "<h1>" . $user['real_name'] . " (" . $user['username'] . ")" . " <a href = '" . $SITE_PREFIX . "t/register/update/" . $argv[2] . "' >" . $editButton . "</a></h1>";
			
			if( $user['private'] == 0 ) $private = "No";
			if( $user['private'] == 1 ) $private = "Yes";
			
			// Getting exposed like a naked monkey
			// and tabled like a bad hand of cards
			$CONTENT .= "User ID: " . $user['uID'] . "<br />
			Real Name: " . $user['real_name'] . "<br />
			Username: " . $user['username'] . "<br />
			Email: " . $user['email'] . "<br /> 
			Locale: " . $user['locale'] . "<br />
			Timezone: " . $user['timezone'] . "<br />
			Private: " . $private . "<br />
			Password: " . $user['password'] . "<br />";
		}
		
		if( $argv[1] == "project") {
			$projName = '';
			if( strpos( $argv[2], '-' ) ) {
				$projName = str_replace ( '-', ' ', $argv[2] );
			}
			$PROJECT_OBJECT->getByCol("project_name", $projName);
			$project = $PROJECT_OBJECT->getNext();
			
			$active = "Yes"; $private = "No";
			if( $project['active'] == 0 ) $active = "No";
			if( $project['private'] == 1 ) $private = "Yes";
			
			$USER_OBJECT->getByCol("uID", $project['owner']);
			$owner = $USER_OBJECT->getNext();

			$TITLE = "Project " . $projName;
			$CONTENT .= "<h1>" . $projName . " <a href = '" . $SITE_PREFIX . "t/admin/" . $argv[1] . "/" . $argv[2] . "/update' >" . $editButton . "</a></h1><br />\n";
			$CONTENT .= "Project Name: " . $project['project_name'] ."<br />
			Description: " . $project['descr'] . "<br />
			Owner: " . $owner['real_name'] . " (" . $owner['username'] . ")<br /> 
			Active: " . $active . "<br />
			Private: " . $private . "<br />";
		}		
	}	else if( isset( $argv[1] ) ) {
		$list = "<tr><td class = 'center' >Go kick some ass using the links above.</td></tr>";
		
		if( $argv[1] == "user" ) {
			$numUsers = count( $users );
			$i = 0;
			$list = '';

			while( $i < $numUsers ) {
				$list .= "<tr style = 'cursor:pointer' onclick=\"document.location.href = '" . $SITE_PREFIX . "t/admin/user/" . $users[$i]['username'] . "'\">
										<td><a href = '" . $SITE_PREFIX . "t/admin/user/" . $users[$i]['username'] . "'>" . $users[$i]['real_name'] ."</a></td> <td>" . $users[$i]['username'] ."</td> <td>" . $users[$i]['email'] ."</td> 
									</tr>";
				$i++;
			}
		}
		
		if( $argv[1] == "project" ) {
			$projects = $PROJECT_OBJECT->getAllProjects();
			$numProjects = count( $projects );
			$i = 0;
			$list = '';
			
			while( $i < $numProjects ) {
				$USER_OBJECT->getByCol( "uID", $projects[$i]['pID'] );				
				$projectOwner = $USER_OBJECT->getNext();
				
				if( $projects[$i]['isTeam'] == 0 ) {
					$projectOwner = $projectOwner['real_name'] . " (" . $projectOwner['username'] . ")";
					$projectLink = $projects[$i]['project_name'];
				
					if ( strpos ( $projects[$i]['project_name'], ' ' ) ) {
						$projectLink = str_replace ( ' ', '-', $projects[$i]['project_name'] );
					}
				
					$list .= "<tr style = 'cursor:pointer' onclick=\"document.location.href = '" . $SITE_PREFIX . "t/admin/project/" . $projects[$i]['project_name'] . "'\">
											<td><a href = '" . $SITE_PREFIX . "t/admin/project/" . $projectLink . "'>" . $projects[$i]['project_name'] ."</a></td> <td style = 'width:500px;'>" . $projects[$i]['descr'] ."</td> <td>" . $projectOwner ."</td> 
										</tr>";
				}
				$i++;
			}
		}
		$CONTENT .= "<h1>" . $argv[1] . "</h1></br />";
		$CONTENT .= "<table class = 'sortable' >";
		$CONTENT .= $list;
		$CONTENT .="</table>";
	}
	
	if( isset( $argv[3] ) && $argv[3] == "update" && $argv[1] == "project" ) {		
		$name = $argv[2];
		if( strpos( $argv[2], '-' ) ) {
			$name = str_replace( '-', ' ', $argv[2] );
		}

		$PROJECT_OBJECT->getByCol('project_name', $name);
		$project = $PROJECT_OBJECT->getNext();
		$users = $PROJECT_OBJECT->userMembership($project['pID']);
		$memberList = '';
		$memberID = array();
		$p = 0;
		$pc = count( $users );
		while( $p < $pc ) {
			$USER_OBJECT->GetByCol( 'uID', $users[$p]['userID'] );
			$member = $USER_OBJECT->getNext();
			if( $users[$p]['active'] == 1 ) {
				$memberList .= "<input type = 'checkbox' name = 'delUsers[]' value = '" . $project['pID'] . "::" . $member['uID'] . "' /> " . $member['real_name'] . " (<a href = '" . $SITE_PREFIX . "t/admin/user/" . $member['username'] . "'>" . $member['username'] . "</a>)<br />";
			}
			$p++;
		}
		
		$addUsers = $USER_OBJECT->customSelect( "*", "users", "uID NOT IN ( SELECT userID FROM " . $TABLE_PREFIX . "project_members WHERE projectID = ". $project['pID'] . ")" );
		$inactive = $PROJECT_OBJECT->customSelect( "*", "project_members", "active = 0" );
		$pc = count( $addUsers );
		$pic = count( $inactive );
		$p = 0;		
		$addList = '';
		
		while( $p < $pc ) {
			$USER_OBJECT->getByCol( 'uID', $addUsers[$p]['uID'] );
			$PROJECT_OBJECT->getByCol( 'active', $addUsers[$p]['uID'] );
			$upforgrabs = $USER_OBJECT->getNext();
			$addList .= "<option value = '". $project['pID'] . "::" . $upforgrabs['uID'] . "'>" . $upforgrabs['real_name'] . "</option>\n";
			$p++;
		}
		$p = 0;
		while( $p < $pic ) {
			$USER_OBJECT->getByCol( 'uID', $inactive[$p]['userID'] );
			$PROJECT_OBJECT->getByCol( 'active', $inactive[$p]['userID'] );
			$upforgrabs = $USER_OBJECT->getNext();
			$addList .= "<option value = '". $project['pID'] . "::" . $upforgrabs['uID'] . "'>" . $upforgrabs['real_name'] . "</option>\n";
			$p++;
		}
		
		
		$TITLE = "Update " . $name;
		$CONTENT = "<h1>Update " . $name . "</h1>";
		$CONTENT .= " <form action = '" . $SITE_PREFIX . "l/submit-project' method = 'post' >
	<table>
		<tr>
			<td>Project Name:</td>
			<td><input type = 'text' id = 'project' name = 'newProject' size = '20' value = '" . $project['project_name'] . "' /></td>
			<td><strong>Project Members</strong> (Check to remove)</td>
		</tr>
		<tr>
			<td></td>
			<td><div id = 'project-name'></div></td>
			<td rowspan = '3' style = 'vertical-align:text-top;'>"
			. $memberList;
			if( $addList != '' ) {
			$CONTENT .= "<br /><br /><strong>Add user:</strong><br />
			Only select users if you wish to add them.<br />
			<select name = 'addUsers[]' size = '3' multiple = 'multiple' >"
				. $addList .
				"</select>";
			}
			$CONTENT .= "</td>
		</tr>
		<tr>
			<td>Description:</td>
			<td><textarea rows = '20' cols = '50' name = 'projDescr'>" . $project['descr'] ."</textarea>
			<input type = 'hidden' name = 'update' id = 'update' value = '1' />
			</td>
		</tr>
		<tr>
			<td><img src = '" . $SITE_PREFIX . "imgs/32_space.png' alt = '' /></td>
			<td><input type = 'submit' value = 'Look, I made this for you!' /></td>
		</tr>
	</table>
	</form>";
	}
	
} else {
	$TITLE    = "Time to do the dirty!";

	$CONTENT .= $adminMenu;
	$CONTENT .= "<br /><h1>Heyya, " . $_SESSION['real_name'] . "</h1>Here's your administration page. " . $TITLE . "<br />\n";
}

?>
