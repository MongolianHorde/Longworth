<!DOCTYPE HTML> 

<html>
<head>
   <meta name="author" content="Mr Sparks" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
   <link href="style.css" rel="stylesheet" type="text/css" />
   <title>Joey Longworth</title>
   </head>
	
	<?php
	// Author: David Hughen
	// Date: 7/27/2015
	// Main page for Joey Longworth's future toy store
	// selling awesome sport's memorabilia!!!

	// Database credentials
	define('DATABASEADDRESS','localhost');  // Host name
	define('DATABASEUSER', 'root');         // User name
	define('DATABASEPASS', '');             // Database password
	define('DATABASENAME', 'longworth');    // Database name

	// Connect to the database
	@$database = new mysqli(DATABASEADDRESS, DATABASEUSER, DATABASEPASS, DATABASENAME);
		
// Queries
$listProductsQuery = "SELECT souvenir_id, athlete_name, category, souvenir_cost, souvenir
								from souvenir
								order by athlete_name";
								
// Prepared statements xD
$listProductsStatement = $database->prepare($listProductsQuery);		
				
	?>
<body>
   <header>
	<img class="titlePic" src="images/toolTime.jpg" alt="text" />	
		<h1>Welcome to Joey's Toy Store!!!</h1>
		
		<nav class="navClass">
			<a href="index.php">Souvenirs</a>
		</nav>
	
	</header>
	<h2>Sports Galore</h2>
	<table class="myTable">
		<?php
			$listProductsStatement->bind_result($souvenirId, $athleteName, $category, $souvenirCost, $item);
			$listProductsStatement->execute();
			
			while($listProductsStatement->fetch())
			{
				echo'
				<tr><td>Celebrity: </td><td>'.$athleteName.'</td></tr>
				<tr><td>Category: </td><td>'.$category.'</td></tr>
				<tr><td>Item Cost: </td><td>$'.number_format($souvenirCost, 2, ".", ",").'</td></tr>
				<tr><td>Description: </td><td>'.$item.'</td></tr>';
				
				// Display photos for item
				echo'<tr>';
				$photosArray = getPhotos($souvenirId);
				for($i = 0; $i < count($photosArray); $i++)
				{
					echo '<td><img class="cartPhoto" src="images/'.$photosArray[$i].'" alt="Text here" /></td>';
				}
				echo'</tr>';
				
				echo'
					<tr><td>
						<a href="souvenirDetails.php?p_id='.$souvenirId.'">See Details</a>
					</td></tr>';
			}
			$listProductsStatement->close();
			$database->close();
		?>	
	</table> 
	
	<?php
		function getPhotos($souvenirId)
		{
			$photosArray = array();
			
			// Connect to the database
			@$database = new mysqli(DATABASEADDRESS, DATABASEUSER, DATABASEPASS, DATABASENAME);
			$selectPhotosQuery = "select photo_location
											from photo
											where souvenir_id = ?";
			$selectPhotosStatement = $database->prepare($selectPhotosQuery);
			$selectPhotosStatement->bind_param("s", $souvenirId);
			$selectPhotosStatement->bind_result($photo);
			$selectPhotosStatement->execute();
			while($selectPhotosStatement->fetch())
			{
				$photosArray[] = $photo;
			}
			$selectPhotosStatement->close();
			$database->close();
			
			return $photosArray;
		}
	?>
 
</body>
</html>	
  