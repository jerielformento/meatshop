<?php 

namespace REJ\Libs;

class LDAP {
	
	/* credential variables */
	
	/* checking existing user */
	public function bindUserToLDAP( $username, $password, $ldap ) {

		$_ldap = ldap_connect( $ldap['domain'] ) or die("Can't connect to server!");
	   
		if($bind = @ldap_bind( $_ldap, $username . $ldap['user_dom'], $password ))  {
			return true;
		} else {
			return false;
		}

		return false;
	}
	
	/*
	public function getUserGroupLDAP($user, $pass) {
		// Active Directory server
		$ldap_host = __LDAPDOMAIN__;
	 
		// Active Directory DN, base path for our querying user
		$ldap_dn = __LDAPSEARCHDN__;
	 
		// Active Directory user for querying
		$query_user = $user . __LDAPUSERDOM__;
		$password = $pass;
	 
		// Connect to AD
		$ldap = ldap_connect($ldap_host);
		ldap_bind($ldap,$query_user,$password);
		
		// Search AD
		$results = ldap_search($ldap,$ldap_dn,"(samaccountname=$user)",array("memberof","primarygroupid"));
		$entries = ldap_get_entries($ldap, $results);
		
		// No information found, bad user
		if($entries['count'] == 0) return false;
		
		// Get groups and primary group token
		$output = $entries[0]['memberof'];
		$token = $entries[0]['primarygroupid'][0];
		
		// Remove extraneous first entry
		array_shift($output);
		
		// We need to look up the primary group, get list of all groups
		$results2 = ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
		$entries2 = ldap_get_entries($ldap, $results2);
		
		// Remove extraneous first entry
		array_shift($entries2);
		
		// Loop through and find group with a matching primary group token
		foreach($entries2 as $e) {
			if($e['primarygrouptoken'][0] == $token) {
				// Primary group found, add it to output array
				$output[] = $e['distinguishedname'][0];
				// Break loop
				break;
			}
		}
	 
		return $output;
	}*/
}

?>