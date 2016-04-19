<?php 
	#Connect to Database 
	$con = mysqli_connect("localhost","root","", "surroundSound"); 
			 
	#Check connection 
	if (mysqli_connect_errno()) 
	{ 
		echo 'Database connection error: ' . mysqli_connect_error(); 
		exit(); 
	}

############################### Get top 10 played track ##################################
    if (isset($_POST["GetTop10"]))
	{ 
		#Query the database to get top 10 played track. 
		$getTop10List = mysqli_query($con, "SELECT  track, artist, count(*) AS played_count FROM played_history GROUP BY track , artist ORDER BY played_count desc limit 10;");
		
		#If no data was returned, check for any SQL errors 
        if(!$getTop10List) { 
            echo 'Could not run query: ' . mysqli_error($con); 
            exit; 
        }
		
		$getTop10List_result = array();
		
		while($row = mysqli_fetch_array($getTop10List, MYSQL_ASSOC))
		{
			$row_array['Track'] = $row['track'];
			$row_array['Artist'] = $row['artist'];
			$row_array['Played Count'] = $row['played_count'];
			
			array_push($getTop10List_result, $row_array);
		}
		
		#Output the JSON data 
		echo json_encode($getTop10List_result);
	}	
	
############################### Get current playing track ##################################
	elseif (isset($_POST["GetCurrentPlaying"]))
	{
		#Query the database to get the list of currently playing tracks
		$trackDetails = mysqli_query($con, "SELECT track, artist FROM current_playing");

        #If no data was returned, check for any SQL errors 
        if(!$trackDetails) { 
            echo 'Could not run query: ' . mysqli_error($con); 
            exit; 
        } 
		
		$databaseArray = array();
		
		while($row = mysqli_fetch_array($trackDetails, MYSQL_ASSOC)) {
			$row_array['Track'] = $row['track'];
			$row_array['Artist'] = $row['artist'];
			
			array_push($databaseArray, $row_array);
		}
		
		echo json_encode($databaseArray);
    }
############################### ADD Track ##################################	
	elseif ((isset($_POST["Add_Track"]) && $_POST["Add_Track"] != "") && (isset($_POST["Add_Artist"]) && $_POST["Add_Artist"] != ""))
	{ 
		$track_name = $_POST["Add_Track"];
		$artist_name = $_POST["Add_Artist"];
		
		$track_name = mysqli_real_escape_string($con, $track_name);
		$artist_name = mysqli_real_escape_string($con, $artist_name);
		
		$add_check = mysqli_query($con, "SELECT * FROM current_playing WHERE track = '$track_name' AND artist = '$artist_name'");
		$add_check_row_num = mysqli_num_rows($add_check);
		
		#check sql statement
		if(!$add_check) 
		{
			die('Add_Check Error'.mysqli_errno($con));
		}
		else
		{
			echo 'Add_check complete ';
		}
		
		if ($add_check_row_num == 0)
		{ 
			$add_track = mysqli_query($con,"INSERT INTO current_playing (track, artist, device_count) VALUES ('$track_name', '$artist_name', '1')");
			
			if(!$add_track)
			{
				die('Add Current Error'.mysqli_errno($con));
			}
			else
			{
				echo 'Track added to current_playing ';
			}
		}
		else
		{
			$find_count = mysqli_query($con,"SELECT device_count FROM current_playing WHERE track = '$track_name' AND  artist = '$artist_name'");
			
			if(!$find_count)
			{
				die('Add Current Error'.mysqli_errno($con));
			}
			else
			{
				echo 'Device count found ';
			}
			
			$find_count_result = mysqli_fetch_assoc($find_count);
			$previous_count = $find_count_result['device_count'];
			
			$new_count = $previous_count + 1;
		
			$update_count = mysqli_query($con,"UPDATE current_playing SET device_count = '$new_count' WHERE track = '$track_name' AND artist = '$artist_name'");
			
			if(!$update_count)
			{
				die('Add Count Error'.mysqli_errno($con));
			}
			else
			{
				echo 'Add_Track count updated ';
			}
		}
		
		$add_to_history = mysqli_query($con, "INSERT INTO played_history (track, artist) VALUES ('$track_name', '$artist_name')");
		
		if(!$add_to_history)
		{
			die('Add History Error'.mysqli_errno($con));
		}
		else
		{
			echo 'Track added to played_history ';
		}
		
	}
############################### Remove Track ##################################	
	elseif (((isset($_POST["Remove_Track"]) && $_POST["Remove_Track"] != "") && (isset($_POST["Remove_Artist"]) && $_POST["Remove_Artist"] != "")))
	{
		$track_name = $_POST["Remove_Track"];
		$artist_name = $_POST["Remove_Artist"];
		
		$track_name = mysqli_real_escape_string($con, $track_name);
		$artist_name = mysqli_real_escape_string($con, $artist_name);
		
		$find_count = mysqli_query($con,"SELECT device_count FROM current_playing WHERE track = '$track_name' AND  artist = '$artist_name'");
			
		if(!$find_count)
		{
			die('Add Current Error'.mysqli_errno($con));
		}
		else
		{
			echo 'Device count found ';
		}
		
		$find_count_result = mysqli_fetch_assoc($find_count);
		$previous_count = $find_count_result['device_count'];
		
		if($previous_count <= 1)
		{
			$delete_entry = mysqli_query($con,"DELETE FROM current_playing WHERE track = '$track_name' AND artist = '$artist_name'");
			
			if(!$delete_entry)
			{
				die('Remove Error'.mysqli_errno($con));
			}
			else
			{
				echo 'Track Removed';
			}
		}
		else
		{
			$new_count = $previous_count - 1;
			$update_count = mysqli_query($con,"UPDATE current_playing SET device_count = '$new_count' WHERE track = '$track_name' AND artist = '$artist_name'");
			
			if(!$update_count)
			{
				die('Remove Count Error'.mysqli_errno($con));
			}
			else
			{
				echo 'Remove_Track count updated ';
			}
			
		}		
	}		
	else
	{
		die('Error');
	}
	mysqli_close($con);
?>