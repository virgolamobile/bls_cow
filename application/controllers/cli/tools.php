<?php

class Tools extends CI_Controller {
	
	/**
	 * Prende una tabella contenente nome di luoghi, lat, lon.
	 * Aggiungere la colonna geo e lasciare che lavori.
	 * Indipendente dall'id delle rispettive colonne scope perché fa riferimento a geo + scope.
	 */
	public function collapse_down_geo($scope,$lon_col='longitude',$lat_col='latitude',$id_col='id')
	{
		echo "\n\n";

		$query = $this->db->get($scope);
		$all = $query->result_array();

		foreach($all as $row)
		{
			$scope_id = $row[$id_col];
			
			$data['lon'] = $row[$lon_col];
			$data['lat'] = $row[$lat_col];
			$data['scope'] = $scope;
			
			// salvo il nuovo geo
			$this->db->insert('geo',$data);
			$geo_id = $this->db->insert_id();

			echo 'inserito geo [' . $geo_id . '] '; 

			// aggiorno lo scope con l'id del geo
			$this->db->where('id',$scope_id);
			$this->db->set(array('geo'=>$geo_id));
			$this->db->update($scope);
			
			echo 'e aggiornato ' . $scope . ' [' . $scope_id . ']' . "\n";
			
		}

		echo "\n\n";
	}
	
	/**
	 * Database dumb creator
	 */
	public function db_dump($format='gzip')
	{
		// Load the DB utility class
		$this->load->dbutil();
		
		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();
		
		echo $backup; 
		
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$path = 'install/sql/'.time().'.gz';
		write_file($path, $backup); 
	}
	
}
